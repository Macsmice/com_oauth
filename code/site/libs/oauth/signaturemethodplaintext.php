<?
/**
 * @version		0.1.0
 * @category	com_oauth
 * @copyright	Copyright (C) 2010 JooCode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.joocode.com
 */

/**
 * The PLAINTEXT method does not provide any security protection and SHOULD only be used 
 * over a secure channel such as HTTPS. It does not use the Signature Base String.
 *   - Chapter 9.4 ("PLAINTEXT")
 */

class ComOauthLibsOauthSignaturemethodplaintext extends ComOauthLibsOauthSignaturemethod
{
	public function get_name() 
	{
		return "PLAINTEXT";
	}
	
	/**
	* oauth_signature is set to the concatenated encoded values of the Consumer Secret and 
	* Token Secret, separated by a '&' character (ASCII code 38), even if either secret is 
	* empty. The result MUST be encoded again.
	*   - Chapter 9.4.1 ("Generating Signatures")
	*
	* Please note that the second encoding MUST NOT happen in the SignatureMethod, as
	* OAuthRequest handles this!
	*/
	public function build_signature($request, $consumer, $token) 
	{
		$key_parts = array (
			$consumer->secret,
			($token) ? $token->secret : ""
		);
		
		$key_parts = OAuthUtil::urlencode_rfc3986($key_parts);
		$key = implode('&', $key_parts);
		$request->base_string = $key;
		
		return $key;
	}
}