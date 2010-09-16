<?
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

class ComOauthModelFoursquares extends ComOauthModelOauths
{
	public $host = "http://api.foursquare.com/v1/"; /* Set up the API root URL. */

	function requestTokenURL() 
	{ 
		//first step of the oauth dance: retrieve the request token
		return 'http://foursquare.com/oauth/request_token'; 
	}
	
	function authorizeURL()    
	{ 
		//second step in oauth dance: user follows a link to this page to authorize your application
		return 'http://foursquare.com/oauth/authorize'; 
	}
	
	function authenticateURL() 
	{ 
		//same as /oauth/authorize, but users are automatically redirected if they've already authorized your app
		return 'http://foursquare.com/oauth/authenticate'; 
	}
	
	function accessTokenURL()  
	{ 
		//final step in oauth dance: exchange a request token for an access token
		return 'http://foursquare.com/oauth/access_token'; 
	}
	
	/**
	 * 
	 * Post a checkin, e.g. $model->postCheckin('4899969', null, 'Waiting for the bus!');

	 * @param $message string no more than 140 chars
	 */
	function postCheckin($vid, $venue, $shout, $isprivate, $twitter, $facebook, $geolat, $geolong)
	{				
    	$api_args = array(
    		"vid" => $vid, 
    		"venue" => $venue, 
    		"shout" => $shout, 
    		"istwitter" => $isprivate, 
    		"twitter" => $twitter, 
    		"facebook" => $facebook,
    		"geolat" => $geolat,
    		"geolong" => $geolong 
    	);
    	$this->fetch($this->host.'checkin.json', $api_args, OAUTH_HTTP_METHOD_POST, array("User-Agent" => "pecl/oauth"));
	}

	/**
	 * 
	 * Return the id of the foursquare account, to store in the db
	 * @return string the login name
	 */
	function getMyId()
	{
		$this->fetch($this->host.'user.json');
		$credentials = json_decode($this->getLastResponse());
var_dump($credentials);exit();
		return $credentials->user->id;
	}
	
	/**
	 * 
	 * Returns a list of recent checkins from friends
	 */
	function getFriendsCheckins()
	{
		$this->fetch($this->host.'checkins.json');
		return json_decode($this->getLastResponse());
	}
 	
	/**
 	 * 
 	 * Return false, can't send messages directly using API
 	 */
 	function canSendMessage()
 	{
 		return false;
 	}
}