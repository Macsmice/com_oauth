<?php
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

class ComOauthViewHtml extends ComDefaultViewHtml
{
	public function __construct(KConfig $config)
	{
        $config->views = array(
			'sites' 		=> JText::_('Sites')
		);
		
		parent::__construct($config);
	}
	
	public function display()
	{
		$name = $this->getName();
		
		//Append enable and disable button for all the list views
		if($name != 'dashboard' && KInflector::isPlural($name) && KRequest::type() != 'AJAX')
		{
			KFactory::get('admin::com.oauth.toolbar.'.$name)
				->append('divider')	
				->append('enable')
				->append('disable');	
		}
					
		return parent::display();
	}
}