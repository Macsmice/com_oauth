<?
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */
 
/**
 * GMail uses OAuth 1.0
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
	
 	function requestTokenURL() 
 	{ 
 		return 'https://api.linkedin.com/uas/oauth/requestToken'; 
 	}
 	
 	function fetchContacts()
 	{
 		$xmlstr = $this->get('http://api.linkedin.com/v1/people/~/connections');
		$xml = new SimpleXMLElement($xmlstr); 	

		$contacts = array();
		
		foreach ($xml->person as $entry)
		{
			$contact = new KObject();
			$firstname = 'first-name';
			$lastname = 'last-name';
			
			$contact->title = $entry->{$firstname} . ' ' . $entry->{$lastname};
			$contact->id = $entry->id;
			
			$pictureurl = 'picture-url';
			$contact->avatar = $entry->{$pictureurl}; 
			
			$contacts[] = $contact;
		}
		
		return $contacts;
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
			foreach ($ids as $email) 
			{
								
				$body = "<?xml version='1.0' encoding='UTF-8'?>
					<mailbox-item>
					  <recipients>
					    <recipient>
					      <person path='/people/~'/>
					    </recipient>
					  </recipients>
					  <subject>Congratulations on your new position.</subject>
					  <body>You're certainly the best person for the job!</body>
					</mailbox-item>";
 				$xmlstr = $this->post('http://api.linkedin.com/v1/people/~/mailbox', array('body' => $body));
 				
				var_dump($xmlstr); exit();
				
				
			}
		}
		$message = 'Mails sent';

		
 	}
 	
 	
}