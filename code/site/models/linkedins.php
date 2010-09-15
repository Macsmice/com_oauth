<?
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

class ComOauthModelLinkedins extends ComOauthModelOauths
{
	public $host = "https://api.linkedin.com/";
	
	function accessTokenURL()  
 	{ 
 		return 'https://api.linkedin.com/uas/oauth/accessToken'; 
 	}
	
	function authorizeURL()    
	{ 
		return 'https://www.linkedin.com/uas/oauth/authorize'; 
	}

	function authenticateURL() 
	{ 
		return 'https://www.linkedin.com/uas/oauth/authenticate'; 
	}
	
 	function requestTokenURL() 
 	{ 
 		return 'https://api.linkedin.com/uas/oauth/requestToken'; 
 	}

 	function fetchContacts()
 	{
 		$this->fetch($this->host.'v1/people/~/connections');
		$xmlstr = $this->getLastResponse();
 			
		$xml = new SimpleXMLElement($xmlstr); 	

		$contacts = array();
		
		foreach ($xml->person as $entry)
		{
			$contact = new KObject();
			$firstname = 'first-name';
			$lastname = 'last-name';
			
			$contact->title = (string)$entry->{$firstname} . ' ' . (string)$entry->{$lastname};
			$contact->id = (string)$entry->id;
			
			$pictureurl = 'picture-url';
			$contact->avatar = (string)$entry->{$pictureurl}; 
			
			$contacts[] = $contact;
		}
		
		return $contacts;
 	}
 	
 	/**
 	 * 
 	 * Updates the Linkedin status with $message
 	 * @param $message
 	 */
 	function updateStatus($message)
 	{
		$xml = '<?xml version="1.0" encoding="UTF-8"?><current-status>'.$message.'</current-status>';
 		$this->fetch($this->host.'v1/people/~/current-status', $xml, OAUTH_HTTP_METHOD_PUT, array("User-Agent" => "pecl/oauth"));
 	}
 	
 	/**
 	 * 
 	 * Send a message to the specified contacts
 	 * @param $message the message
 	 * @param $ids array of contact ids
 	 */
 	function sendMessage($message, $ids)
 	{			
		if (count($ids))
		{	
			foreach ($ids as $id) 
			{
				
				$xml = '<?xml version="1.0" encoding="UTF-8"?>
						<mailbox-item>
						  <recipients>
						    <recipient>
						      <person path="/people/'.$id.'"/>
						    </recipient>				
    					  </recipients>
						  <subject>Congratulations on your new position.</subject>
						  <body>You\'re certainly the best person for the job!</body>
						</mailbox-item>';
				
    			$this->fetch($this->host.'v1/people/~/mailbox', $xml, OAUTH_HTTP_METHOD_POST, array("User-Agent" => "pecl/oauth", 'Content-Type' => 'application/xml'));
			}
		}
		$message = 'Mails sent';
 	}

	/**
	 * 
	 * Return the user id, to store in the db
	 * @return string the login name
	 */
	function getMyId()
	{
		$this->fetch($this->host.'v1/people/~:(id,first-name,last-name,industry)');
		$xmlstr = $this->getLastResponse();
		$xml = new SimpleXMLElement($xmlstr); 	
    
    	return (string)$xml->id;
	}
	
	/**
	 * 
	 * Return the user id, to store in the db
	 * @return string the login name
	 */
	function getMyName()
	{
		$this->fetch($this->host.'v1/people/~:(first-name,last-name)');
		$xmlstr = $this->getLastResponse();
		$xml = new SimpleXMLElement($xmlstr); 	
        
    	return (string)$xml->{"first-name"}.' '.(string)$xml->{"last-name"};
	}
 	
	/**
 	 * 
 	 * Return true, can send messages directly using API
 	 */
 	function canSendMessage()
 	{
 		return true;
 	}
}