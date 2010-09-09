<?
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */
class ComOauthLibsOauthDatastore extends KObject
{
	function lookup_consumer($consumer_key) 
	{
		// implement me
	}
	
	function lookup_token($consumer, $token_type, $token) 
	{
		// implement me
	}
	
	function lookup_nonce($consumer, $token, $nonce, $timestamp) 
	{
		// implement me
	}
	
	function new_request_token($consumer, $callback = null) 
	{
		// return a new token attached to this consumer
	}
	
	function new_access_token($token, $consumer, $verifier = null) 
	{
		// return a new access token attached to this consumer
		// for the user associated with this token if the request token
		// is authorized
		// should also invalidate the request token
	}
	
}