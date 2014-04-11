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
class Plugin_Google extends Plugin
{
	
	function analytics()
	{
		
		$tracking = $this->settings->ga_tracking;
		

return "<script type=\"text/javascript\">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', '$tracking']);
		  _gaq.push(['_trackPageview']);

		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>
			<script type=\"text/javascript\">
			function recordOutboundLink(link, category, action) {
				_gat._getTrackerByName()._trackEvent(category, action);
		
				
			}
			/* use regular Javascript for this */
			function getAttr(ele, attr) {
				var result = (ele.getAttribute && ele.getAttribute(attr)) || null;
				if( !result ) {
					var attrs = ele.attributes;
					var length = attrs.length;
					for(var i = 0; i < length; i++)
					if(attr[i].nodeName === attr) result = attr[i].nodeValue;
				}
				return result;
			}

			window.onload = function () {
				var links = document.getElementsByTagName('a');
				for (var x=0; x < links.length; x++) {
					links[x].onclick = function () {
						var mydomain = new RegExp(document.domain, 'i');
						href = getAttr(this, 'href');
						if(href && href.toLowerCase().indexOf('http') === 0 && !mydomain.test(href)) {
							recordOutboundLink(this, 'External Links', href);
						}
					};
				}
			};
		
			
		</script>
		
		";





	}
}

/* End of file example.php */