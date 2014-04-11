<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Gists Plugin
 *
 * Getting GitHub gists
 *
 * You can set pyrocache expire as a PyroCMS variable named
 *		gist_pyrocache
 * OR in the private parameter $gist_pyrocache
 *
 * You can set user-agent as a PyroCMS variable named
 *		gist_plugin_user_agent
 * OR in the private parameter $gist_plugin_user_agent
 *
 * For this plugin to work you need one of:
 *		x allow_url_fopen=true
 *		x php5-curl
 *
 *           DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE 
 *                 Version 2, December 2004 
 *
 * Copyright (C) 2004 Sam Hocevar <sam@hocevar.net> 
 *
 * Everyone is permitted to copy and distribute verbatim or modified 
 * copies of this license document, and changing it is allowed as long 
 * as the name is changed. 
 *
 *            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE 
 *  TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION 
 *
 * 0. You just DO WHAT THE FUCK YOU WANT TO. 
 *
 * @package	PyroCMS
 * @subpackage	Plugins
 * @author	Mauro Pizzamiglio - Awesomelabs
 * @copyright	Copyright (c) 2012, Mauro Pizzamiglio - Awesomelabs
 *
 */
class Plugin_Gists extends Plugin
{
	private $schema = 'https';
	private $domain = 'api.github.com';
	private $gist_plugin_user_agent = 'Awesomelabs - Gists PyroCMS Plugin';
	private $gist_pyrocache = 300;

	/**
	 * List a github user Gists
	 *
	 * Usage:
	 * {{ gists:list_gists user="philsturgeon" }}
	 * 		take a look at github gists api
	 * 		about the available content
	 * {{ /gists:list_gists }}
	 *
	 * @param	array
	 * @return	array
	 */
	function list_gists()
	{
		$user = $this->attribute('user');
	
		return $this->do_call('GET', "users/$user/gists");
	}
	
	/**
	 * Get a single Gist
	 *
	 * Usage:
	 * {{ gists:gist id="" }}
	 * 		take a look at github gists api
	 * 		abaout the available content
	 * {{ /gists:gist }}
	 *
	 * @param	array
	 * @return	array
	 */
	function gist()
	{
		$id = $this->attribute('id');
	
		return array($this->do_call('GET', "gists/$id"));
	}
	
	/**
	 *	Perform the request
	 *
	 *	Preferred method is file_get_contents, otherwise will
	 *  try to look for curl extension.
	 *
	 *	The cache name is based on the uri trunk, like
	 *	> users.philsturgeon.gists
	 *	> gists.7788
	 * and stored in a gists-plugin directory
	 *
	 *  If you want/need to set custom user-agent check the class descrpition
	 *	If you want to set cache expire time check the class description
	 **/
	private function do_call($method, $uri)
	{
		$options = $this->request_options($method, $uri);
		$url = $this->schema . '://' . $this->domain . '/' . $uri;
		$results = null;
		$cachename = 'gists-plugin/' . str_replace('/', '.', $uri);
		if ( ! ($results = $this->pyrocache->get($cachename)))
		{
			if(ini_get('allow_url_fopen'))
			{
				$context = stream_context_create($options);
				$results = json_decode(file_get_contents($url, false, $context));
			}
			elseif(function_exists('curl_init'))
			{
				$ch = curl_init();
				curl_setopt_array($ch, $options);
				$results = json_decode(curl_exec($ch));
			}
			else
			{
				// throw error
			}
			
			$gist_pyrocache = isset($this->variables->gist_pyrocache) ? $this->variables->gist_pyrocache : $this->gist_pyrocache;
			$this->pyrocache->write($results, $cachename, $gist_pyrocache);
		}


		return $results;
	}

	/**
	 *	Pack request data as a stream context options or
	 *	a cURL options array.
	 *	@param $method 		HTTP request method
	 *	@param $uri_trunk   github uri trunk
	 **/
	private function request_options($method, $uri_trunk)
	{
		$options = null;
		$user_agent = isset($this->variables->gist_plugin_user_agent) ? $this->variables->gist_plugin_user_agent : $this->gist_plugin_user_agent;
		if(ini_get('allow_url_fopen'))
		{
			// request via file_get_contents
			$options = array(
				$this->schema => array(
					'method' => $method,
					'user-agent' => $user_agent,
					'protocol_version' => 1.1
				)
			);
		}
		else
		{
			// cURL fallback
			/**
			 * actually this plugin need to perform only HTTP GET request
			 * which is the cURL default
			 **/
			$options = array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_USERAGENT => $user_agent,
				CURLOPT_URL => $this->schema . '://' . $this->domain . '/' . $uri_trunk,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
			);
		}

		return $options;
	}
}

/* End of file gists.php */
