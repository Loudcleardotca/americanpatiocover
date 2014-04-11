<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Images_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
		$this->_table = 'images_image';
	}
	
	public function get_images($page)
	{
		$data = array();
		
		$this->db
			->from($this->_table)
			->where('page', $page);
			
		$query = $this->db->get();
		$images = $query->result();
		
		foreach($images as $image) {
			$data[$image->thumbnail_id] = $image;
		}
		
		return $data;
	}
	
	public function get_image($page, $thumbnail_id)
	{
		$this->db
			->from($this->_table)
			->where('page', $page)
			->where('thumbnail_id', $thumbnail_id)
			->limit(1);
			
		$query = $this->db->get();
		$result = $query->result();
		
		return $result;
	}
	
	public function insert_image($to_insert)
	{
		return $this->db->insert($this->_table, $to_insert);
	}
	
	public function delete_image($page, $thumbnail_id)
	{
		$this->db->where('page', $page);
		$this->db->where('thumbnail_id', $thumbnail_id);
		return $this->db->delete($this->_table);
	}
	
	public function get_thumbnails($id)
	{	
		$layout = $this->db->where('id', $id)->get('pages')->row();
		
		return $this->db
			->where('layout', $layout->layout_id)
			->or_where('layout', 0)
			->order_by('order', 'asc')
			->get('images_thumbnails')
			->result();
	}
	
	public function get_thumbnail_width($id)
	{
		return $this->db->select('width')->where('id', $id)->get('images_thumbnails')->row()->width;
	}
	
	public function get_thumbnail_height($id)
	{
		return $this->db->select('height')->where('id', $id)->get('images_thumbnails')->row()->height;
	}
	
	public function get_folder($id)
	{
		return $this->db->select('folder')->where('id', $id)->get('images_thumbnails')->row()->folder;
	}
	
}