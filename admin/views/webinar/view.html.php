<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class MWebinarViewWebinar extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;

	public function display($tpl = null)
	{
		// get the Data
		$this->state = $this->get('State');
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');

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
		JToolBarHelper::title($isNew ? "MWebinar - New Webinar" : "MWebianr - Edit Webinar", 'mams');
		// Built the actions for new and existing records.
		if ($isNew)
		{
			// For new records, check the create permission.
			JToolBarHelper::apply('webinar.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('webinar.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('webinar.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			JToolBarHelper::cancel('webinar.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::apply('webinar.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('webinar.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('webinar.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			JToolBarHelper::cancel('webinar.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
