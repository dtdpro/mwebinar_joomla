<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');


class MWebinarControllerResults extends JControllerAdmin
{

	protected $text_prefix = "COM_MWEBINAR_RESULTS";

	function showresults()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();

		// Get items to remove from the request.\
		$cid = $this->input->get('cid', array(), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			\JLog::add(\JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), \JLog::WARNING, 'jerror');
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=webinars', false));
		}
		else
		{
			$app->setUserState('com_mwebinar.results.filter.webinar',$cid[0]);
		}

		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
	}


}
