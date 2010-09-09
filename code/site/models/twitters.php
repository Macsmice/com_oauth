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
	public $host = "https://api.twitter.com/1/"; /* Set up the API root URL. */

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
		$credentials = json_decode($this->get($this->host.'account/verify_credentials.json')); 
		
		$ids = $this->get($this->host.'followers/ids.json', array('screen_name' => $credentials->screen_name));
		//remove unneeded chars from response
		$ids = str_replace('[', '', $ids);
		$ids = str_replace(']', '', $ids);
		$ids = explode(',',$ids);

		//$ids = array_slice($ids, 0, 10);
		$chunks = array_chunk($ids, 100);
				
		$twittercontacts = null;

		/* Twitter returns only the ids of the followers calling followers/ids.json. We then need to call users/lookup.json passing max 100 ids each call. */	
		foreach ($chunks as $chunk)
		{
			$contactsdetails = $this->get($this->host.'users/lookup.json', array('user_id' => implode(', ', $chunk)));

			if ($twittercontacts == null)
			{
				$twittercontacts = json_decode($contactsdetails);
			}
			else
			{
				$json = json_decode($contactsdetails);

				if (is_array($json)) 
				{
					$twittercontacts = array_merge($twittercontacts, $json);	
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
				$res = $this->post($this->host.'direct_messages/new.json', array('screen_name' => $id, 'text' => $message));
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
		$this->post($this->host.'statuses/update.json', array('status' => $message));
	}

	/**
	 * 
	 * Return the login name, to store in the db
	 * @return string the login name
	 */
	function getMyLogin()
	{
		$credentials = json_decode($this->get($this->host.'account/verify_credentials.json')); 
		
		return $credentials->screen_name;
	}
	
	function countFollowers()
	{
		$credentials = json_decode($this->get($this->host.'account/verify_credentials.json')); 
		
		$ids = $this->get($this->host.'followers/ids.json', array('screen_name' => $credentials->screen_name));
		//remove unneeded chars from response
		$ids = str_replace('[', '', $ids);
		$ids = str_replace(']', '', $ids);
		$ids = explode(',',$ids);
		return count($ids);
	}
}