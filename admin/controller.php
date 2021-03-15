<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class MWebinarController extends JControllerLegacy
{
	function display($cachable = false, $urlparams = false)
	{
		// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'webinars'));

		// call parent behavior
		parent::display($cachable,$urlparams);
	}

}
?>
