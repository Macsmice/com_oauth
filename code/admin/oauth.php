<?php
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

// Check if Koowa is active
if(!defined('KOOWA')) {
    JError::raiseWarning(0, JText::_("Koowa wasn't found. Please install the Koowa plugin and enable it."));
    return;
}

// Require the defines
KLoader::load('admin::com.oauth.defines');
KFactory::map('lib.koowa.template.helper.behavior', 'admin::com.oauth.helper.behavior'); 
 
// Create the controller dispatcher
KFactory::get('admin::com.oauth.dispatcher')->dispatch(KRequest::get('get.view', 'cmd', 'sites'));
