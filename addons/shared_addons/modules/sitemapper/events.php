<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * A hack, to load the sitemapper library, so it should be available at any page but don't do anything really bad at all
 */
class Events_Sitemapper {

    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->library('sitemapper/sitemapper');
    }
}
/* End of file events.php */