<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Module Field Type
 *
 * @author		Jeff Mulder - Loud+Clear Dev Team
 * @link		http://loudclear.ca
 */
class Field_module
{
	public $field_type_slug				= 'module';
	
	public $db_col_type					= 'varchar';
	
	public $extra_validation			= '';

	public $version						= '1.0';
	
	public $author						= array('name'=>'Loud+Clear Dev Team', 'url'=>'http://loudclear.ca');
	
	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function form_output($data)
	{
		$options['name'] 	= $data['form_slug'];
		$options['id']		= $data['form_slug'];
		$options['value']	= $data['value'];
		
		//get all frontend modules
		$all_modules = $this->CI->module_m->get_all(array('is_frontend'=>true));

		//only allow modules that user has permissions for
		foreach($all_modules as $module)
		{
			if(in_array($module['slug'], $this->CI->permissions) OR $this->CI->current_user->group == 'admin') $modules[] = $module;
		}
		
		//prep array for select
		$modules = array_for_select($modules, 'slug', 'name');
		
		//prepend blank option
		$modules = array_merge(array('' => lang('global:select-pick')), $modules);
		
		//return dropdown
		return form_dropdown($options['name'], $modules, $options['value'], 'id="'.$options['id'].'"');
	}

	// --------------------------------------------------------------------------

	/**
	 * Process before outputting for the plugin
	 *
	 * This creates an array of data to be merged with the
	 * tag array so relationship data can be called with
	 * a {field.column} syntax
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	array
	 */
	public function pre_output_plugin($input, $params)
	{
		
	}

}