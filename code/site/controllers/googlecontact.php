<?php 
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

class ComOauthControllerGooglecontact extends ComOauthControllerOauth 
{
	protected function _processRedirect($layout, $view)
	{
		define('SCOPE', 'https://www.google.com/m8/feeds/');
		
		$service = KFactory::get('site::com.oauth.model.sites')->slug($view)->getItem();
		$model = KFactory::get('site::com.oauth.model.'.KInflector::pluralize($view));
		$model->initialize(array($service->consumer_key, $service->consumer_secret));
		
		if (!$service->title)
		{
			echo 'Service not enabled';
		}
		else
		{
			$request_token = $model->getRequestToken($model->requestTokenURL().'?scope='.SCOPE, 'http://'.$_SERVER['HTTP_HOST'].@route('view='.$view.'&layout=callback'));  
			KRequest::set('session.request_token', $request_token['oauth_token']);
			KRequest::set('session.request_token_secret', $request_token['oauth_token_secret']);
			KFactory::tmp('lib.joomla.application')->redirect($model->authorizeURL().'?oauth_token='.$request_token['oauth_token']);
		}
	}
}