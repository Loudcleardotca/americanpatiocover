<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Images extends Module {

public $version = '3.0.0';

public function info()
{
	return array(
		'name' => array(
			'en' => 'Images',
			'nl' => 'Afbeeldingen'
		),
		'description' => array(
			'en' => 'Manage page images.',
			'nl' => 'Beheer pagina afbeeldingen.'
		),
		'frontend' => TRUE,
		'backend' => TRUE,
		'menu' => 'content',
		'sections' => array(
			'images' => array(
				'name' 	=> 'images:label',
				'uri' 	=> 'admin/images',
				'shortcuts' => array()
			),
			'thumbnails' => array(
				'name' 	=> 'thumbnails:label',
				'uri' 	=> 'admin/images/thumbnails',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'thumbnails:create',
							'uri' 	=> 'admin/images/thumbnails/create',
							'class' => 'add'
						)
					)
			)
		)
	);
}

public function install()
{
	$this->dbforge->drop_table('images_image');
	$this->dbforge->drop_table('images_thumbnails');
	$this->db->delete('settings', array('module' => 'images'));
	
	$tables = array(
		'images_image' => array(
			'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
			'page' => array('type' => 'INT', 'constraint' => 11),
			'thumbnail_id' => array('type' => 'INT', 'constraint' => 11),
			'image_file_id' => array('type' => 'INT', 'constraint' => 11),
			'thumbnail_file_id' => array('type' => 'INT', 'constraint' => 11),
			'image' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
			'thumbnail' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
		),
		'images_thumbnails' => array(
			'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
			'name' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
			'slug' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
			'layout' => array('type' => 'INT', 'constraint' => 11),
			'folder' => array('type' => 'INT', 'constraint' => 11),
			'width' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
			'height' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
			'order' => array('type' => 'INT', 'constraint' => 11, 'default' => 0),
		)
	);
	
	if ( ! $this->install_tables($tables))
	{
		return false;
	}
	
	return TRUE;
}

public function uninstall()
{
	$this->dbforge->drop_table('images_image');
	$this->dbforge->drop_table('images_thumbnails');
	$this->db->delete('settings', array('module' => 'images'));

	return TRUE;
}


public function upgrade($old_version)
{
	return FALSE;
}

public function help()
{
	return "Help is not available for this module";
}

}
/* End of file details.php */