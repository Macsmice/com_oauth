<?
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

class ComOauthModelFacebooks extends ComOauthModelOauth2s
{
	function fetchContacts()
 	{
 		$friends = $this->api('/me/friends?fields=id,name,picture', 'GET');

 		$contacts = array();

		foreach ( $friends['data'] as $entry)
		{
			$contact = new KObject();
			$contact->id = $entry['id'];			
			$contact->title = $entry['name'];
			$contact->avatar = $entry['picture'];
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
				$res = $this->post($this->host.'direct_messages/new.json', array('user_id' => $id, 'text' => $message));
			}			
		}
 	} 	

 	/**
 	 * 
 	 * Sennds a message to my FB Wall
 	 * @param $message string the message to post
 	 */
	function postMessage($message)
 	{
 		$result =  $this->api('/me/feed', 'POST', array('message' => $message)); 
 		var_dump($result);
 		exit();
 	}

	/**
	 * Maps aliases to Facebook domains.
	 */
	public $domainMap = array(
    	'api'      => 'https://api.facebook.com/',
    	'api_read' => 'https://api-read.facebook.com/',
    	'graph'    => 'https://graph.facebook.com/',
    	'www'      => 'https://www.facebook.com/',
	);
	
	/**
	 * (non-PHPdoc)
	 * @see components/com_oauth/models/ComOauthModelOauth2s::getLoginUrl()
	 */
	public function getLoginUrl($params=array()) 
	{
		$currentUrl = $this->getCurrentUrl();

		return $this->getUrl('www', 'login.php', array_merge(array(
	        'api_key'         => $this->getAppId(),
	        'cancel_url'      => $currentUrl,
	        'display'         => 'page',
	        'fbconnect'       => 1,
	        'next'            => 'http://'.$_SERVER['HTTP_HOST'].KFactory::get('site::com.oauth.view.facebook')->createRoute('option=com_oauth&view=facebook&layout=callback'),
	        'return_session'  => 1,
	        'session_version' => 3,
	        'v'               => '1.0',
			), $params)
		);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see components/com_oauth/models/ComOauthModelOauth2s::getLogoutUrl()
	 */
	public function getLogoutUrl($params=array()) 
	{
		return $this->getUrl('www', 'logout.php', array_merge(array(
	        'next'         => $this->getCurrentUrl(),
	        'access_token' => $this->getAccessToken(),
			), $params)
		);
	}
	
	/**
	 * Get a login status URL to fetch the status from facebook.
	 *
	 * The parameters:
	 * - ok_session: the URL to go to if a session is found
	 * - no_session: the URL to go to if the user is not connected
	 * - no_user: the URL to go to if the user is not signed into facebook
	 *
	 * @param Array $params provide custom parameters
	 * @return String the URL for the logout flow
	 */
	public function getLoginStatusUrl($params=array()) 
	{
		return $this->getUrl('www', 'extern/login_status.php', array_merge(array(
	        'api_key'         => $this->getAppId(),
	        'no_session'      => $this->getCurrentUrl(),
	        'no_user'         => $this->getCurrentUrl(),
	        'ok_session'      => $this->getCurrentUrl(),
	        'session_version' => 3,
			), $params)
		);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see components/com_oauth/models/ComOauthModelOauth2s::getAccessToken()
	 */
 	public function getAccessToken()
	{
		$user = KFactory::get('lib.joomla.user');
	
		if ($user->id)
		{		
			$token = KFactory::tmp('site::com.oauth.model.tokens')
				->set('service', 'facebook')
				->set('userid', $user->id)
				->getList()->getData();		
			$token = reset($token);
			$return = $token['oauth_token'];
		}
		else
		{
			$return = KRequest::get('session.oauth_token', 'string');
		}
		
		return $return;
	}
}