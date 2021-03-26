<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class MWebinarViewSessions extends JViewLegacy
{
	protected $results;

	function display($tpl = "csv")
	{
		$this->results = $this->get('Results');
		parent::display($tpl);
	}
}
