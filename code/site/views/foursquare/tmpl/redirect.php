<?
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

$name = KRequest::get('get.view', 'string');
$service = KFactory::get('site::com.oauth.model.sites')->slug($name)->getItem(); 
$model = KFactory::get('site::com.oauth.model.'.KInflector::pluralize($name));
$model->initialize(array($service->consumer_key, $service->consumer_secret));
 
/* Get temporary credentials. */
$request_token = $model->getRequestToken($model->requestTokenURL(), 'http://'.$_SERVER['HTTP_HOST'].@route('view='.$name.'&layout=callback'));  
KRequest::set('session.request_token', $request_token['oauth_token']);
KRequest::set('session.request_token_secret', $request_token['oauth_token_secret']);
KFactory::tmp('lib.joomla.application')->redirect($model->authorizeURL().'?oauth_token='.$request_token['oauth_token']);