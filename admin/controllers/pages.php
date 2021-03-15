<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');


class MWebinarControllerPages extends JControllerAdmin
{

	protected $text_prefix = "COM_MWEBINAR_PAGE";

	public function getModel($name = 'Page', $prefix = 'MWebinarModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function saveorder()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// Get the arrays from the Request
		$order = $this->input->post->get('order', null, 'array');
		$originalOrder = explode(',', $this->input->getString('original_order_values'));
		// Make sure something has changed
		if (!($order === $originalOrder))
		{
			parent::saveorder();
		}
		else
		{
			// Nothing to reorder
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
			return true;
		}
	}

	function listpages()
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
			$app->setUserState('com_mwebinar.pages.filter.webinar',$cid[0]);
		}

		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
	}


}
