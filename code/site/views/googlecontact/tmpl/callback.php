<?
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

$service = KRequest::get('get.view', 'string');

$site = KFactory::get('site::com.oauth.model.sites')->slug($service)->getItem();

if (KRequest::get('session.oauth_token', 'string') !== KRequest::get('request.oauth_token', 'string')) 
{	
	$app = KFactory::tmp('lib.joomla.application');
	$url = KRequest::get('session.caller_url', 'string');
	$message = 'Old Token';
	$app->redirect($url, $message); 
}
else
{	
	$model = KFactory::get('com.oauth.model.'.$service.'s');
	$model->initialize(array($site->consumer_key, $site->consumer_secret, KRequest::get('session.oauth_token', 'raw'), KRequest::get('session.oauth_token_secret', 'raw')));
	$model->storeToken($service, $model->getAccessToken(KRequest::get('get.oauth_verifier', 'string')));
	$model->redirect();
}