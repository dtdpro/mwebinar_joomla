<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class MWebinarViewWebinars extends JViewLegacy
{
	protected $items;
	protected $state;

	function display($tpl = null)
	{
		$this->items = $this->get('Items');

		MWebinarHelper::addSubmenu(JRequest::getVar('view'),JRequest::getCmd('extension', 'com_mwebinar'));

		$this->addToolBar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('MWebinar - Webinars'), 'mwebinar');
		JToolBarHelper::addNew('webinar.add', 'JTOOLBAR_NEW');
		JToolBarHelper::editList('webinar.edit', 'JTOOLBAR_EDIT');
		JToolBarHelper::custom('webinars.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('webinars.unpublish', 'unpublish.png', 'unpublish_f2.png','JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::preferences('com_mwebinar');
	}
}
