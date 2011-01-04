<?php 
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

class ComOauthControllerToken extends ComDefaultControllerDefault 
{
	public function getRedirect()
	{
		$action = KRequest::get('post.action', 'string');

		if ($action == 'delete')
		{
	  		$return = KRequest::get('post.return', 'url');

			return $result = array(
				'url' 			=> JRoute::_($return, false),
			);
		}
		else 
		{			
			$result = array();
			
			if(!empty($this->_redirect))
			{
				$url = $this->_redirect;
			
				//Create the url if no full URL was passed
				if(strrpos($url, '?') === false) 
				{
					$url = 'index.php?option=com_'.$this->_identifier->package.'&'.$url;
				}
			
				$result = array(
					'url' 			=> JRoute::_($url, false),
					'message' 		=> $this->_message,
					'messageType' 	=> $this->_messageType,
				);
			}
			
			return $result;
		}
	}
	
}