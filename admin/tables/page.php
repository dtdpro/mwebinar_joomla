<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

class MWebinarTablePage extends JTable
{
	function __construct(&$db)
	{
		parent::__construct('#__mwebinar_pages', 'id', $db);
	}

	public function store($updateNulls = false)
	{
		// Verify that the alias is unique
		$table = JTable::getInstance('Page', 'MWebinarTable');

		// Attempt to store the user data.
		return parent::store($updateNulls);
	}

	public function bind($array, $ignore = '')
	{
		if (isset($array['content']) && is_array($array['content']))
		{
			$array['content'] = json_encode($array['content']);
		}
		return parent::bind($array, $ignore);
	}


	public function check()
	{
		// check for valid name
		if (trim($this->title) == '') {
			$this->setError('Title Needed');
			return false;
		}

		return true;
	}


}
