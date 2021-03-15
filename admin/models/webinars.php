<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class MWebinarModelWebinars extends JModelList
{

	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the parameters.
		$params = JComponentHelper::getParams('com_mwebinar');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('s.name', 'asc');
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query->select('s.*');
		$query->from('#__mwebinar_webinars as s');
		$query->order('s.name ASC');

		return $query;
	}

	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		try
		{
			$this->cache[$store] = $this->_getList($this->_getListQuery(), $this->getStart(), 1000000);
		}
		catch (\RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		return $this->cache[$store];
	}

}
