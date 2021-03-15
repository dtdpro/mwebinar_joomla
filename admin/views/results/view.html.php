<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class MWebinarViewResults extends JViewLegacy
{
	protected $results;
	protected $state;
	protected $webinars;
	protected $webinarSelected=false;

	function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->webinars = $this->get('Webinars');

		if ($this->state->get('filter.webinar') != '') {
			$this->results = $this->get('Results');
			$this->webinarSelected = true;
		}
		else {
			$this->results = [];
			$this->webinarSelected = false;
		}

		MWebinarHelper::addSubmenu(JRequest::getVar('view'),JRequest::getCmd('extension', 'com_mwebinar'));

		$doc = JFactory::getDocument();
		$doc->addScript( 'https://www.gstatic.com/charts/loader.js' );

		$this->addToolBar();
		$this->addSideBar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('MWebinar - Results'), 'mwebinar');
	}

	protected function addSideBar() {
		JHtmlSidebar::setAction('index.php?option=com_mwebinar&view=pages');
		JHtmlSidebar::addFilter('-- Select Webinar --','filter_webinar',JHtml::_('select.options', $this->webinars, 'value', 'text', $this->state->get('filter.webinar')));
	}
}
