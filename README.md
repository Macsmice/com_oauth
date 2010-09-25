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
Uses PECL OAuth PHP extension http://pecl.php.net/package/oauth

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

After the user authorizes the application on the service, you can run the code you need on the return_url you set, i.e.

  <?
  $user = KFactory::get('lib.joomla.user');
  $serviceName = KRequest::get('get.service', 'string');
  $service = KFactory::get('site::com.oauth.model.sites')->slug($serviceName)->getItem();	
  $model = KFactory::get('site::com.oauth.model.'.KInflector::pluralize($serviceName));
				
  if ($model->getToken())
  {
  	  $model->initialize(array($service->consumer_key, $service->consumer_secret));
  }
  else
  {
	  $url =  'index.php';												
	  $message = "Invalid token";
	  KFactory::tmp('lib.joomla.application')->redirect($url, $message); 		
  }

  $mylogin = $model->getMyLogin();
  $numberOfFollowers = $model->countFollowers());
  $model->sendMessage('test', array('joocode'));
  ?>

You can add other functions in the model or in any other location, i.e. in the extension that uses com_oauth, but basically what should be best is trying to identify general purpose functions that are useful to everyone and put them in the com_oauth model so we can all use them, so feel free to fork, add what you think is right and push your modifications here :)

CHANGELOG
---------

15/09/2010
Added FourSquare and made LinkedIn, Facebook & Google Contacts work with the new APIs. Now we have Twitter, LinkedIn, FourSquare, Google Contacts, Facebook.

14/09/2010
I'm migrating the models to the new PECL OAuth extension from the previous http://github.com/abraham/twitteroauth library. Now only Twitter works.

09/09/2010
At the moment Twitter, Google Contacts (OAuth 1.0a) and Facebook (which uses OAuth 2) are fully working, just look into their model to see how they work. 
Tested on Nooku Framework rev. 2513

TODO
----

- Create a common redirect/callback interface
- Scope management for services such as Facebook with granular permissions (implemented as parameters array from client code)