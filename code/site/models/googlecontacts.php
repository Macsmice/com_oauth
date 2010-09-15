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
class ComOauthModelGooglecontacts extends ComOauthModelOauths
{	
	public $host = "https://www.google.com/m8/";
	
	function accessTokenURL()  
 	{ 
 		return 'https://www.google.com/accounts/OAuthGetAccessToken'; 
 	}
	
	function authorizeURL()    
	{ 
		return 'https://www.google.com/accounts/OAuthAuthorizeToken'; 
	}
	
 	function requestTokenURL() 
 	{ 
 		return 'https://www.google.com/accounts/OAuthGetRequestToken'; 
 	}
 	
 	/**
 	 * @see components/com_oauth/models/ComOauthModelOauths::fetchContacts()
 	 */
 	function fetchContacts()
 	{
 		$this->fetch($this->host.'feeds/contacts/default/full?max-results=100&alt=json');
		$json_output = json_decode($this->getLastResponse());
 	
		$contacts = array();
		foreach ( $json_output->feed->entry as $entry)
		{
			$contact = new KObject();
			if ($entry->title->{'$t'})
			{
				$contact->title = $entry->title->{'$t'};
			}
			else 
			{
				$contact->title = $entry->{'gd$email'}[0]->address;
			}
			$contact->email = $entry->{'gd$email'}[0]->address;
			$contact->id = $contact->email;
			
			$contacts[] = $contact;
		}
		
		return $contacts;
 	}
 	
 	/**
 	 * 
 	 * Send a message to the specified contacts (uses standard J! email)
 	 * @param $message the message
 	 * @param $ids array of contact ids
 	 */
 	function sendMessage($message, $ids, $subject)
 	{			
		if (count($ids))
		{	
			foreach ($ids as $email) 
			{
				$from = KRequest::get('post.from', 'string');
				$config = JFactory::getConfig();
				$mailfrom = $config->getValue('mailfrom');
				$fromname = $config->getValue('fromname');
						
				JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message, true);
			}
		}
		$message = 'Mails sent';
 	}
}