<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Example Plugin
 *
 * Quick plugin to demonstrate how things work
 *
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Addon\Plugins
 * @copyright	Copyright (c) 2009 - 2010, PyroCMS
 */
class Plugin_My_pages extends Plugin
{
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
		$page = $this->db
			->where('pages.id', $this->attribute('page-id'))
			->or_where('pages.slug', $this->attribute('slug'))
			->where('status', 'live')
			->get('pages')
			->row_array();
		echo 'id = '.$this->attribute('id');
		// Grab all the chunks that make up the body
		$page['chunks'] = $this->db->get_where('page_chunks', array('page_id' => $page['id']))->result_array();
		
		$page['body'] = '';
		if ($page['chunks'])
		{
			foreach ($page['chunks'] as $chunk)
			{
				$page['body'] .= 	'<div class="page-chunk ' . $chunk['slug'] . '">' .
										(($chunk['type'] == 'markdown') ? $chunk['parsed'] : $chunk['body']) .
									'</div>'.PHP_EOL;
			}
		}

		// we'll unset the chunks array as Lex is grouchy about mixed data at the moment
		unset($page['chunks']);

		return $this->content() ? array($page) : $page['body'];
	}
}

/* End of file example.php */