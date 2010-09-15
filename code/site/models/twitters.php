<?
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

class ComOauthModelTwitters extends ComOauthModelOauths
{
	public $host = "https://api.twitter.com/1/";

	function accessTokenURL()  
	{ 
		return 'https://api.twitter.com/oauth/access_token'; 
	}
	
	function authenticateURL() 
	{ 
		return 'https://twitter.com/oauth/authenticate'; 
	}
	
	function authorizeURL()    
	{ 
		return 'https://twitter.com/oauth/authorize'; 
	}
	
	function requestTokenURL() 
	{ 
		return 'https://api.twitter.com/oauth/request_token'; 
	}

	/**
	 * @see components/com_oauth/models/ComOauthModelOauths::fetchContacts()
	 * 
	 */
	function fetchContacts()
	{		
		$this->fetch($this->host.'account/verify_credentials.json');
		$credentials = json_decode($this->getLastResponse());
		
		$this->fetch($this->host.'followers/ids.json', array('screen_name' => $credentials->screen_name));
		$ids = json_decode($this->getLastResponse());

		$chunks = array_chunk($ids, 100);
				
		$twittercontacts = null;

		/* Twitter returns only the ids of the followers calling followers/ids.json. We then need to call users/lookup.json passing max 100 ids each call. */	
		foreach ($chunks as $chunk)
		{
			$this->fetch($this->host.'users/lookup.json', array('user_id' => implode(', ', $chunk)));
			$contactsdetails = json_decode($this->getLastResponse());
			
			if ($twittercontacts == null)
			{
				$twittercontacts = $contactsdetails;
			}
			else
			{
				if (is_array($contactsdetails)) 
				{
					$twittercontacts = array_merge($twittercontacts, $$contactsdetails);	
				}
			}	
		}		
		
		$contacts = array();
		
		for ($i = 0; $i < count($twittercontacts); $i++)
		{
			$contact = new KObject();
			$contact->id = $twittercontacts[$i]->screen_name;			
			$contact->title = $twittercontacts[$i]->screen_name;
			$contact->avatar = $twittercontacts[$i]->profile_image_url;
			
			$contacts[] = $contact; 
		}

		return $contacts;
	}
	
	/**
	 * 
	 * Send a DM message to specified ids
	 * @param $message string the message
	 * @param $ids array the people you want to send the message to
	 */
	function sendMessage($message, $ids)
 	{			
		if (count($ids))
		{	
			foreach ($ids as $id) 
			{
				$api_args = array("screen_name" => $id, "text" => $message);
    			$this->fetch($this->host.'direct_messages/new.json', $api_args, OAUTH_HTTP_METHOD_POST, array("User-Agent" => "pecl/oauth"));
			}			
		}
 	}

	/**
	 * 
	 * Post a tweet
	 * @param $message string no more than 140 chars
	 */
	function postMessage($message)
	{				
    	$api_args = array("status" => $message, "empty_param" => NULL);
    	$this->fetch($this->host.'statuses/update.json', $api_args, OAUTH_HTTP_METHOD_POST, array("User-Agent" => "pecl/oauth"));    	
	}

	/**
	 * 
	 * Return the login name, to store in the db
	 * @return string the login name
	 */
	function getMyLogin()
	{
		$this->fetch($this->host.'account/verify_credentials.json');
		$credentials = json_decode($this->getLastResponse());
				
		return $credentials->screen_name;
	}
	
	/**
	 * 
	 * Return the followers count
	 * @see com_oauth/code/site/models/ComOauthModelOauths::countFollowers()
	 */
	function countFollowers()
	{
		$this->fetch($this->host.'account/verify_credentials.json');
		$credentials = json_decode($this->getLastResponse());
		
		$this->fetch($this->host.'followers/ids.json', array('screen_name' => $credentials->screen_name));
		$ids = json_decode($this->getLastResponse());

		return count($ids);
	}
}