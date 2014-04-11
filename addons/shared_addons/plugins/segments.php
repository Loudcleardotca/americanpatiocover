<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Plugin_Segments extends Plugin {
	/**
	 * Returns true of segment exists
	 *
	 * usage:
	 * {{ segments:exists names="incidents,majors" }}
	 */
	function exists() 
	{
		$modules = explode(',',$this->attribute('names'));
		$segs = $this->uri->segment_array();
		
		if ( ! $segs){
			return true;
		}
		
		$result = array_intersect($modules,$segs);
		
		return ! empty($result);
	}
}