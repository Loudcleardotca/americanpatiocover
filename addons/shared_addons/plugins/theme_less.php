<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Theme Plugin
 *
 * Load partials and access data
 *
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Plugins
 */
class Plugin_Theme_less extends Plugin
{

	

	/**
	 * Theme CSS
	 *
	 * Insert a CSS tag with location based for url or path from the theme or module
	 *
	 * Usage:
	 *  {{ theme_less:less file="styles.less" }}
	 *
	 * @return string The link HTML tag for the stylesheets.
	 */
	public function less()
	{
		
		$domain = $_SERVER['SERVER_NAME'];
		$default_theme = $this->settings->default_theme;
		$file = $this->attribute('file');

		return "<link href=\"http://$domain/addons/shared_addons/themes/$default_theme/css/$file\" type=\"text/css\" rel=\"stylesheet/less\" />";
	}
	

	

}