<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');


class MWebinarControllerWebinars extends JControllerAdmin
{

	protected $text_prefix = "COM_MWEBINAR_WEBINAR";

	public function getModel($name = 'Webinar', $prefix = 'MWebinarModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}
