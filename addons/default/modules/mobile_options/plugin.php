<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Mobile Iphone options Plugin
 *
 * Quick plugin to demonstrate how things work
 *
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Addon\Plugins
 * @copyright	Copyright (c) 2009 - 2010, PyroCMS
 */
class Plugin_Mobile_options extends Plugin
{
	

	
	function meta_data()
	{	
	$baseurl = base_url();
	
$image_72 = "{{ settings:72x72_image }}";
$image_96 = "{{ settings:96x96_image }}";
$image_114 = "{{ settings:114x114_image }}";
$image_144 = "{{ settings:144x144_image }}";
$favicon = "{{ settings:favicon }}";
$url = base_url();

	
	return "
	
	
 <!-- Load up some favicons -->
<link href=\"$url$favicon\" rel=\"shortcut icon\" type=\"image/x-icon\" />


<link href=\"{{ url:site }}$image_72\" rel=\"shortcut icon\" type=\"image/x-icon\" />


<!-- add to homepage bubble on iphone, ipad  -->     

<meta content=\"width=device-width, minimum-scale=1.0, maximum-scale=1.0\" name=\"viewport\">
<meta name=\"apple-mobile-web-app-capable\" content=\"yes\" />
<meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black\" />  
     
<link rel=\"apple-touch-icon-precomposed\" title=\"{{ settings:site_name }}\" href=\"{{ url:site }}$image_96\">
<link rel=\"apple-touch-icon-precomposed\" title=\"{{ settings:site_name }}\" sizes=\"72x72\" href=\"{{ url:site }}$image_72\">
<link rel=\"apple-touch-icon-precomposed\" title=\"{{ settings:site_name }}\" sizes=\"114x114\" href=\"{{ url:site }}$image_114\"> 
<link rel=\"apple-touch-icon-precomposed\" title=\"{{ settings:site_name }}\" sizes=\"144x144\" href=\"{{ url:site }}$image_144\">
    
<link rel=\"stylesheet\" type=\"text/css\" href=\"$url/addons/default/modules/mobile_options/css/add2home.css\" />

<!-- ie10 -->
<meta name=\"msapplication-TileImage\" content=\"/$image_144\">

<script type=\"application/javascript\" src=\"$url/addons/default/modules/mobile_options/js/add2home.js\"></script>

<script type=\"text/javascript\">
	var addToHomeConfig = {
	animationIn: \"bubble\",
	animationOut: \"drop\",
	lifespan:10000,
	returningVisitor: false,	
	touchIcon:true,
	expire:720
	
	};
	</script>
<!-- /add to homepage bubble on iphone, ipad  -->	
";




	}
}

/* End of file  */