<?
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

class ComOauthModelSubscrins extends ComOauthModelOauths
{
	public $host = "http://localhost/subscrin/"; /* Set up the API root URL. */
	
	function authorizeURL()    
	{ 
		return 'http://localhost/subscrin/index.php?option=com_oauthserver&view=token&layout=authorize'; 
	}
	function accessTokenURL()  
	{ 
		return 'http://localhost/subscrin/index.php?option=com_oauthserver&view=token&layout=accesstoken'; 
	}
	
 	function getMyData()
 	{
 		return null;
 	}
 	
 	function getMyId()
 	{
 		return null;
 	}
 	
 	function getMyName()
 	{
 		return null;
 	}
 	
	/**
 	 * 
 	 * Return false, can't send messages directly using Facebook API
 	 */
 	function canSendMessage()
 	{
 		return false;
 	}
}