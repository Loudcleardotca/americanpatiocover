<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 *
 * @author 		Loud+Clear Dev Team
 * @website		http://loudclear.ca
 *
 */
if (!function_exists('update_ordering_count'))
{

	/**
	 * Receive original index of element and new index of element from post data and update ordering count of affected rows
	 *
	 * @param string $stream_slug The current stream slug
	 * @return bool
	 */
	function update_ordering_count( $stream_slug )
	{
		$ci = & get_instance();
		
		//add 1 because ordering_count isn't zero based
		$original_ordering_count = $ci->input->post('original_index') + 1;
		$new_ordering_count = $ci->input->post('new_index') + 1;
		
		//store target row
		$target_row = $ci->db->get_where($stream_slug, array('ordering_count' => $original_ordering_count) )->row();
		
		//determine direction of change
		$move_up = ($original_ordering_count > $new_ordering_count) ? true : false;
		
		if($move_up)
		{
			for($i=$original_ordering_count - 1; $i >= $new_ordering_count; $i--) 
			{
				
				//update rows to new values
				$ci->db->where('ordering_count', $i);
				$ci->db->update($stream_slug, array('ordering_count' => $i + 1)); 
				
			}
		}
		else
		{
			for($i=$original_ordering_count + 1; $i <= $new_ordering_count; $i++) 
			{
				//update rows to new values
				$ci->db->where('ordering_count', $i);
				$ci->db->update($stream_slug, array('ordering_count' => $i - 1)); 
			}
		}
		
		//update target row
		$ci->db->where('id', $target_row->id);
		$ci->db->update($stream_slug, array('ordering_count' => $new_ordering_count)); 
			
		return true;	
		
		
	}

}