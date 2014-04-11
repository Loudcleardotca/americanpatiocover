<?php defined('BASEPATH') OR exit('No direct script access allowed');
//usage {{ filesize:results }}


class Plugin_Filesize extends Plugin
{
	
	
	
	
	
	function results()
	{
		

$currenturl = current_url();
//$page_memory = memory_get_usage();

//$filename ='uploads/default/files/logos_site_text.png';
//$filesize = filesize($filename);

//$filesize =  memory_get_usage();
//$mem_usage = memory_get_usage(true); 

//$kb = round($mem_usage / 1024, 2);
//$mb = round($mem_usage / 1048576, 2);
 // 
		
return "<!-- 
Page rendered: {elapsed_time} Seconds
Total Memeory Used: {memory_usage}
-->
";





	}
}

/* End of file example.php */