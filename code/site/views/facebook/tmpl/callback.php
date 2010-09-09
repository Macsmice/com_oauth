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
$model = KFactory::get('site::com.oauth.model.'.$service.'s');
$model->initialize(array($site->consumer_key, $site->consumer_secret, 'http://'.$_SERVER['HTTP_HOST'].@route('view=facebook&layout=callback')));

$facebook = KFactory::tmp('site::com.oauth.model.facebooks', array(
  'appId'  => $site->consumer_key,
  'secret' => $site->consumer_secret,
  'cookie' => true,
));

$session = $facebook->getSession();

$model->storeToken($service, $session['access_token']);
$model->redirect();