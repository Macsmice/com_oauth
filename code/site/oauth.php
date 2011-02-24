<?php 
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

try 
{
	echo KFactory::get('site::com.oauth.dispatcher')->dispatch();
} 
catch (Exception $e) 
{ 
	KFactory::get('site::com.error.controller.error')->manage($e);
}
