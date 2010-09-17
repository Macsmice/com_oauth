<?php
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

class ComOauthViewOauthHtml extends ComDefaultViewHtml 
{
	public function display()
	{
		$layout = KRequest::get('get.layout', 'string');
		KRequest::set('session.caller_url', JRoute::_(base64_decode(KRequest::get('get.caller_url', 'url'))));
		KRequest::set('session.return_url', JRoute::_(base64_decode(KRequest::get('get.return_url', 'url'))));
					
		$user = KFactory::get('lib.joomla.user');
		$url = '';
	
		if ($layout == 'add')
		{
			$url = JRoute::_('index.php?option=com_oauth&view='.KRequest::get('get.service', 'string').'&layout=redirect'); 
		}
		else
		{
			$hasToken = false;		
			
			if ($user->id)
			{
				if (KFactory::tmp('site::com.oauth.model.tokens')
					->set('service', KRequest::get('get.service', 'string'))
					->set('userid', $user->id)
					->getTotal())
				{
					$hasToken = true;
				}
			}
			else
			{
				if (KRequest::get('session.service', 'string') == KRequest::get('get.service', 'string') && KRequest::get('session.oauth_token', 'string'))
				{
					$hasToken = true;
				} 
			}
			
			if ($hasToken)
			{
				$url = JRoute::_(base64_decode(KRequest::get('get.return_url', 'url')));
			}
			else
			{	
				$url = JRoute::_('index.php?option=com_oauth&view='.KRequest::get('get.service', 'string').'&layout=redirect'); 
			}
		}
		
		KFactory::tmp('lib.joomla.application')->redirect($url);
	}
}