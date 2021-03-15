<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once (JPATH_COMPONENT.'/controller.php');

// Require specific controller if requested
if($controller = JRequest::getVar('controller')) {
	require_once( JPATH_COMPONENT . 'controllers/' . $controller . '.php' );
}

$doc = JFactory::getDocument();

//jQuery
JHtml::_('jquery.framework');
$doc->addScript('media/com_mams/mediaelementjs/mediaelement-and-player.js');
if (JFactory::getApplication()->input->get('format') != "feed") {
	$doc->addStyleSheet('media/com_mams/mediaelementjs/mediaelementplayer.css');
}

// Create the controller
$classname	= 'MWebinarController'.$controller;
$controller = new $classname();
		
// Perform the Request task
$controller->execute( JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();
?>