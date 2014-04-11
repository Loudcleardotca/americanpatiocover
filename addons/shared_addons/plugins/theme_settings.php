<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Google Analytics Plugin
 *
 * Quick plugin to demonstrate how things work
 *
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Addon\Plugins
 * @copyright	Copyright (c) 2009 - 2010, PyroCMS
 */
class Plugin_Theme_settings extends Plugin
{
	
	function active_theme()
	{
		
		$default_theme = $this->settings->default_theme;
return "$default_theme";
	}
}

/* End of file example.php */