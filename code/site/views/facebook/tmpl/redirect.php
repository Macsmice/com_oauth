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

if (!$service->name)
{
	echo 'Service not enabled';
}
else
{
	KFactory::tmp('lib.joomla.application')->redirect(
		$model->authorizeURL().
		'?client_id='.$service->consumer_key.
		'&redirect_uri=http://'.$_SERVER['HTTP_HOST'].@route('view='.$name.'&layout=callback').
		'&scope=publish_stream'
	);
}