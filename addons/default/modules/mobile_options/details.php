<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Mobile_options extends Module 
{
	public $version = '1.0';
	
	public function info()
    {
        return array(
            'name' => array(
                'en' => 'Mobile Theme Plugin'
            ),
            'description' => array(
                'en' => 'Integrate Mobile Theme options.'
            ),
            'frontend' => true,
            'backend' => true
        );
    }
	
	
	
	public function install()
    {
        $this->dbforge->drop_table('mobile_options');
        $this->db->delete('settings', array('module' => 'mobile_options'));

        $mobile_options = array(
            'id' => array(
            'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'slug' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
        );
		
		$activity = array(
            'slug' => '72x72_image',
            'title' => '72x72 Image',
            'description' => '72x72 Images',
            '`default`' => '',
            '`value`' => '',
            'type' => 'text',
			'`options`' => '',
            'is_required' => 0,
            'is_gui' => 1,
            'module' => 'mobile_options',
			'order' => 1
        );

        $audience = array(
            'slug' => '96x96_image',
            'title' => '96x96_image',
            'description' => '96x96 Images',
            '`default`' => '',
            '`value`' => '',
            'type' => 'text',
			'`options`' => '',
            'is_required' => 0,
            'is_gui' => 1,
            'module' => 'mobile_options',
			'order' => 2
        );
		
		$traffic = array(
            'slug' => '114x114_image',
            'title' => '114x114 Image',
            'description' => '114x114 Images',
            '`default`' => '',
            '`value`' => '',
            'type' => 'text',
			'`options`' => '',
            'is_required' => 0,
            'is_gui' => 1,
            'module' => 'mobile_options',
			'order' => 3
        );
		
		$content = array(
           'slug' => '144x144_image',
            'title' => '144x144 Image',
            'description' => '144x144 Images',
            '`default`' => '',
            '`value`' => '',
            'type' => 'text',
			'`options`' => '',
            'is_required' => 0,
            'is_gui' => 1,
            'module' => 'mobile_options',
			'order' => 4
        );
		
		$advertising = array(
            'slug' => 'site_logo',
            'title' => 'Site Logo',
            'description' => 'Site Logo',
            '`default`' => '',
            '`value`' => '',
            'type' => 'text',
			'`options`' => '',
            'is_required' => 0,
            'is_gui' => 1,
            'module' => 'mobile_options',
			'order' => 5
        );
		
		$conversion = array(
            'slug' => 'favicon',
            'title' => 'Favicon',
            'description' => '32x32',
            '`default`' => '',
            '`value`' => '',
            'type' => 'text',
			'`options`' => '',
            'is_required' => 0,
            'is_gui' => 1,
            'module' => 'mobile_options',
			'order' => 6
        );
		


        $this->dbforge->add_field($mobile_options);
        $this->dbforge->add_key('id', TRUE);

        // Let's try running our DB Forge Table and inserting some settings
        if ( ! $this->dbforge->create_table('mobile_options') OR ! $this->db->insert('settings', $activity) OR ! $this->db->insert('settings', $audience) OR ! $this->db->insert('settings', $traffic) OR ! $this->db->insert('settings', $content) OR ! $this->db->insert('settings', $advertising) OR ! $this->db->insert('settings', $conversion))
        {
            return FALSE;
        }

        // We made it!
        return TRUE;
    }
	
	
	public function uninstall()
	{
		
		return true;
	}
	
	public function upgrade($old_version)
    {
        return true;
    }
}
/* End of file details.php */