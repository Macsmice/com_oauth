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
	public $http_code;					/* Contains the last HTTP status code returned. */
	public $url;						/* Contains the last API call. */
	public $timeout = 30;				/* Set timeout default. */
	public $connecttimeout = 30; 		/* Set connect timeout. */
	public $ssl_verifypeer = FALSE; 	/* Verify SSL Cert. */
	public $format = 'json';			/* Respons format. */
	public $decode_json = TRUE; 		/* Decode returned json data. */
	public $http_info;					/* Contains the last HTTP headers returned. */
	public $useragent = 'com_oauth'; 	/* Set the useragent. */
	//public $retry = TRUE;				/* Immediately retry the API call if the response was not successful. */

	function initialize(array $options) 
	{
		$consumer_key = $options[0];
		$consumer_secret = $options[1];
		$oauth_token = @$options[2] ? @$options[2] : null;
		$oauth_token_secret = @$options[3] ? @$options[3] : null;

		$this->sha1_method = KFactory::tmp('site::com.oauth.libs.oauth.signaturemethodhmacsha1');
		$this->consumer = KFactory::tmp('site::com.oauth.libs.oauth.consumer', array('key' => $consumer_key, 'secret' => $consumer_secret));

		if (!empty($oauth_token) && !empty($oauth_token_secret)) 
		{
			$this->token = KFactory::tmp('site::com.oauth.libs.oauth.consumer', array('key' => $oauth_token, 'secret' => $oauth_token_secret));			
		} 
		else 
		{
			$this->token = NULL;
		}
		return $this;
	}

	/**
	 * 
	 * Debug helpers
	 */
	function lastStatusCode() 
	{ 
		return $this->http_status; 
	}

	function lastAPICall() 
	{ 
		return $this->last_api_call; 
	}

	/**
	 * 
	 * Make an HTTP request
	 *
	 * @return API results
	 */
	function http($url, $method, $postfields = NULL) 
	{
		$this->http_info = array();
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);

	 
		//var_dump($headerParams);
		//echo '<br /><br />';
 
		curl_setopt($ci, CURLINFO_HEADER_OUT, true);

		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		curl_setopt($ci, CURLOPT_HEADER, false);
		curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));

		switch ($method) 
		{
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, true);
				if (!empty($postfields)) 
				{
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
				}
				break;
			case 'DELETE':
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if (!empty($postfields)) 
				{
					$url = "{$url}?{$postfields}";
				}
		}

		curl_setopt($ci, CURLOPT_URL, $url);
		
		$response = curl_exec($ci);
				
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
		$this->url = $url;
		curl_close ($ci);
		
		if ($this->http_code == 401) 
		{
			//echo $response;	
		}
		
		return $response;
	}

	/**
	 * 
 	 * Get the header info to store.
	 */
	function getHeader($ch, $header) 
	{
		$i = strpos($header, ':');

		if (!empty($i)) 
		{
			$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
			$value = trim(substr($header, $i + 2));
			$this->http_header[$key] = $value;
		}

		return strlen($header);
	}

	/**
	 * 
	 * Get the request token
	 * @returns a key/value array containing oauth_token and oauth_token_secret
	 */
	function getRequestToken($parameters = NULL) 
	{
		$request = $this->oAuthRequest($this->requestTokenURL(), 'GET', $parameters);
		$token = KFactory::get('site::com.oauth.libs.oauth.util')->parse_parameters($request);
		$this->token = KFactory::get('site::com.oauth.libs.oauth.consumer', array('key' => @$token['oauth_token'], 'secret' => @$token['oauth_token_secret']));
		return $token;
	}

	/**
	 * 
	 * Get the authorize URL
	 * @returns a string
	 */
	function getAuthorizeURL($token, $sign_in_with_twitter = TRUE) 
	{	
		if (is_array($token)) 
		{
			$token = $token['oauth_token'];
		}

		if ($token)
		{
			return $this->authorizeURL() . "?oauth_token={$token}";
		}
		else 
		{
			return null;
		}
	}


	/**
	 * 
	 * Exchange request token and secret for an access token and
	 * secret, to sign API calls.
	 *
	 * @returns array("oauth_token" => "the-access-token",
	 *                "oauth_token_secret" => "the-access-secret",
	 *                "user_id" => "9436992",
	 *                "screen_name" => "abraham")
	 */
	function getAccessToken($oauth_verifier = FALSE) 
	{
		$parameters = array();

		if (!empty($oauth_verifier)) 
		{
			$parameters['oauth_verifier'] = $oauth_verifier;
		}

		$request = $this->oAuthRequest($this->accessTokenURL(), 'GET', $parameters);
		$token = KFactory::get('site::com.oauth.libs.oauth.util')->parse_parameters($request);
				
		$this->token = KFactory::tmp('site::com.oauth.libs.oauth.consumer', array('key' => $token['oauth_token'], 'secret' => $token['oauth_token_secret']));

		return $token;
	}

	/**
	 * 
	 * Store the token in the database table #__oauth_tokens if the user is registered, otherwise put the token in the session
	 * @param string $service
	 * @param raw $accessToken
	 */
	function storeToken($service, $token)
	{
		$user = KFactory::get('lib.joomla.user');

		if ($user->id) 
		{
			if (is_array($token))
			{
				KFactory::tmp('site::com.oauth.model.tokens')
					->set('userid', $user->id)
					->set('service', $service)
					->getItem()
					->set('userid', $user->id)
					->set('service', $service)
					->set('oauth_token', $token['oauth_token'])
					->set('oauth_token_secret', $token['oauth_token_secret'])
					->save();
			}
			else 
			{
				KFactory::tmp('site::com.oauth.model.tokens')
					->set('userid', $user->id)
					->set('service', $service)
					->getList()->delete();
				KFactory::tmp('site::com.oauth.model.tokens')
					->getItem()
					->set('userid', $user->id)
					->set('service', $service)
					->set('oauth_token', $token)
					->set('oauth_token_secret', 0)
					->save();
			}
		}
		else
		{
			KRequest::set('session.service', $service);

			if (is_array($token))
			{
				KRequest::set('session.oauth_token', $token['oauth_token']);
				KRequest::set('session.oauth_token_secret', $token['oauth_token_secret']);
			}
			else
			{
				KRequest::set('session.oauth_token', $token);
				KRequest::set('session.oauth_token_secret', 0);
			}
		}
	}

	/**
	 * 
	 * Redirect to the workflow after the token has been stored
	 */
	function redirect()
	{
		$app = KFactory::tmp('lib.joomla.application');

		/* If HTTP response is 200 continue otherwise send to connect page to retry */
		if (200 == $this->http_code || !$this->http_code) 
		{
			$url = KRequest::get('session.return_url', 'url');
			$message = 'Authenticated';
			$app->redirect($url, $message); 
		} 
		else 
		{
			KRequest::set('session.oauth_token', null);
			KRequest::set('session.oauth_token_secret', null);

			$url = KRequest::get('session.caller_url', 'string');
			$message = 'Error: not 200';
			$app->redirect($url, $message); 
		}
	}

	/**
	* One time exchange of username and password for access token and secret.
	*
	* @returns array("oauth_token" => "the-access-token",
	*                "oauth_token_secret" => "the-access-secret",
	*                "user_id" => "9436992",
	*                "screen_name" => "abraham",
	*                "x_auth_expires" => "0")
	*/  
	function getXAuthToken($username, $password) 
	{
		$parameters = array();
		$parameters['x_auth_username'] = $username;
		$parameters['x_auth_password'] = $password;
		$parameters['x_auth_mode'] = 'client_auth';
		$request = $this->oAuthRequest($this->accessTokenURL(), 'POST', $parameters);
		$token = KFactory::get('site::com.oauth.libs.oauth.util')->parse_parameters($request);
		$this->token = KFactory::tmp('site::com.oauth.libs.oauth.consumer', array('key' => $token['oauth_token'], 'secret' => $token['oauth_token_secret']));
		return $token;
	}

	/**
	 * 
 	 * GET wrapper for oAuthRequest.
	 */
	function get($url, $parameters = array()) 
	{
		$response = $this->oAuthRequest($url, 'GET', $parameters);

//		if ($this->format === 'json' && $this->decode_json) 
//		{
//			return json_decode($response);
//		}

		return $response;
	}

	/**
	 * 
	 * POST wrapper for oAuthRequest.
	 */
	function post($url, $parameters = array()) 
	{
		$response = $this->oAuthRequest($url, 'POST', $parameters);

		if ($this->format === 'json' && $this->decode_json) 
		{
			return json_decode($response);
		}

		return $response;
	}

	/**
	 * 
	 * DELETE wrapper for oAuthReqeust.
	 */
	function delete($url, $parameters = array()) 
	{
		$response = $this->oAuthRequest($url, 'DELETE', $parameters);

		if ($this->format === 'json' && $this->decode_json)
		{
			return json_decode($response);
		}

		return $response;
	}

	/**
	 * 
	 * Format and sign an OAuth / API request
	 */
	function oAuthRequest($url, $method, $parameters) 
	{
		if (strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) 
		{
			$url = "{$this->host}{$url}.{$this->format}";
		}
		
		$request = KFactory::get('site::com.oauth.libs.oauth.request')->from_consumer_and_token($this->consumer, @$this->token, $method, $url, $parameters);
		$request->sign_request($this->sha1_method, $this->consumer, @$this->token);
		//echo $request->to_url();

		switch ($method) 
		{
			case 'GET':
				return $this->http($request->to_url(), 'GET', null);
			default:
				return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata());
		}
	}

	function getContacts()
	{
		$user = KFactory::get('lib.joomla.user');

		if (KFactory::tmp('site::com.oauth.model.tokens')
			->set('service', KRequest::get('get.service', 'string'))
			->set('userid', $user->id)
			->getTotal() > 0)
		{
			return $this->fetchContacts($this);
		}
		else
		{
			return array();
		}		
	}

	/**
	 * 
	 * Return an array of objects featuring
	 * - the contact ID (if the service provides a messaging system, such as Twitter or Facebook, the ID is the contact ID. Else, it is the contact e-mail
	 * - the contact display name
	 * - the contact avatar link
	 */
	function fetchContacts($model) {}

	/**
	 * 
	 * Return the login name, to store in the db
	 * @return string the login name
	 */
	function getMyLogin() {}
	
	/**
	 * 
	 * Return the followers count
	 */
	function countFollowers() {}
}