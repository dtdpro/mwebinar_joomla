<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class MWebinarModelPages extends JModelList
{

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'ordering', 'a.ordering',
			);
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		$webinarId = $this->getUserStateFromRequest($this->context.'.filter.webinar', 'filter_webinar', null, 'int');
		$this->setState('filter.webinar', $webinarId);

		// List state information.
		parent::populateState('s.ordering', 'asc');
	}

	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter.webinar');

		return parent::getStoreId($id);
	}


	protected function getListQuery()
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query->select('s.*');
		$query->from('#__mwebinar_pages as s');
		$query->order('s.ordering ASC');

		if ($webinar = $this->getState('filter.webinar')) {
			$query->where('s.webinar_id = '.(int) $webinar);
		}


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

	public function getWebinars() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__mwebinar_webinars');
		$db->setQuery($query);
		$webinars = $db->loadObjectList();
		$webinarsbyid=array();
		foreach ($webinars as $w) {
			$webinarsbyid[$w->id] = $w->name;
		}
		return $webinarsbyid;
	}


}
