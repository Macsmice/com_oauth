<?
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

/* this file is called default.php since Facebook doesn't allow parameters in the return URL. 
 * Also, facebook requires SEF URLs, or at least a menu pointing here so there are no params. 
 * 
 */ 


$service = KRequest::get('get.view', 'string');
$site = KFactory::get('site::com.oauth.model.sites')->slug($service)->getItem();

if (!KRequest::get('get.code', 'raw'))
{
	$app = KFactory::tmp('lib.joomla.application');
	$url = KRequest::get('session.caller_url', 'string');
	$message = 'Old Token';
	$app->redirect($url, $message); 
}
else 
{	
	$model = KFactory::get('site::com.oauth.model.'.$service.'s');
	$model->initialize(array($site->consumer_key, $site->consumer_secret));
	
	$model->fetch($model->accessTokenURL().'?client_id='.$site->consumer_key.'&redirect_uri=http://'.$_SERVER['HTTP_HOST'].@route('view='.$service.'&layout=callback').'&client_secret='.$site->consumer_secret.'&code='.KRequest::get('get.code', 'raw'));
	parse_str($model->getLastResponse());
	
 	$model->setToken($access_token, 0);   
 	$model->storeToken($service, $access_token);   
 	
 	$model->redirect();
}