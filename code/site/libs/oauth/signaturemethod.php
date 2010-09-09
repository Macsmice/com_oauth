<?
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

/**
 * A class for implementing a Signature Method
 * See section 9 ("Signing Requests") in the spec
 */
abstract class ComOauthLibsOauthSignaturemethod extends KObject
{
	/**
	* Needs to return the name of the Signature Method (ie HMAC-SHA1)
	* @return string
	*/
	abstract public function get_name();
	
	/**
	* Build up the signature
	* NOTE: The output of this function MUST NOT be urlencoded.
	* the encoding is handled in OAuthRequest when the final
	* request is serialized
	* @param OAuthRequest $request
	* @param OAuthConsumer $consumer
	* @param OAuthToken $token
	* @return string
	*/
	abstract public function build_signature($request, $consumer, $token);
	
	/**
	* Verifies that a given signature is correct
	* @param OAuthRequest $request
	* @param OAuthConsumer $consumer
	* @param OAuthToken $token
	* @param string $signature
	* @return bool
	*/
	public function check_signature($request, $consumer, $token, $signature) 
	{
		$built = $this->build_signature($request, $consumer, $token);
		return $built == $signature;
	}
}