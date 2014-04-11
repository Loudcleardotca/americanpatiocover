<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller
{
    protected $cms_version = 2.1;

    private $validation_rules = array(
        array(
            'field' => 'link_type',
            'label' => 'lang:sitemap_type_label',
            'rules' => 'trim|required|alpha'
        ),
        array(
            'field' => 'module_id',
            'label' => 'lang:sitemap_module_label',
            'rules' => 'trim|numeric'
        ),
        array(
            'field' => 'page_id',
            'label' => 'lang:sitemap_page_label',
            'rules' => 'trim|numeric'
        )
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model(array('sitemapp_m', 'pages/page_m'));
        $this->load->helper(array('cms_version'));
        $this->lang->load('sitemapp');
        $this->cms_version = cms_version(CMS_VERSION);
    }

    function index()
    {
        $this->template
            ->title($this->module_details['name'])
            ->set('excludes', $this->sitemapp_m->get_all())
            ->build('admin/index');
    }

    public function create()
    {
        // The eventual array containing exluded modules
        $module_excludes = array();
        $module_selects  = array();
        // The excludes set by the user
        $user_excludes_temp = $this->sitemapp_m->get_excluded_modules();
        $manual_excludes    = array('pages', 'sitemap', $this->module);
        foreach ($user_excludes_temp as $value) {
            // It's on purpose we use the slug, it can be added multiple times and we don't know ids of this module and pages for example
            $module_excludes[] = $value['slug'];
        }
        if ($modules = $this->sitemapp_m->get_modules(array_merge($module_excludes, $manual_excludes))) {
            foreach ($modules as $module) {
                $module_name                 = unserialize($module->name);
                $module_selects[$module->id] = (isset($module_name[CURRENT_LANGUAGE])) ? ($module_name[CURRENT_LANGUAGE]) : ($module_name['en']);
            }
        }
        $this->form_validation->set_rules($this->validation_rules);
        if ($this->form_validation->run()) {
            $input_post = $this->input->post();
            $data       = array(
                'link_type'  => $input_post['link_type'],
                'module_id'  => (isset($input_post['module_id'])) ? ((int)$input_post['module_id']) : (0),
                'page_id'    => (isset($input_post['page_id'])) ? ((int)$input_post['page_id']) : (0),
                'created_on' => now()
            );
            $error      = true;
            if (in_array($data['link_type'], array('module', 'page'))) {
                $this->pyrocache->delete_all('sitemapp_m');
                if ($data['link_type'] == 'module' && array_key_exists($data['module_id'], $module_selects)) {
                    $error = false;
                    $this->session->set_flashdata('success', sprintf(lang('sitemap_module_add_success'), $module_selects[$data['module_id']]));
                }
                else {
                    $this->load->model('pages/page_m');
                    $page = $this->page_m->get($data['page_id'], false);
                    if ($page) {
                        $error = false;
                        $this->session->set_flashdata('success', sprintf(lang('sitemap_page_add_success'), $page->title));
                    }
                }
            }
            if (!$error) {
                if ($this->sitemapp_m->insert_exclude($data) > 0) {
                    $error = false;
                }
            }
            ($error) ? ($this->session->set_flashdata('error', lang('sitemap_link_add_error'))) : (null);
            redirect('admin/' . $this->module);
        }
        foreach ($this->validation_rules as $rule) {
            $link->{$rule['field']} = set_value($rule['field']);
        }
        $tree_select = $this->_build_tree_select(array('current_parent' => $link->page_id));
        // Ow, we have no more pages or modules to exclude eh?
        if ($tree_select == null && empty($module_selects)) {
            $this->session->set_flashdata('notice', lang('sitemapp.out_of_excludes'));
            redirect('admin/' . $this->module);
        }
        // Make sure we have this module compatible with multiple PyroCMS versions :)
        if ($this->cms_version < 2.1) {
            $this->template->append_metadata(js('sitemap.js', $this->module));
        }
        else {
            $this->template->append_js('module::sitemap.js');
        }
        $this->template
            ->title($this->module_details['name'], lang('sitemap_exclude_create_title'))
            ->set('link', $link)
            ->set('module_selects', $module_selects)
            ->set('tree_select', $tree_select)
            ->build('admin/form');
    }

    public function delete($id = 0)
    {
        $id_array = (!empty($id)) ? array($id) : $this->input->post('action_to');
        // Loop through each item to delete
        if (!empty($id_array)) {
            if ($this->sitemapp_m->delete($id_array)) {
                // Flush the cache and redirect
                $this->pyrocache->delete_all('sitemapp_m');
                $this->session->set_flashdata('success', $this->lang->line('sitemap_link_delete_success'));
            }
            else {
                $this->session->set_flashdata('error', $this->lang->line('sitemap_link_delete_error'));
            }
            redirect('admin/' . $this->module);
        }
        else {
            // No items have been selected, show error message here and make the redirect
            $this->session->set_flashdata('error', $this->lang->line('sitemap_link_delete_no_success'));
            redirect('admin/' . $this->module);
        }
    }

    function _build_tree_select($params)
    {
        $params = array_merge(array(
            'tree'           => array(),
            'parent_id'      => 0,
            'current_parent' => 0,
            'current_id'     => 0,
            'level'          => 0
        ), $params);
        extract($params);
        if (!$tree) {
            if ($pages = $this->db->select('id, parent_id, title')->get('pages')->result()) {
                foreach ($pages as $page) {
                    $tree[$page->parent_id][] = $page;
                }
            }
        }
        if (!isset($tree[$parent_id])) {
            return;
        }
        $html    = null;
        $results = $this->db
            ->select('pages.id')
            ->from('pages')
            ->join('sitemapper se', 'se.page_id = pages.id')
            ->where('link_type', 'page')
            ->get()
            ->result_array();
        // Select all those pages which are already excluded
        $pages_to_remove = array();
        foreach ($results as $result) {
            $pages_to_remove[] = $result['id'];
        }
        foreach ($tree[$parent_id] as $item) {
            //If it's not an id that has already be excluded from sitemapping, continue
            if (!in_array($item->id, $pages_to_remove)) {
                if ($current_id == $item->id) {
                    continue;
                }
                $html .= '<option value="' . $item->id . '"';
                $html .= $current_parent == $item->id ? ' selected="selected">' : '>';
                if ($level > 0) {
                    for ($i = 0; $i < ($level * 2); $i++) {
                        $html .= '&nbsp;';
                    }
                    $html .= '-&nbsp;';
                }
                $html .= $item->title . '</option>';
                $html .= $this->_build_tree_select(array(
                    'tree'           => $tree,
                    'parent_id'      => (int)$item->id,
                    'current_parent' => $current_parent,
                    'current_id'     => $current_id,
                    'level'          => $level + 1
                ));
            }
        }
        return $html;
    }
}

?>