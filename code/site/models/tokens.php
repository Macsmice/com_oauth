<?php
/**
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

class ComOauthModelTokens extends KModelTable
{
    public function __construct($config) 
	{
		$config['table_behaviors'] = array('creatable', 'modifiable'); 
		parent::__construct($config);
		
		// Set the state
		$this->getState()
		 	->insert('userid', 'int')
		 	->insert('created_by', 'int')
		 	->insert('service', 'string')
		 	->insert('service_login', 'raw')
		 	->insert('service_id', 'raw')
		 	->insert('service_avatar', 'raw')
		 	->insert('oauth_token', 'raw')
		 	->insert('oauth_token_secret', 'raw');
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		parent::_buildQueryWhere($query);

		$state = $this->getState();
			
		if ($state->service)
		{
			$query->where('service', '=', $state->service);
		}
		
		if ($state->oauth_token)
		{
			$query->where('oauth_token', '=', $state->oauth_token);
		}	
		
		if ($state->oauth_token_secret)
		{
			$query->where('oauth_token_secret', '=', $state->oauth_token_secret);
		}	
		
		if ($state->service_login)
		{
			$query->where('service_login', '=', $state->service_login);
		}	
		
		if ($state->service_id)
		{
			$query->where('service_id', '=', $state->service_id);
		}	
		
		if ($state->service_avatar)
		{
			$query->where('service_avatar', '=', $state->service_avatar);
		}
		
		if ($state->userid)
		{
			$query->where('userid', '=', $state->userid);
		}
		
		if ($state->created_by)
		{
			$query->where('created_by', '=', $state->created_by);
		}
	}
}