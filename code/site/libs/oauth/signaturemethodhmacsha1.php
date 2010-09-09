<?
/**
 * @version		0.1.0
 * @category	com_oauth
 * @copyright	Copyright (C) 2010 JooCode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.joocode.com
 */

/**
 * The HMAC-SHA1 signature method uses the HMAC-SHA1 signature algorithm as defined in [RFC2104] 
 * where the Signature Base String is the text and the key is the concatenated values (each first 
 * encoded per Parameter Encoding) of the Consumer Secret and Token Secret, separated by an '&' 
 * character (ASCII code 38) even if empty.
 *   - Chapter 9.2 ("HMAC-SHA1")
 */
class ComOauthLibsOauthSignaturemethodhmacsha1 extends ComOauthLibsOauthSignaturemethod
{
	function get_name() 
	{
		return "HMAC-SHA1";
	}
	
	public function build_signature($request, $consumer, $token) 
	{
		$base_string = $request->get_signature_base_string();
		$request->base_string = $base_string;
	
		$key_parts = array (
			$consumer->secret,
			($token) ? $token->secret : ""
		);
	
		$key_parts = KFactory::get('site::com.oauth.libs.oauth.util')->urlencode_rfc3986($key_parts);
		$key = implode('&', $key_parts);
	
		return base64_encode(hash_hmac('sha1', $base_string, $key, true));
	}
}