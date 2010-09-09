<?php
/**
 * @version		0.1.0
 * @package		com_oauth
 * @copyright	Copyright (C) 2010 Beyounic SA & Joocode. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.beyounic.com - http://www.joocode.com
 */

class ComOauthModelSites extends KModelTable
{
    public function __construct(KConfig $config) 
	{		
		parent::__construct($config);
		
		$this->getState()
		 	->insert('text'  , 'string');
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$state = $this->getState();
		
		if ($state->search) 
		{
			$search = '%'.$state->search.'%';
			$query->where('tbl.target_type', 'LIKE',  $search);
			$query->where('tbl.text', 'LIKE',  $search, 'OR');
		}		
		
		parent::_buildQueryWhere($query);
	}
	
}