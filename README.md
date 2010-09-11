Credits
========
	/**
	 * com_oauth	Developed using Nooku Framework 0.7  
	 * @package		com_oauth
	 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
	 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
	 * @link        http://www.beyounic.com - http://www.joocode.com
	 */

Nooku Framework general OAuth client library
Based on http://github.com/abraham/twitteroauth

INSTALLATION
------------

Simply download the package and symlink from the Joomla installation

####/administrator:####
	`ln -s com_oauth_directory/admin com_oauth`
####/components:####
	`ln -s com_oauth_directory/site com_oauth`

USAGE
-----

This component must be referenced and run by another component, let's call it the 'client' component. For example I reference how this works on buzzrewarder.com.
First, create a link to com_oauth, referencing the caller and return URL:

<?
$site->slug = 'twitter';
$url =  'option=com_oauth&view=oauth&service='.$site->slug;
$url .= '&caller_url='.base64_encode('index.php?option=com_campaigns&view=campaign&id='.$campaign->id);
$url .= '&return_url='.base64_encode('index.php?option=com_campaigns&view=campaign&id='.$campaign->id.'&layout=step2&service='.$site->slug);
?>

<a href="<?=@route($url)?>">Click here</a>

com_oauth takes care of logging the user to the service and coming back, storing the token in the database if the user is logged in, or storing it in the user's session if not.

After the user authorizes the application on the service, you can run the code you need, i.e.

<?
$user = KFactory::get('lib.joomla.user');
$name = KRequest::get('get.service', 'string');
$service = KFactory::get('site::com.oauth.model.sites')->slug($name)->getItem();	
$model = KFactory::get('site::com.oauth.model.'.KInflector::pluralize($name));

if ($user->id)
{
	$token = KFactory::tmp('site::com.oauth.model.tokens')
		->set('service', $name)
		->set('userid', $user->id)
		->getList()->getData();		
	$token = reset($token);

	if ($token)
	{
		$model->initialize(array($service->consumer_key, $service->consumer_secret, $token['oauth_token'], $token['oauth_token_secret']));
	}	
	else 
	{
		$url =  'index.php';												
		$message = "Invalid token";
		$app = KFactory::tmp('lib.joomla.application');
		$app->redirect($url, $message); 		
	}
}
else
{
	if (KRequest::get('session.oauth_token', 'string'))
	{
		$model->initialize(array($service->consumer_key, $service->consumer_secret, KRequest::get('session.oauth_token', 'string'), KRequest::get('session.oauth_token_secret', 'string')));
	}	
	else 
	{
		$url =  'index.php';											
		$message = "Invalid token";
		$app = KFactory::tmp('lib.joomla.application');
		$app->redirect($url, $message);
	}
}

$mylogin = $model->getMyLogin();
$numberOfFollowers = $model->countFollowers());

?>

Tested on Nooku Framework rev. 2513