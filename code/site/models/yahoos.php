<?
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */
 
class ComOauthModelYahoos extends ComOauthModelOauths
{
	public $host = "https://api.login.yahoo.com";
	
	function accessTokenURL()  
 	{ 
 		return 'https://api.login.yahoo.com/oauth/v2/get_token'; 
 	}
	
	function authorizeURL()    
	{ 
		return 'https://api.login.yahoo.com/oauth/v2/request_auth'; 
	}
	
 	function requestTokenURL() 
 	{ 
 		return 'https://api.login.yahoo.com/oauth/v2/get_request_token'; 
 	}
}