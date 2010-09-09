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
 		$json = $this->get('https://www.google.com/m8/feeds/contacts/default/full?max-results=100&alt=json');
		$json_output = json_decode($json);
	
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
				$subject = 'Ohanah invite';
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