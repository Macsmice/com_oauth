<?
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

class ComOauthModelFacebooks extends ComOauthModelOauths
{
	public $host = "https://graph.facebook.com/"; /* Set up the API root URL. */

	function requestTokenURL() 
	{ 
		//first step of the oauth dance: retrieve the request token
		return 'https://graph.facebook.com/oauth/request_token'; 
	}
	
	function authorizeURL()    
	{ 
		//second step in oauth dance: user follows a link to this page to authorize your application
		return 'https://graph.facebook.com/oauth/authorize'; 
	}
	
	function authenticateURL() 
	{ 
		//same as /oauth/authorize, but users are automatically redirected if they've already authorized your app
		return 'https://graph.facebook.com/oauth/authenticate'; 
	}
	
	function accessTokenURL()  
	{ 
		//final step in oauth dance: exchange a request token for an access token
		return 'https://graph.facebook.com/oauth/access_token'; 
	}
	
	function fetchContacts()
 	{
 		$access_token = $this->getToken();

 		$api_args = array(
    		"access_token" => $access_token['oauth_token'] 
    	);
    	$this->fetch($this->host.'me/friends?fields=id,name,picture', $api_args);
 		
		$friends = json_decode($this->getLastResponse());

		$contacts = array();

		foreach ( $friends->data as $entry)
		{
			$contact = new KObject();
			$contact->id = $entry->id;			
			$contact->title = $entry->name;
			$contact->avatar = $entry->picture;
			$contacts[] = $contact;
		}

		return $contacts;
 	}
 	
 	/**
 	 * 
 	 * Sends a message to my FB Wall
 	 * @param $message string the message to post
 	 */
	function postMessage($message)
 	{ 		
 		$access_token = $this->getToken();

 		$api_args = array(
    		"access_token" => $access_token['oauth_token'],
 			"message" => $message
    	);
    	$this->fetch($this->host.'me/feed', $api_args, OAUTH_HTTP_METHOD_POST, array("User-Agent" => "pecl/oauth"));	
 	}

 	/**
 	 * 
 	 * Sends a message to a user's FB Wall
 	 * 
 	 * @todo Not working currently, still gets Exception caught! Response: {"error":{"type":"OAuthException","message":"(#210) User not visible"}}
 	 * 
 	 * @param $message string the message to post
 	 */
	function postMessageToFriend($message, $friend_id)
 	{ 		
 		$access_token = $this->getToken();

 		$api_args = array(
    		"access_token" => $access_token['oauth_token'],
 			"message" => $message
    	);
    	$this->fetch($this->host.$friend_id.'/feed', $api_args, OAUTH_HTTP_METHOD_POST, array("User-Agent" => "pecl/oauth"));	
 	}
 	
 	function getMyId()
 	{
 		$access_token = $this->getToken();

 		$api_args = array(
    		"access_token" => $access_token['oauth_token'] 
    	);
    	$this->fetch($this->host.'me', $api_args);
 		
		return json_decode($this->getLastResponse())->id;
 	}
 	
 	function getMyName()
 	{
 		$access_token = $this->getToken();

 		$api_args = array(
    		"access_token" => $access_token['oauth_token'] 
    	);
    	$this->fetch($this->host.'me', $api_args);
 		
		return json_decode($this->getLastResponse())->name;
 	}
 	
	/**
 	 * 
 	 * Return false, can't send messages directly using Facebook API
 	 */
 	function canSendMessage()
 	{
 		return false;
 	}
}