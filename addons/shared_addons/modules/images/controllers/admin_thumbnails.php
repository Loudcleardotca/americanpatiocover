<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Products Module
 *
 * @author 		Patrick Kivits - Woodits Webbureau
 * @website		http://woodits.nl
 * @package 	PyroCMS
 * @subpackage 	Products Module
 */
class Admin_thumbnails extends Admin_Controller
{
	protected $section = 'thumbnails';

	public function __construct()
	{
		parent::__construct();

		$this->load->model('thumbnails_m');
		$this->load->library('form_validation');
		$this->load->library('files/files');
		$this->lang->load('general');
		$this->lang->load('thumbnails');

		$this->item_validation_rules = array(
			array(
				'field' => 'name',
				'label' => lang('thumbnails:name'),
				'rules' => 'trim|max_length[100]|required'
			),
			array(
				'field' => 'slug',
				'label' => lang('thumbnails:slug'),
				'rules' => 'trim|max_length[100]|required'
			),
			array(
				'field' => 'layout',
				'label' => lang('thumbnails:layout'),
				'rules' => 'trim|max_length[100]|required'
			),
			array(
				'field' => 'folder',
				'label' => lang('thumbnails:folder'),
				'rules' => 'trim|max_length[100]|required'
			),
			array(
				'field' => 'width',
				'label' => lang('thumbnails:width'),
				'rules' => 'trim|max_length[100]|integer|required'
			),
			array(
				'field' => 'height',
				'label' => lang('thumbnails:height'),
				'rules' => 'trim|max_length[100]|integer|required'
			)
		);
		
		$this->template
			->append_js('module::jquery.ui.js')
			->append_js('module::jquery.cookie.js')
			->append_js('module::admin.js');
	}

	public function index()
	{
		$items = $this->thumbnails_m->get_all();

		$this->template
			->title($this->module_details['name'])
			->set('items', $items)
			->build('admin/thumbnails/items');
	}

	public function create()
	{
		$this->form_validation->set_rules($this->item_validation_rules);

		if($this->form_validation->run())
		{
			if($this->thumbnails_m->create($this->input->post()))
			{
				$this->session->set_flashdata('success', lang('general:success'));
				redirect('admin/'.$this->module.'/thumbnails');
			}
			else
			{
				$this->session->set_flashdata('error', lang('general:error'));
				redirect('admin/'.$this->module.'/thumbnails/create');
			}
		}
		
		foreach ($this->item_validation_rules AS $rule)
		{
			$thumbnails->{$rule['field']} = $this->input->post($rule['field']);
		}

		$this->template
			->title($this->module_details['name'], lang('thumbnails:create'))
			->set('thumbnails', $thumbnails)
			->set('page_layouts', $this->thumbnails_m->get_page_layouts())
			->set('file_folders', $this->thumbnails_m->get_file_folders())
			->append_js('module::form.js')
			->build('admin/thumbnails/form');
	}
	
	public function edit($id = 0)
	{
		$id = $this->uri->segment(5);
		$id or redirect('admin/'.$this->module);
		
		$thumbnails = $this->thumbnails_m->get($id);
		
		// Get the navigation item based on the ID
		$page = $this->thumbnails_m->get_page($id);

		$this->form_validation->set_rules($this->item_validation_rules);

		if($this->form_validation->run())
		{
			unset($_POST['btnAction']);
			
			if($this->thumbnails_m->update($id, $this->input->post()))
			{
				$this->session->set_flashdata('success', lang('general:success'));
				redirect('admin/'.$this->module.'/thumbnails');
			}
			else
			{
				$this->session->set_flashdata('error', lang('general:error'));
				redirect('admin/'.$this->module.'/thumbnails/create');
			}
		}
		
		$this->template
			->title($this->module_details['name'], lang('thumbnails:edit'))
			->set('thumbnails', $thumbnails)
			->set('page_layouts', $this->thumbnails_m->get_page_layouts())
			->set('file_folders', $this->thumbnails_m->get_file_folders())
			->append_js('module::form.js')
			->build('admin/thumbnails/form');
	}
	
	public function delete($id = 0)
	{
		if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
		{
			foreach($this->input->post('action_to') as $key => $id) {
				if(!$this->thumbnails_m->check_images($id)) {
					$this->thumbnails_m->delete($id);
				} else {
					$this->session->set_flashdata('error', lang('thumbnails:in_use'));	
				}
			}
		}
		elseif (is_numeric($id))
		{
			if(!$this->thumbnails_m->check_images($id)) {
				$this->thumbnails_m->delete($id);
			} else {
				$this->session->set_flashdata('error', lang('thumbnails:in_use'));	
			}
		}
		redirect('admin/'.$this->module.'/thumbnails');
	}
	
	public function order()
	{
		if($this->input->is_ajax_request()) {
			$this->thumbnails_m->order($this->input->post('order'));
		}  else {	
			redirect('admin/'.$this->module);
		}
	}
	
}