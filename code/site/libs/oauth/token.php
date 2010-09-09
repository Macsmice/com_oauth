<?
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */
class ComOauthLibsOauthToken extends KObject 
{
	// access tokens and request tokens
	public $key;
	public $secret;
	
	/**
	 * key = the token
	 * secret = the token secret
	 */
	function __construct($key, $secret) 
	{
		$this->key = $key;
		$this->secret = $secret;
	}
	
	/**
	 * generates the basic string serialization of a token that a server
	 * would respond to request_token and access_token calls with
	 */
	function to_string() 
	{
		return "oauth_token=" .
			OAuthUtil::urlencode_rfc3986($this->key) .
			"&oauth_token_secret=" .
			OAuthUtil::urlencode_rfc3986($this->secret);
	}
	
	function __toString() 
	{
		return $this->to_string();
	}
}