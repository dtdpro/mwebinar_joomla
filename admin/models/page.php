<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class MWebinarModelPage extends JModelAdmin
{
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');

		// Load the User state.
		$pk = $app->input->getInt('id');


		if ($type = $app->getUserState('com_mwebinar.add.page.type'))
		{
			$this->setState('page.type', $type);
		}

		$this->setState('page.id', $pk);

		parent::populateState();
	}

	public function getTable($type = 'Page', $prefix = 'MWebinarTable', $config = array())
	{
		$table = JTable::getInstance($type, $prefix, $config);
		return $table;
	}


	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$this->populateState();
		$type = $this->getState('page.type');

		$form = $this->loadForm('com_mwebinar.page', 'page_'.$type, array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$app = JFactory::getApplication();

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_mwebinar.edit.page.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			if ($this->getState('page.id') == 0)
			{
				$data->set('webinar_id', $app->input->get('webinar_id', $app->getUserState('com_mwebinar.pages.filter.webinar'), 'int'));
				$data->set('type', $app->input->get('type', $app->getUserState('com_mwebinar.add.page.type'), 'string'));
			}

		}

		$this->preprocessData('com_mwebinar.page', $data);

		return $data;
	}

	protected function prepareTable($table)
	{
		if (empty($table->id)) {
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__mwebinar_pages WHERE webinar_id = '.$table->webinar_id);
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
	}

	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'webinar_id = '.$table->webinar_id;
		return $condition;
	}

	public function saveorder($pks = [], $order = null)
	{
		$table = $this->getTable();
		$pks = (array) $pks;
		$result = true;

		$allowed = true;

		$neworder = 1;

		foreach ($pks as $i => $pk)
		{
			$table->reset();

			if ($table->load($pk))
			{
				$table->ordering = $neworder;
				$table->store();

				$neworder++;
			}
			else
			{
				$this->setError($table->getError());
				unset($pks[$i]);
				$result = false;
			}
		}

		if ($allowed === false && empty($pks))
		{
			$result = null;
		}

		// Clear the component's cache
		if ($result == true)
		{
			$this->cleanCache();
		}

		return $result;
	}



	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			$this->setState('page.type', $item->type);
		}

		if (!empty($item->id))
		{
			$item->content = json_decode($item->content,true);
		}

		return $item;
	}


}
