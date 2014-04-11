<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Sitemapper Module
 *
 * @author Ramon Leenders (www.ramon-leenders.nl)
 */
class Module_Sitemapper extends Module
{
    public $version = '1.2';

    public function info()
    {
        return array(
            'name'        => array(
                'en' => 'Sitemapper',
                'nl' => 'Sitemapper',
            ),
            'description' => array(
                'en' => 'A sitemap to show for your front-end users',
                'nl' => 'Een sitemap voor te tonen aan uw website gebruikers',
            ),
            'frontend'    => FALSE,
            'backend'     => TRUE,
            'menu'        => 'utilities',
            'shortcuts'   => array(
                array(
                    'name'  => 'sitemap_exclude_create_title',
                    'uri'   => 'admin/sitemapper/create',
                    'class' => 'add'
                )
            )
        );
    }

    public function install()
    {
        $this->dbforge->drop_table('sitemapper');

        $sitemap = "
			CREATE TABLE " . $this->db->dbprefix('sitemapper') . " (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `link_type` varchar(255) NOT NULL,
              `module_id` int(11) NOT NULL DEFAULT '0',
              `page_id` int(11) NOT NULL DEFAULT '0',
              `created_on` int(11) NOT NULL DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
        if ($this->db->query($sitemap)) {
            return TRUE;
        }
    }

    public function uninstall()
    {
        $this->dbforge->drop_table('sitemapper');
        return TRUE;
    }

    public function upgrade($old_version)
    {
        // Your Upgrade Logic
        return TRUE;
    }

    public function help()
    {
        // Return a string containing help info
        // You could include a file and return it here.
        return "<h4>Overview</h4>";
    }
}
/* End of file details.php */