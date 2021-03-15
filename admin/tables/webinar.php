<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

class MWebinarTableWebinar extends JTable
{
	function __construct(&$db)
	{
		parent::__construct('#__mwebinar_webinars', 'id', $db);
	}

	public function store($updateNulls = false)
	{
		// Verify that the alias is unique
		$table = JTable::getInstance('Webinar', 'MWebinarTable');
		if ($table->load(array('alias'=>$this->alias)) && ($table->id != $this->id || $this->id==0)) {
			$this->setError('Alias must be unique');
			return false;
		}

		// Attempt to store the user data.
		return parent::store($updateNulls);
	}

	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params']))
		{
			$array['params'] = json_encode($array['params']);
		}
		return parent::bind($array, $ignore);
	}


	public function check()
	{
		// check for valid name
		if (trim($this->name) == '') {
			$this->setError('Title Needed');
			return false;
		}

		if (empty($this->alias)) {
			$this->alias = $this->name;
		}
		$this->alias = JApplication::stringURLSafe($this->alias);
		if (trim(str_replace('-','',$this->alias)) == '') {
			$this->alias = JFactory::getDate()->format("Y-m-d-H-i-s");
		}

		return true;
	}


}
