<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Images Module
 *
 * @author 		Patrick Kivits - Woodits Webbureau
 * @website		http://woodits.nl
 * @package 	PyroCMS
 * @subpackage 	Images Module
 */
class Admin extends Admin_Controller
{
	protected $section = 'images';

	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('files/files');
		$this->load->model('images_m');
		$this->lang->load('general');
		$this->lang->load('images');
	}

	public function index()
	{	
		$this->template
			->title($this->module_details['name'])
			->append_js('jquery/jquery.ui.nestedSortable.js')
			->append_js('jquery/jquery.cooki.js')
			->append_js('jquery/jquery.stickyscroll.js')
			->append_js('module::index.js')
			->append_css('module::index.css')
			// Make javascript constant for ADDONPATH
			->append_metadata('<script type="text/javascript">var ADDONPATH = "'.ADDONPATH.'";</script>')
			->set('pages', $this->page_m->get_page_tree())
			->build('admin/'.$this->module.'/items');
	}
	
	public function ajax_upload_image()
	{		
		$thumbnail_id = $this->input->post('thumbnail_id');
		
		// Upload file and register upload in files module
		if($upload_data = Files::upload($this->images_m->get_folder($thumbnail_id), false, 'file')) {
			
			// Upload and resize thumbnail image
			$thumbnail_upload_data = Files::upload(
				$this->images_m->get_folder($thumbnail_id),
				$upload_data['data']['name'].' '.$this->images_m->get_thumbnail_width($thumbnail_id).' x '.$this->images_m->get_thumbnail_height($thumbnail_id),
				'file'
			);
			
			/**************************************************************************/
	
			// Resize thumbnail image
			$this->load->library('image_lib');

			unset($config);
			$config['source_image'] = UPLOAD_PATH.'files/'.$thumbnail_upload_data['data']['filename'];
			$config['maintain_ratio'] = TRUE;
			$config['quality'] = '100%';
			$config['width'] = $this->images_m->get_thumbnail_width($thumbnail_id);
			$config['height'] = $this->images_m->get_thumbnail_height($thumbnail_id);
	
			if($thumbnail_upload_data['data']['width'] > $thumbnail_upload_data['data']['height']) {
				$config['master_dim'] = 'width';
				
				if($this->images_m->get_thumbnail_height($thumbnail_id) > $thumbnail_upload_data['data']['width'])
				{
					$config['master_dim'] = 'height';
				}
			} elseif($thumbnail_upload_data['data']['height'] > $thumbnail_upload_data['data']['width']) {
				$config['master_dim'] = 'height';
				
				if($this->images_m->get_thumbnail_width($thumbnail_id) > $thumbnail_upload_data['data']['height'])
				{
					$config['master_dim'] = 'width';
				}
			} else {
				if($this->images_m->get_thumbnail_height($thumbnail_id) > $thumbnail_upload_data['data']['width'])
				{
					$config['master_dim'] = 'height';
				} else {
					$config['master_dim'] = 'width';
				}
			}

			$this->image_lib->initialize($config);
			$this->image_lib->resize();
			$this->image_lib->clear();
			
			unset($config);
			$thumb_image = UPLOAD_PATH.'files/'.$thumbnail_upload_data['data']['filename'];			
			$image_size = getimagesize($thumb_image);
			$config['source_image'] = $thumb_image;
			
			$config['x_axis'] = round(($image_size[0] - $this->images_m->get_thumbnail_width($thumbnail_id)) / 2);
			$config['y_axis'] = round(($image_size[1] - $this->images_m->get_thumbnail_height($thumbnail_id)) / 2);
			$config['width'] = $this->images_m->get_thumbnail_width($thumbnail_id);
			$config['height'] = $this->images_m->get_thumbnail_height($thumbnail_id);		
			
			$config['quality'] = '100%';
			$config['maintain_ratio'] = FALSE;
			
			$this->image_lib->initialize($config);
			$this->image_lib->crop();

			/**************************************************************************/
			
			/* Delete old image first */
			$images = $this->images_m->get_image($this->input->post('page'), $this->input->post('thumbnail_id'));
			
			foreach($images as $image) {
				if($image->thumbnail_file_id) {
					Files::delete_file($image->thumbnail_file_id);
				}
				if($image->image_file_id) {
					Files::delete_file($image->image_file_id);
				}
			}
			
			/* Save image to database */
			$to_insert = array(
				'page' => $this->input->post('page'),
				'thumbnail_id' => $this->input->post('thumbnail_id'),
				'image_file_id' => $upload_data['data']['id'],
				'thumbnail_file_id' => $thumbnail_upload_data['data']['id'],
				'image' => $upload_data['data']['filename'],
				'thumbnail' => $thumbnail_upload_data['data']['filename']
			);
			
			$this->images_m->delete_image($to_insert['page'], $to_insert['thumbnail_id']);
			$this->images_m->insert_image($to_insert);
			
			/* Output JSON */
			echo json_encode(array('page' => $this->input->post('page')));
		}
	}
	
	public function ajax_delete_image()
	{	
		if($this->input->is_ajax_request()) {
			if($this->input->post('thumbnail_file_id')) {
				Files::delete_file($this->input->post('thumbnail_file_id'));
			}
			if($this->input->post('image_file_id')) {
				Files::delete_file($this->input->post('image_file_id'));
			}
			
			$this->images_m->delete_image($this->input->post('page'), $this->input->post('thumbnail_id'));
		} else {	
			redirect('admin/'.$this->module);
		}
	}
	
	public function ajax_page_images()
	{
		if($this->input->is_ajax_request()) {
			$data['thumbnails'] = $this->images_m->get_thumbnails($this->uri->segment(4));
			$data['images'] = $this->images_m->get_images($this->uri->segment(4));
			
			/* Load ajax view with thumbnails and images */
			$this->load->view('admin/'.$this->module.'/ajax/page_images', $data);
		} else {	
			redirect('admin/'.$this->module);
		}
	}
	
}