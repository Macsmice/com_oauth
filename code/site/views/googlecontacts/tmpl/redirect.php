<?
/**
 * @version		0.1.0
 * @category	com_oauth
 * @copyright	Copyright (C) 2010 JooCode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.joocode.com
 */

define('SCOPE', 'https://www.google.com/m8/feeds/');

$name = KRequest::get('get.view', 'string');
$service = KFactory::get('site::com.oauth.model.sites')->slug($name)->getItem();

$model = KFactory::get('com.oauth.model.'.KInflector::pluralize($name));
$model->initialize(array($service->consumer_key, $service->consumer_secret));
 
/* Get temporary credentials. */
$request_token = $model->getRequestToken(array('scope' => SCOPE, 'oauth_callback' => 'http://'.$_SERVER['HTTP_HOST'].@route('view='.$name.'&layout=callback')));

/* Save temporary credentials to session. */
KRequest::set('session.oauth_token', $request_token['oauth_token']);
KRequest::set('session.oauth_token_secret', $request_token['oauth_token_secret']);

/* If last connection failed don't display authorization link. */
switch ($model->http_code) 
{
	case 200:
		/* Build authorize URL and redirect user to Twitter. */
		$app = KFactory::tmp('lib.joomla.application');
		$url = $model->getAuthorizeURL($request_token['oauth_token']);
		$app->redirect($url); 
		break;
	default:
		/* Show notification if something went wrong. */
		echo 'Could not connect to '.$name.'. Refresh the page or try again later.';
}