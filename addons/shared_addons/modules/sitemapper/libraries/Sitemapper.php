<?php

class Sitemapper
{
    private static $ci = null;

    private static $theme_layout = false;
    private static $load_theme_views = false;
    private static $enabled = false;
    private static $queried = false;

    private static $attributes = array();
    private static $build = array();

    public function __construct()
    {
        self::$ci         =& get_instance();
        self::$attributes = array(
            'heading_m'    => 3,
            'heading_c'    => 4,
            'grab_modules' => array(),
            'excludes'     => array(),
        );

    }

    /*
     * Set own attributes, such as which modules to grab, which to exclude etc., see attributes default values above
     */
    public static function set_attributes($attributes = array())
    {
        self::$attributes = $attributes;
    }

    public static function enable()
    {
        // Only do this, upon having the status disabled (timesaver)
        if (!self::$enabled) {
            self::$enabled = true;
            // Common needed models, helpers and configs
            self::$ci->lang->load('sitemapp');
            self::$ci->load->model(array('sitemapp_m', 'blog/blog_m'));
            self::$ci->load->helper('html');
            self::$ci->load->config('sitemapper_c');
            self::$load_theme_views = config_item('sitemap_views');
            // Only do this specific thing, when we want to load those views in the first place. Saves some processing right?
            if (self::$load_theme_views) {
                foreach (self::$ci->template->get_theme_layouts() as $layout) {
                    if (self::$ci->template->layout_is($layout)) {
                        self::$theme_layout = $layout;
                        break;
                    }
                }
            }
        }
    }

    private static function _tree_builder($page)
    {
        $return = null;
        if (isset($page['children'])) {
            foreach ($page['children'] as $page) {
                $return .= '<li>' . anchor($page['uri'], $page['title']);
                if (isset($page['children'])) {
                    $return .= '<ul class="child_pages">' . self::_tree_builder($page) . '</ul>';
                }
                $return .= '</li>';
            }
        }
        return $return;
    }

    private static function build()
    {
        if ($modules = self::$ci->sitemapp_m->get_all_modules(array('sitemap'))) {
            // Use the modules query, and start fetching
            foreach ($modules as $module) {
                $slug               =& $module->slug;
                $module_name        = unserialize($module->name);
                self::$build[$slug] = heading((isset($module_name[CURRENT_LANGUAGE])) ? ($module_name[CURRENT_LANGUAGE]) : ($module_name['en']), self::$attributes['heading_m']);
                switch ($slug) {
                    case 'pages':
                        $pages = array();
                        if ($pages_db = self::$ci->sitemapp_m->get_page_tree()) {
                            foreach ($pages_db as $key => $page) {
                                $pages[$key] = anchor($page['uri'], $page['title']);
                                if (isset($page['children'])) {
                                    $pages[$key] .= '<ul class="child_pages">' . self::_tree_builder($page) . '</ul>';
                                }
                            }
                            self::$build[$slug] .= ul($pages, array('class' => 'main_pages'));
                        }
                        break;
                    case 'galleries':
                        $galleries    = array();
                        $galleries_db = self::$ci->db
                            ->select('g.id, g.title, g.slug, gi.id AS image_id, f.name, f.filename')
                            ->from('galleries g')
                            ->where('published', 1)
                            ->join('gallery_images gi', 'gi.gallery_id = g.id')
                            ->join('files f', 'gi.file_id = f.id')
                            ->get()
                            ->result();
                        foreach ($galleries_db as $gallery_db) {
                            if (!isset($galleries[$gallery_db->slug])) {
                                $galleries[$gallery_db->slug] = array(
                                    'header'   => heading(anchor('galleries/' . $gallery_db->slug, $gallery_db->title), self::$attributes['heading_c']),
                                    'children' => array()
                                );
                            }
                            else {
                                $galleries[$gallery_db->slug]['children'][] = anchor('galleries/' . $gallery_db->slug . '/' . $gallery_db->image_id, ($gallery_db->name != '') ? ($gallery_db->name) : ($gallery_db->filename));
                            }
                        }
                        foreach ($galleries as $key => $value) {
                            $this_gallery = $value['header'];
                            if (!empty($value['children'])) {
                                $this_gallery .= ul($value['children']);
                            }
                            $galleries[$key] = ul($this_gallery);
                        }
                        if (!empty($galleries)) {
                            self::$build[$slug] .= ul($galleries);
                        }
                        break;
                    case 'blog':
                        $blog       = array();
                        $categories = array();
                        if ($blog_articles = self::$ci->blog_m->get_all()) {
                            foreach ($blog_articles as $article) {
                                $temp_blog_articles[$article->category_slug] = (isset($temp_blog_articles[$article->category_slug]))
                                    ? ($temp_blog_articles[$article->category_slug]) : (NULL);
                                // Store the article's category
                                $categories[$article->category_title] = $article->category_slug;
                                $temp_blog_articles[$article->category_slug] .= '<li>' . anchor('blog/' . date('Y/m', $article->created_on) . '/' . $article->slug, $article->title) . '</li>';
                            }
                            // Use all the categories from above fetched articles
                            if (!empty($categories)) {
                                foreach ($categories as $key => $value) {
                                    $blog[$value] = heading(anchor('blog/category/' . $value, $key), self::$attributes['heading_c']);
                                    // Check if it's really there
                                    if (isset($temp_blog_articles[$value])) {
                                        // Build the unordened list
                                        $blog[$value] .= '<ul>' . $temp_blog_articles[$value] . '</ul>';
                                    }
                                }
                            }
                        }
                        if (!empty($blog)) {
                            self::$build[$slug] .= ul($blog);
                        }
                        break;
                    default:
                        if (self::$load_theme_views) {
                            // Check first for this specific view in our theme, before we try and load it!
                            if (file_exists(self::$ci->theme->path . '/views/modules/sitemapper/' . $slug . '.php')) {
                                self::$build[$slug] .= self::$ci->template
                                    ->set_layout(false)
                                    ->build('sitemapper/' . $slug, array(), true);
                                // Make sure we set our theme layout back to normal!
                                self::$ci->template->set_layout(self::$theme_layout);
                            }
                        }
                        break;
                }
            }
        }
        self::$queried = true;
    }

    public static function show()
    {
        if (!self::$queried) {
            self::build();
        }
        $return = null;
        if (!empty(self::$attributes['grab_modules'])) {
            foreach (self::$attributes['grab_modules'] as $module) {
                if (isset(self::$build[$module])) {
                    $return .= self::$build[$module];
                }
            }
        }
        else {
            foreach (self::$build as $key => $value) {
                if (empty(self::$attributes['excludes']) || !in_array($key, self::$attributes['excludes'])) {
                    $return .= $value;
                }
            }
        }
        return $return;
    }
}