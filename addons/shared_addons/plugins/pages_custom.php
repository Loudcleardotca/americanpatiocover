<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Pages Plugin
 *
 * Create links and whatnot.
 *
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\Pages\Plugins
 */
class Plugin_Pages_Custom extends Plugin
{

	public $version = '1.0.0';
	public $name = array(
		'en' => 'Pages',
	);
	public $description = array(
		'en' => 'Output page data or build a list of pages in a page tree.',
	);

	/**
	 * Returns a PluginDoc array that PyroCMS uses 
	 * to build the reference in the admin panel
	 *
	 * All options are listed here but refer 
	 * to the Blog plugin for a larger example
	 *
	 * @todo fill the  array with details about this plugin, then uncomment the return value.
	 *
	 * @return array
	 */
	public function _self_doc()
	{
		$info = array(
			'teasers' => array(// the name of the method you are documenting
				'description' => array(// a single sentence to explain the purpose of this method
					'en' => 'Outputs a list of pages with teaser information.'
				),
				'single' => true,
				'double' => true,
				'variables' => '',
				'attributes' => array(
					'parent-id' => array(
						'type' => 'number',
						'flags' => '',
						'default' => '',
						'required' => false,
					),
					'parent-slug' => array(
						'type' => 'slug',
						'flags' => '',
						'default' => '',
						'required' => false,
					),
					'tags' => array(
						'type' => 'text',
						'flags' => '',
						'default' => '',
						'required' => false,
					),
					'order-by' => array(
						'type' => 'flag',
						'flags' => 'title|slug|uri|parent_id|status|created_on|updated_on|order|page_type_slug|page_type_title',
						'default' => 'sort',
						'required' => false,
					),
					'limit' => array(
						'type' => 'number',
						'flags' => '',
						'default' => '',
						'required' => false,
					),
				),
			),// end url method
			
		);
	
		return $info;
	}

	/**
	 * Get the URL of a page
	 *
	 * Attributes:
	 *  - (int) id : The id of the page to get the URL for.
	 *
	 * @return string
	 */
	public function categories()
	{
		$parent_id   	= $this->attribute('parent-id');
		$parent_slug   	= $this->attribute('parent-slug');
		$order_by   	= $this->attribute('order-by', 'order');
		$limit   		= $this->attribute('limit');
				
		if($parent_id !=NULL || $parent_slug != NULL)
		{
			//get pages by parent
			$parent_page = $this->db->select('pages.*')
				->where('pages.id', $parent_id)
				->or_where('pages.slug', $parent_slug)
				->where('status', 'live')
				->join('page_types', 'page_types.id = pages.type_id', 'left')
				->get('pages')
				->row();
			
			//print_r($parent_page);
			
			$pages = $this->db->select('pages.*, page_types.stream_id, page_types.slug as page_type_slug, page_types.title as page_type_title')
				->where('pages.parent_id', $parent_page->id)
				->where('status', 'live')
				->join('page_types', 'page_types.id = pages.type_id', 'left')
				->order_by($order_by)
				->get('pages')
				->result_array();
				
				//print_r($pages);
		} 
		
		
		return $pages;
	}
	
	
	
	
	
	/**
	 * Get the URL of a page
	 *
	 * Attributes:
	 *  - (int) id : The id of the page to get the URL for.
	 *
	 * @return string
	 */
	public function related_pages()
	{
		$tag   			= $this->attribute('tag');
		$page_id		= $this->attribute('page-id');
		$order_by   	= $this->attribute('order-by', 'sort');
		$limit   		= $this->attribute('limit', 3);
		
		if($tag == NULL && $page_id != NULL) 
		{
			//grab slug of parent as the tag
			//get pages by parent
			$page = $this->db->select('pages.slug')
				->where('pages.id', $page_id)
				->where('status', 'live')
				->join('page_types', 'page_types.id = pages.type_id', 'left')
				->get('pages')
				->row();
			//print_r($page);
			
			$tag = $page->slug;
		}
		
		if($tag != NULL)
		{
			// Here we need to add some custom joins into the
			// row query. This shouldn't be in the controller, but
			// we need to figure out where this sort of stuff should go.
			// Maybe the entire blog moduel should be replaced with stream
			// calls with items like this. Otherwise, this currently works.
			$this->row_m->sql['join'][] = 'JOIN '.$this->db->protect_identifiers('keywords_applied', true).' ON '.$this->db->protect_identifiers('keywords_applied.hash', true).' = '.$this->db->protect_identifiers('def_page_fields.tags', true);
	
			$this->row_m->sql['join'][] = 'JOIN '.$this->db->protect_identifiers('keywords', true).' ON '.$this->db->protect_identifiers('keywords.id', true).' = '.$this->db->protect_identifiers('keywords_applied.keyword_id', true);	
			
			//page data
			$this->row_m->sql['join'][] = 'JOIN '.$this->db->protect_identifiers('pages', true).' as pages ON pages.entry_id = '.$this->db->protect_identifiers('def_page_fields.id', true);	

			$this->row_m->sql['where'][] = $this->db->protect_identifiers('keywords.name', true)." = '".str_replace('-', ' ', $tag)."'";
			
			$this->row_m->sql['where'][] = "pages.status = 'live'";
			
			//select page and stream data
			$this->row_m->sql['select'][] = "pages.*, ".$this->db->protect_identifiers('def_page_fields', true).".*";
			
			//order by featured, then random after that
			$this->row_m->sql['order_by'][] = $this->db->protect_identifiers('def_page_fields', true).".featured DESC, rand()";

			// Get the blog posts
			$params = array(
				'stream'		=> 'def_page_fields',
				'namespace'		=> 'pages'
			);
			$stream_data = $this->streams->entries->get_entries($params);
			
			$pages = $stream_data['entries'];
			
			//print_r($pages);
			
			
			if(count($pages) > $limit) // Enforce the limit
			{
				$pages = array_slice($pages, 0, $limit);
			}
			//return pages array or false
			return (count($pages) > 0 ) ? array(array('status' => true, 'teasers' => $pages)) : false;
		}
		
		return false;
	}
	
	
	
	/**
	 * Page has function
	 *
	 * Check if this page has children
	 *
	 * Usage:
	 * {{ pages:has id="4" }}
	 *
	 * @param  int id   The id of the page you want to check
	 * @return bool
	 */
	public function has()
	{
		return $this->page_m->has_children($this->attribute('page_id'));
	}
	
	
	
	
	/**
	 * Children list
	 *
	 * Creates a list of child pages one level under the parent page.
	 *
	 * Attributes:
	 * - (int) limit: How many pages to show.
	 * - (string) order-by: One of the column names from the `pages` table.
	 * - (string) order-dir: Either `asc` or `desc`
	 *
	 * Usage:
	 * {{ pages:children id="1" limit="5" }}
	 *  <h2>{{title}}</h2>
	 *      {{body}}
	 * {{ /pages:children }}
	 *
	 * @return array
	 */
	public function all_children()
	{
		$limit			= $this->attribute('limit', 10);
		$order_by 		= $this->attribute('order-by', 'rand()');
		$page_types 	= $this->attribute('include_types');
		
		
		//get page's children and grab all parent_ids
		$all_children = $this->_get_children($this->attribute('id'));
		$parent_ids = array();
		foreach($all_children as $child) { $parent_ids[] = $child['parent_id']; }
		
		
		// Restrict page types.
		// Page types can be provided in a pipe (|) delimited string.
		// Ex: 4|6
		if ($page_types)
		{
			$types = explode('|', $page_types);
			$this->db->where_in('pages.type_id', $types);
		}
		
		
		//get all children using parent_ids, limit and order_by
		$pages = $this->db->select('pages.*, page_types.stream_id, page_types.slug as page_type_slug, page_types.title as page_type_title')
			->where_in('pages.parent_id', $parent_ids)
			->where('status', 'live')
			->join('page_types', 'page_types.id = pages.type_id', 'left')
			->order_by($order_by)
			->limit($limit)
			->get('pages')
			->result_array();
		
		
		
		
		
		
		
		// Custom fields provision.
		// Since page children can have many different page types,
		// we are going to do this in the most economical way possible:
		// Find the entries by ID and attach them to a special custom_fields
		// variable.
		if (is_scalar($this->content()) and strpos($this->content(), 'custom_fields') !== false and $pages)
		{
			$custom_fields = array();
			$this->load->driver('Streams');
		
			// Get all of the IDs by page type id.
			// Ex: array('page_type_id' => array('1', '2'))
			$entries = array();
			foreach ($pages as $page)
			{
				$entries[$page['stream_id']][] = $page['entry_id'];
			}

			// Get our entries by steram
			foreach ($entries as $stream_id => $entry_ids)
			{
				$stream = $this->streams_m->get_stream($stream_id);

				$params = array(
					'stream'		=> $stream->stream_slug,
					'namespace'		=> $stream->stream_namespace,
					'include'		=> implode('|', $entry_ids),
					'disable'		=> 'created_by'
				);

				$entries = $this->streams->entries->get_entries($params);

				// Set the entries in our custom fields array for
				// easy reference later when processing our pages.
				foreach ($entries['entries'] as $entry)
				{
					$custom_fields[$stream_id][$entry['id']] = $entry;	
				}
			}
		}
		else
		{
			$custom_fields = false;
		}

		// Legacy support for chunks
		if ($pages and $this->db->table_exists('page_chunks'))
		{
			foreach ($pages as &$page)
			{
				// Grab all the chunks that make up the body for this page
				$page['chunks'] = $this->db
					->get_where('page_chunks', array('page_id' => $page['id']))
					->result_array();

				$page['body'] = '';
				if ($page['chunks'])
				{
					foreach ($page['chunks'] as $chunk)
					{
						$page['body'] .= '<div class="page-chunk ' . $chunk['slug'] . '">' .
							(($chunk['type'] == 'markdown') ? $chunk['parsed'] : $chunk['body']) .
							'</div>' . PHP_EOL;
					}
				}
			}
		}
		
		$featured_pages = array();
		
		// Process our pages.
		foreach ($pages as $key => &$page)
		{
			// First, let's get a full URL. This is just
			// handy to have around.
			$page['url'] = site_url($page['uri']);
		
			// Now let's process our keywords hash.
			$keywords = Keywords::get($page['meta_keywords']);

			// In order to properly display the keywords in Lex
			// tags, we need to format them.
			$formatted_keywords = array();
			
			foreach ($keywords as $key)
			{
				$formatted_keywords[] 	= array('keyword' => $key->name);

			}
			$page['meta_keywords'] = $formatted_keywords;

			// If we have custom fields, we need to roll our
			// entry values in.
			if ($custom_fields and isset($custom_fields[$page['stream_id']][$page['entry_id']]))
			{
				$page['custom_fields'] = array($custom_fields[$page['stream_id']][$page['entry_id']]);
				
				//featured field
				if(isset($page['custom_fields'][0]['featured']) and $page['custom_fields'][0]['featured']['key'] == 1)
				{
					$featured_pages[] = $page;
					unset($pages[$key]);
				}
			}
			
		}
		
		if(count($featured_pages > 0)) 
			$pages = array_merge($featured_pages, $pages);
		
		
		//limit pages based on LIMIT
		if(count($pages) > $limit) // Enforce the limit
		{
			$pages = array_slice($pages, 0, $limit);
		}
		//return '<pre>'.print_r($pages, true).'</pre>';

		return $pages;
	}
	
	private function _get_children($parent_id) {
		$pages = $this->db->select('pages.*')
			->where('pages.parent_id', $parent_id)
			->where('status', 'live')
			->get('pages')
			->result_array();
		
		if(count($pages) > 0)
		{
			$temp_pages = $pages;
			foreach($temp_pages as $page)
			{
				//print_r($page);
				$parent_id = $page['id'];
				$children = $this->_get_children($parent_id);
				$pages = ($children != NULL) ? array_merge($pages, $children) : $pages;
			}
			
			return $pages;
		} 
		else
		{
			return NULL;
		}
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * 
	 *
	 * 
	 *
	 * BELOW IS FROM PAGE PLUGIN!!
	 * Can eventually be removed
	 *
	 * 
	 * 
	 */
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	/**
	 * Get a page by ID or slug
	 *
	 * Attributes:
	 * - (int) id: The id of the page.
	 * - (string) slug: The slug of the page.
	 *
	 * @return array
	 */
	public function display()
	{
		
		$page = $this->db->select('pages.*, page_types.stream_id, page_types.slug as page_type_slug, page_types.title as page_type_title')
			->where('pages.id', $this->attribute('page_id_test'))
			->or_where('pages.slug', $this->attribute('slug'))
			->where('status', 'live')
			->join('page_types', 'page_types.id = pages.type_id', 'left')
			->get('pages')
			->row();

		$page->body = '';

		// Legacy support for chunks
		if ($this->db->table_exists('page_chunks'))
		{
			// Grab all the chunks that make up the body
			$page->chunks = $this->db->get_where('page_chunks', array('page_id' => $page->id))->result();
    		
			if ($page->chunks)
			{
    				foreach ($page->chunks as $chunk)
				{
					$page->body .= '<div class="page-chunk '.$chunk->slug.'">' .
					(($chunk->type == 'markdown') ? $chunk->parsed : $chunk->body) .
					'</div>' . PHP_EOL;
				}
			}
    
			// we'll unset the chunks array as Lex is grouchy about mixed data at the moment
			unset($page->chunks);
		}

		// Check for custom fields
		if (is_scalar($this->content()) and strpos($this->content(), 'custom_fields') !== false and $page)
		{
			$custom_fields = array();
			$this->load->driver('Streams');

			$stream = $this->streams_m->get_stream($page->stream_id);

			$params = array(
				'stream'		=> $stream->stream_slug,
				'namespace'		=> $stream->stream_namespace,
				'include'		=> $page->entry_id,
				'disable'		=> 'created_by'
			);

			$entries = $this->streams->entries->get_entries($params);

			foreach ($entries['entries'] as $entry)
			{
				$custom_fields[$page->stream_id][$entry['id']] = $entry;
			}
		} else {
			$custom_fields = false;
		}

		// If we have custom fields, we need to roll our
		// entry values in.
		if ($custom_fields and isset($custom_fields[$page->stream_id][$page->entry_id]))
		{
			//$page->custom_fields = array($custom_fields[$page->stream_id][$page->entry_id]);
			$page->custom_fields = $custom_fields[$page->stream_id][$page->entry_id];
		}

		return $this->content() ? array($page) : $page->body;
	}

	/**
	 * Get a page chunk by page ID and chunk name
	 *
	 * Attributes:
	 * - (int) id : The id of the page.
	 * - (string) name : The name of the chunk.
	 * - (string) parse_tags : yes/no - whether or not to parse tags within the chunk
	 *
	 * @return string|bool
	 */
	public function chunk()
	{
		$parse_tags = str_to_bool($this->attribute('parse_tags', true));

		$chunk = $this->db
			->where('page_id', $this->attribute('id'))
			->where('slug', $this->attribute('name'))
			->get('page_chunks')
			->row_array();

		if ($chunk)
		{
			if ($this->content())
			{
				$chunk['parsed'] = $this->parse_chunk($chunk['parsed'], $parse_tags);
				$chunk['body']   = $this->parse_chunk($chunk['body'], $parse_tags);

				return $chunk;
			}
			else
			{
				return $this->parse_chunk(($chunk['type'] == 'markdown') ? $chunk['parsed'] : $chunk['body'], $parse_tags);
			}
		}
	}

	/**
	 * Parse chunk content
	 *
	 * @access private
	 * @param string the chunk content
	 * @param string parse Lex tags? - yes/no
	 * @return string
	 */
	private function parse_chunk($content, $parse_tags)
	{
		// Lex tags are parsed by default. If you want to
		// turn off parsing Lex tags, just set parse_tags to 'no'
		if (str_to_bool($parse_tags))
		{
			$parser = new Lex_Parser();
			$parser->scope_glue(':');

			return $parser->parse($content, array(), array($this->parser, 'parser_callback'));
		}
		else
		{
			return $content;
		}
	}

	/**
	 * Children list
	 *
	 * Creates a list of child pages one level under the parent page.
	 *
	 * Attributes:
	 * - (int) limit: How many pages to show.
	 * - (string) order-by: One of the column names from the `pages` table.
	 * - (string) order-dir: Either `asc` or `desc`
	 *
	 * Usage:
	 * {{ pages:children id="1" limit="5" }}
	 *  <h2>{{title}}</h2>
	 *      {{body}}
	 * {{ /pages:children }}
	 *
	 * @return array
	 */
	public function children()
	{
		$limit			= $this->attribute('limit', 10);
		$order_by 		= $this->attribute('order-by', 'order');
		$order_dir 		= $this->attribute('order-dir', 'ASC');
		$page_types 	= $this->attribute('include_types');

		// Restrict page types.
		// Page types can be provided in a pipe (|) delimited string.
		// Ex: 4|6
		if ($page_types)
		{
			$types = explode('|', $page_types);

			foreach ($types as $type)
			{
				$this->db->where('pages.type_id', $type);
			}
		}

		$pages = $this->db->select('pages.*, page_types.stream_id, page_types.slug as page_type_slug, page_types.title as page_type_title')
			->where('pages.parent_id', $this->attribute('id'))
			->where('status', 'live')
			->join('page_types', 'page_types.id = pages.type_id', 'left')
			->order_by($order_by, $order_dir)
			->limit($limit)
			->get('pages')
			->result_array();

		// Custom fields provision.
		// Since page children can have many different page types,
		// we are going to do this in the most economical way possible:
		// Find the entries by ID and attach them to a special custom_fields
		// variable.
		if (is_scalar($this->content()) and strpos($this->content(), 'custom_fields') !== false and $pages)
		{
			$custom_fields = array();
			$this->load->driver('Streams');
		
			// Get all of the IDs by page type id.
			// Ex: array('page_type_id' => array('1', '2'))
			$entries = array();
			foreach ($pages as $page)
			{
				$entries[$page['stream_id']][] = $page['entry_id'];
			}

			// Get our entries by steram
			foreach ($entries as $stream_id => $entry_ids)
			{
				$stream = $this->streams_m->get_stream($stream_id);

				$params = array(
					'stream'		=> $stream->stream_slug,
					'namespace'		=> $stream->stream_namespace,
					'include'		=> implode('|', $entry_ids),
					'disable'		=> 'created_by'
				);

				$entries = $this->streams->entries->get_entries($params);

				// Set the entries in our custom fields array for
				// easy reference later when processing our pages.
				foreach ($entries['entries'] as $entry)
				{
					$custom_fields[$stream_id][$entry['id']] = $entry;	
				}
			}
		}
		else
		{
			$custom_fields = false;
		}

		// Legacy support for chunks
		if ($pages and $this->db->table_exists('page_chunks'))
		{
			foreach ($pages as &$page)
			{
				// Grab all the chunks that make up the body for this page
				$page['chunks'] = $this->db
					->get_where('page_chunks', array('page_id' => $page['id']))
					->result_array();

				$page['body'] = '';
				if ($page['chunks'])
				{
					foreach ($page['chunks'] as $chunk)
					{
						$page['body'] .= '<div class="page-chunk ' . $chunk['slug'] . '">' .
							(($chunk['type'] == 'markdown') ? $chunk['parsed'] : $chunk['body']) .
							'</div>' . PHP_EOL;
					}
				}
			}
		}

		// Process our pages.
		foreach ($pages as &$page)
		{
			// First, let's get a full URL. This is just
			// handy to have around.
			$page['url'] = site_url($page['uri']);
		
			// Now let's process our keywords hash.
			$keywords = Keywords::get($page['meta_keywords']);

			// In order to properly display the keywords in Lex
			// tags, we need to format them.
			$formatted_keywords = array();
			
			foreach ($keywords as $key)
			{
				$formatted_keywords[] 	= array('keyword' => $key->name);

			}
			$page['meta_keywords'] = $formatted_keywords;

			// If we have custom fields, we need to roll our
			// entry values in.
			if ($custom_fields and isset($custom_fields[$page['stream_id']][$page['entry_id']]))
			{
				$page['custom_fields'] = array($custom_fields[$page['stream_id']][$page['entry_id']]);
			}
		}

		//return '<pre>'.print_r($pages, true).'</pre>';

		return $pages;
	}

	/**
	 * Page tree function
	 *
	 * Creates a nested ul of child pages
	 *
	 * Usage:
	 * {{ pages:page_tree start-id="5" }}
	 * optional attributes:
	 *
	 * disable-levels="slug"
	 * order-by="title"
	 * order-dir="desc"
	 * list-tag="ul"
	 * link="true"
	 *
	 * @param  array
	 * @return  array
	 */
	public function page_tree()
	{
		$start          = $this->attribute('start');
		$start_id       = $this->attribute('start-id', $this->attribute('start_id'));
		$disable_levels = $this->attribute('disable-levels');
		$order_by       = $this->attribute('order-by', 'order');
		$order_dir      = $this->attribute('order-dir', 'ASC');
		$list_tag       = $this->attribute('list-tag', 'ul');
		$link           = $this->attribute('link', true);

		// If we have a start URI, let's try and
		// find that ID.
		if ($start)
		{
			$page = $this->page_m->get_by_uri($start);

			if ( ! $page) {
				return null;
			}

			$start_id = $page->id;
		}

		// If our start doesn't exist, then
		// what are we going to do? Nothing.
		if ( ! $start_id) {
			return null;
		}

		// Disable individual pages or parent pages by submitting their slug
		$this->disable = explode("|", $disable_levels);

		return '<' . $list_tag . '>' . $this->_build_tree_html(array(
			'parent_id' => $start_id,
			'order_by'  => $order_by,
			'order_dir' => $order_dir,
			'list_tag'  => $list_tag,
			'link'      => $link
		)) . '</' . $list_tag . '>';
	}

	/**
	 * Page is function
	 *
	 * Check the pages parent or descendent relation
	 *
	 * Usage:
	 * {{ pages:is child="7" parent="cookbook" }} // return 1 (true)
	 * {{ pages:is child="recipes" descendent="books" }} // return 1 (true)
	 * {{ pages:is children="7,8,literature" parent="6" }} // return 0 (false)
	 * {{ pages:is children="recipes,ingredients,9" descendent="4" }} // return 1 (true)
	 *
	 * Use Id or Slug as param, following usage data reference
	 * Id: 4 = Slug: books
	 * Id: 6 = Slug: cookbook
	 * Id: 7 = Slug: recipes
	 * Id: 8 = Slug: ingredients
	 * Id: 9 = Slug: literature
	 *
	 * @param  array Plugin attributes
	 * @return int   0 or 1
	 */
	public function is()
	{
		$children_ids = $this->attribute('children');
		$child_id     = $this->attribute('child');

		if ( ! $children_ids)
		{
			return (int) $this->_check_page_is($child_id);
		}

		$children_ids = explode('|', str_replace(',', '|', $children_ids));
		$children_ids = array_map('trim', $children_ids);

		if ($child_id)
		{
			$children_ids[] = $child_id;
		}

		foreach ($children_ids as $child_id)
		{
			if ( ! $this->_check_page_is($child_id))
			{
				return (int) false;
			}
		}

		return (int) true;
	}

	

	/**
	 * Check Page is function
	 *
	 * Works for page is function
	 *
	 * @param  mixed  The Id or Slug of the page
	 * @param  array  Plugin attributes
	 * @return int    0 or 1
	 */
	private function _check_page_is($child_id = 0)
	{
		$descendent_id = $this->attribute('descendent');
		$parent_id     = $this->attribute('parent');

		if ($child_id and $descendent_id)
		{
			if ( ! is_numeric($child_id))
			{
				$child_id = ($child = $this->page_m->get_by(array('slug' => $child_id))) ? $child->id : 0;
			}

			if ( ! is_numeric($descendent_id))
			{
				$descendent_id = ($descendent = $this->page_m->get_by(array('slug' => $descendent_id))) ? $descendent->id : 0;
			}

			if ( ! ($child_id and $descendent_id))
			{
				return false;
			}

			$descendent_ids = $this->page_m->get_descendant_ids($descendent_id);

			return in_array($child_id, $descendent_ids);
		}

		if ($child_id and $parent_id)
		{
			if ( ! is_numeric($child_id))
			{
				$parent_id = ($parent = $this->page_m->get_by(array('slug' => $parent_id))) ? $parent->id : 0;
			}

			return $parent_id ? (int) $this->page_m->count_by(array(
				(is_numeric($child_id) ? 'id' : 'slug') => $child_id,
				'parent_id' => $parent_id
			)) > 0 : false;
		}
	}
	
	/**
	 * Tree html function
	 *
	 * Creates a page tree
	 *
	 * @param  array
	 * @return  array
	 */
	private function _build_tree_html($params)
	{
		$params = array_merge(array(
			'tree'         => array(),
			'parent_id'    => 0
		), $params);

		extract($params);

		if ( ! $tree)
		{
			$this->db
				->select('id, parent_id, slug, uri, title')
				->where_not_in('slug', $this->disable);
			
			// check if they're logged in
			if ( isset($this->current_user->group) )
			{
				// admins can see them all
				if ($this->current_user->group != 'admin')
				{
					$id_list = array();
					
					$page_list = $this->db
						->select('id, restricted_to')
						->get('pages')
						->result();

					foreach ($page_list as $list_item)
					{
						// make an array of allowed user groups
						$group_array = explode(',', $list_item->restricted_to);

						// if restricted_to is 0 or empty (unrestricted) or if the current user's group is allowed
						if ( ($group_array[0] < 1) or in_array($this->current_user->group_id, $group_array) )
						{
							$id_list[] = $list_item->id;
						}
					}
					
					// if it's an empty array then evidently all pages are unrestricted
					if ( count($id_list) > 0 )
					{
						// select only the pages they have permissions for
						$this->db->where_in('id', $id_list);
					}
					
					// since they aren't an admin they can't see drafts
					$this->db->where('status', 'live');
				}
			}
			else
			{
				//they aren't logged in, show them all live, unrestricted pages
				$this->db
					->where('status', 'live')
					->where('restricted_to <', 1)
					->or_where('restricted_to', null);
			}
			
			$pages = $this->db
				->order_by($order_by, $order_dir)
				->get('pages')
				->result();

			if ($pages)
			{
				foreach ($pages as $page)
				{
					$tree[$page->parent_id][] = $page;
				}
			}
		}

		if ( ! isset($tree[$parent_id]))
		{
			return;
		}

		$html = '';

		foreach ($tree[$parent_id] as $item)
		{
			$html .= '<li';
			$html .= (current_url() == site_url($item->uri)) ? ' class="current">' : '>';
			$html .= ($link === true) ? '<a href="' . site_url($item->uri) . '">' . $item->title . '</a>' : $item->title;
			
			
			$nested_list = $this->_build_tree_html(array(
				'tree'         => $tree,
				'parent_id'    => (int) $item->id,
				'link'         => $link,
				'list_tag'     => $list_tag
			));
			
			if ($nested_list)
			{
				$html .= '<' . $list_tag . '>' . $nested_list . '</' . $list_tag . '>';
			}
			
			$html .= '</li>';
		}

		return $html;
	}
}
