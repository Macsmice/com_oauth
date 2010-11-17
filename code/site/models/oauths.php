<?
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

class ComOauthModelOauths extends KModelAbstract
{
	public $oauthc = null;

	/**
	 * 
	 * Initializes the model
	 * @param $options $options[0] is site key, $options[1] is site secret
	 */
	function initialize(array $options) 
	{
		try
		{
			$this->oauthc = new OAuth($options[0], $options[1], OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);			
			$token = $this->getToken();
			$this->setToken($token['oauth_token'], $token['oauth_token_secret']);
		}
		catch (OAuthException $e) 
		{
	 		echo "Exception caught!\n";
		    echo "Response: ". $e->lastResponse . "\n";
		}
	}

	/**
	 * 
	 * Get the redirect uri. Can be overridden (see Facebook)
	 */
	function getRedirectUri()
	{
		$service = KInflector::singularize($this->getIdentifier()->package);
		return 'http://'.$_SERVER['HTTP_HOST'].JRoute::_('index.php?option=com_oauth&view=oauth&service='.$service.'&layout=default');
	}
	
	/**
	 * 
	 * Get the request token
	 * @returns a key/value array containing oauth_token and oauth_token_secret
	 */
	function getRequestToken($requestTokenUrl, $callbackUrl) 
	{
		return $this->oauthc->getRequestToken($requestTokenUrl, $callbackUrl);
	}
	
	function setToken($oauth_token, $oauth_token_secret)
	{
		return $this->oauthc->setToken($oauth_token, $oauth_token_secret);
	}
	
	function getAccessToken() 
	{
		return $this->oauthc->getAccessToken($this->accessTokenURL());
	}
	
	/**
	 * 
	 * Store the token in the database table #__oauth_tokens if the user is registered, otherwise put the token in the session
	 * @param string $service
	 * @param raw $accessToken
	 */
	function storeToken($token)
	{
		$service = KInflector::singularize($this->getIdentifier()->package);
		$this->setToken(is_array($token) ? $token['oauth_token'] : $token, is_array($token) ? $token['oauth_token_secret'] : 0);
		
		if ($token)
		{
			$user = KFactory::get('lib.joomla.user');
	
			if ($user->id) 
			{
				$myName = $this->getMyName();
				$myId = $this->getMyId();
				
				KFactory::tmp('site::com.oauth.model.tokens')
					->set('userid', $user->id)
					->set('service', $service)
					->set('service_username', $myName)
					->set('service_id', $myId)
					->getItem()
					->set('oauth_token', is_array($token) ? $token['oauth_token'] : $token)
					->set('oauth_token_secret', is_array($token) ? $token['oauth_token_secret'] : 0)
					->set('service_avatar', $this->getMyAvatar())
					->set('userid', $user->id)
					->set('service', $service)
					->set('service_username', $myName)
					->set('service_id', $myId)
					->save();
			}
			else
			{
				//TODO: Storing the token this way, the user can only have 1 enabled service (and 1 login per service). Getting a token for a second service will overwrite the previous sessions, so 
				//I need to have the name of the service in the variable itself.
				KRequest::set('session.service', $service);
				KRequest::set('session.oauth_token', is_array($token) ? $token['oauth_token'] : $token);
				KRequest::set('session.oauth_token_secret', is_array($token) ? $token['oauth_token_secret'] : 0);
			}
		}
		else
		{
			echo 'Null token';
		}
	}
	
	/**
	 * 
	 * Return the current token for the given service
	 * @param serviceName string the service slug
	 */
	function getToken()
	{
		$user = KFactory::get('lib.joomla.user');
		$service = KFactory::tmp('site::com.oauth.model.sites')->set('slug', KInflector::singularize($this->getIdentifier()->name))->getItem();	
		
		$return = null;
		
		//se sono loggato
		if ($user->id)
		{
			$token = KFactory::tmp('site::com.oauth.model.tokens')
				->set('service', KInflector::singularize($this->getIdentifier()->package))
				->set('userid', $user->id)
				->getList()->getData();
			$token = reset($token);
			
			if ($token)
			{
				$return = array();
				$return['oauth_token'] = $token['oauth_token'];
				$return['oauth_token_secret'] = $token['oauth_token_secret'];
			}	
		}
		else
		{
			if (KRequest::get('session.oauth_token', 'string'))
			{
				$return = array();
				$return['oauth_token'] = KRequest::get('session.oauth_token', 'string');
				$return['oauth_token_secret'] = KRequest::get('session.oauth_token_secret', 'string');
			}
		}
		
		return $return;
	}
	
	/**
	 * 
	 * Performs an API call
	 * @param $url 
	 * @param $extra_parameters an array of parameters
	 * @param $http_method defaults to GET, use OAUTH_HTTP_METHOD_POST for POST
	 * @param $http_headers additional headers you may want to send
	 */
	function fetch($url, $extra_parameters = null, $http_method = OAUTH_HTTP_METHOD_GET, $http_headers = array("User-Agent" => "pecl/oauth"))
	{
		try
		{
			return $this->oauthc->fetch($url, $extra_parameters, $http_method, $http_headers);
		}
		catch (OAuthException $e) 
		{
	 		echo "Exception caught!\n";
		    echo "Response: ". $e->lastResponse . "\n";
		}
	}
	
	/**
	 * 
	 * Returns the last result from an API operation
	 */
	function getLastResponse()
	{
		return $this->oauthc->getLastResponse();
	}

	/**
	 * 
	 * Redirect to the workflow after the token has been stored
	 */
	function redirect()
	{
		$app = KFactory::tmp('lib.joomla.application');
//
//		/* If HTTP response is 200 continue otherwise send to connect page to retry */
//		if (200 == $this->http_code || !$this->http_code) 
//		{
			$url = KRequest::get('session.return_url', 'url');
			$message = 'Authenticated';
			$app->redirect($url, $message); 
//		} 
//		else 
//		{
//			KRequest::set('session.oauth_token', null);
//			KRequest::set('session.oauth_token_secret', null);
//
//			$url = KRequest::get('session.caller_url', 'string');
//			$message = 'Error: not 200';
//			$app->redirect($url, $message); 
//		}
	}
	
	/* Default methods each specialized model will override */
	
	function getMyAvatar() {}
	function getMyId() {}
	function getMyName() {}
	function canPostStories() {}
}