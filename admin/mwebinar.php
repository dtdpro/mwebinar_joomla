<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_mwebinar'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

require_once JPATH_COMPONENT . '/helpers/mwebinar.php';


$controller = JControllerLegacy::getInstance('MWebinar');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();


?>
