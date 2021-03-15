<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class MWebinarViewPages extends JViewLegacy
{
	protected $items;
	protected $state;
	protected $webinars;
	protected $webinarSelected=false;

	function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->webinars = $this->get('Webinars');

		if ($this->state->get('filter.webinar') != '') {
			$this->items = $this->get('Items');
			$this->webinarSelected = true;
		}
		else {
			$this->items = [];
			$this->webinarSelected = false;
		}

		MWebinarHelper::addSubmenu(JRequest::getVar('view'),JRequest::getCmd('extension', 'com_mwebinar'));

		$this->addToolBar();
		$this->addSideBar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('MWebinar - Pages'), 'mwebinar');
		JToolBarHelper::addNew('page.addvideo','Add Video Page');
		JToolBarHelper::addNew('page.addquestion','Add Question Page');
		JToolBarHelper::addNew('page.addrating','Add Rating Page');
		JToolBarHelper::addNew('page.addjump','Add Jump Page');

		JToolBarHelper::editList('page.edit', 'JTOOLBAR_EDIT');
		JToolBarHelper::custom('page.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('page.unpublish', 'unpublish.png', 'unpublish_f2.png','JTOOLBAR_UNPUBLISH', true);
	}

	protected function addSideBar() {
		JHtmlSidebar::setAction('index.php?option=com_mwebinar&view=pages');
		JHtmlSidebar::addFilter('-- Select Webinar --','filter_webinar',JHtml::_('select.options', $this->webinars, 'value', 'text', $this->state->get('filter.webinar')));
	}
}
