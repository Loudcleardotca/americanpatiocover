<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Thumbnails_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
		$this->_table = 'images_thumbnails';
	}
	
	public function get_all()
	{
		$this->db->from($this->_table)
			->order_by('order', 'asc');
		
		$query = $this->db->get();
		return $query->result();	
	}
	
	public function create($input)
	{	
		$to_insert = array(
			'name' => $input['name'],
			'slug' => $this->_check_slug($input['slug']),
			'layout' => $input['layout'],
			'folder' => $input['folder'],
			'width' => $input['width'],
			'height' => $input['height'],
			'order' => $this->get_order()
		);
		
		return $this->db->insert($this->_table, $to_insert);
	}
	
	public function update($id, $input)
	{	
		$to_insert = array(
			'name' => $input['name'],
			'slug' => $this->_check_slug($input['slug']),
			'layout' => $input['layout'],
			'folder' => $input['folder'],
			'width' => $input['width'],
			'height' => $input['height'],
		);
		
		$this->db->where('id', $id);
		return $this->db->update($this->_table, $to_insert);
	}
	
	private function _check_slug($slug)
	{
		$slug = strtolower($slug);
		$slug = preg_replace('/\s+/', '-', url_title($slug, 'dash', TRUE));

		return $slug;
	}
	
	private function get_order()
	{
		$this->db
			->from($this->_table)	
			->order_by('order', 'desc')
			->limit(1);
			
		$query = $this->db->get();
		$result = $query->result();
		
		if(isset($result[0])) {
			return ($result[0]->order + 1);
		} else {
			return 0;	
		}
	}
	
	public function order($order)
	{
		foreach($order as $order => $id)
		{
			$this->db->where('id', $id);
			$this->db->update($this->_table, array('order' => $order));
		}
		return true;	
	}
	
	public function check_images($id)
	{
		$this->db
			->from('images_image')
			->where('thumbnail_id', $id);	
			
		return $this->db->count_all_results() > 0 ? true : false;
	}
	
	public function get_page($id = 0)
	{
		$query = $this->db->get_where('images_thumbnails', array('id'=>$id));

		if ($query->num_rows() == 0)
		{
			return FALSE;
		}
		else
		{
			return $query->row();
		}
	}
	
	public function get_page_layouts()
	{
		$layouts = $this->db->get('page_layouts')->result();
		
		$data[] = lang('thumbnails:all_layouts');
		foreach($layouts as $layout) {
			$data[$layout->id] = $layout->title;
		}
		
		return $data;	
	}
	
	public function get_file_folders()
	{
		$folders = $this->db->get('file_folders')->result();
		
		foreach($folders as $folder) {
			$data[$folder->id] = $folder->name;
		}
		
		return $data;	
	}
	
}