<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Plugin_Sitemapper extends Plugin
{
    public function __construct()
    {
        Sitemapper::enable();
    }

    function show()
    {
        Sitemapper::set_attributes(
            array(
                'heading_m'    => (int)$this->attribute('heading_m', 3),
                'heading_c'    => (int)$this->attribute('heading_c', 4),
                'grab_modules' => array_filter(explode('|', $this->attribute('modules', null))),
                'excludes'     => explode('|', $this->attribute('excludes', null)),
            )
        );
        return Sitemapper::show();
    }


}

/* End of file plugin.php */