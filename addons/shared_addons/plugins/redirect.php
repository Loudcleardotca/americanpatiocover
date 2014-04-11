<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* Redirect Plugin
*
* Redirect to a passed URL
*
* @author		Amity Web Solutions Ltd
* @package		PyroCMS\Addon\Plugins
* @copyright	Copyright (c) 1012, Amity Web Solutions Ltd
* 
*/

class Plugin_Redirect extends Plugin
{
	/**
	* Usage:
	* {{ redirect:url url="mypage"}}
	*/
	
	function url()
	{
		$url = $this->attribute('url');
		$this->load->helper('url');
		redirect($url);
	}

}

/* End of file redirect.php */
