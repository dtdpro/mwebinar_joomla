<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class MWebinarViewPage extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;

	public function display($tpl = null)
	{
		// get the Data
		$this->state = $this->get('State');
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		JRequest::setVar('hidemainmenu', true);
		$user = JFactory::getUser();
		$userId = $user->id;
		$isNew = $this->item->id == 0;
		JToolBarHelper::title($isNew ? "MWebinar - New Page" : "MWebianr - Edit Page", 'mams');
		// Built the actions for new and existing records.
		if ($isNew)
		{
			// For new records, check the create permission.
			JToolBarHelper::apply('page.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('page.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('page.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			JToolBarHelper::cancel('page.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::apply('page.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('page.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('page.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			JToolBarHelper::cancel('page.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
