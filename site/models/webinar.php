<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;

class MWebinarModelWebinar extends JModelLegacy {

	function getConfig() {
		$config = JComponentHelper::getParams('com_mwebinar');
		$cfg = $config->toObject();
		return $cfg;
	}

	function getWebinar($webinar) {
		$db    = JFactory::getDBO();
		$config = $this->getConfig();
		$sessionId = JFactory::getSession()->getId();

		$query = $db->getQuery( true );
		$query->select( 's.*' );
		$query->from( '#__mwebinar_webinars as s' );
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = s.catid');
		$query->where( 's.id = ' . $webinar );
		$query->andWhere( 's.published > 0');
		$db->setQuery( $query );
		$webinarData = $db->loadObject();

		if (!$webinarData) {
			return false;
		}

		$webinarData->params = json_decode($webinarData->params);

		$db    = JFactory::getDBO();
		$queryList = $db->getQuery( true );
		$queryList->select( '*' );
		$queryList->from( '#__mwebinar_pages' );
		$queryList->where( 'webinar_id = ' . $webinar );
		$queryList->andWhere( 'published > 0');
		$queryList->order('ordering ASC');
		$db->setQuery( $queryList );
		$pagesData = $db->loadObjectList();

		$pages = [];
		$pageMatch = [];
		$completed = [];
		foreach ($pagesData as $key=>$page) {
			$newPage = json_decode($page->content,true);
			unset($page->content);
			$newPage = array_merge(ArrayHelper::fromObject($page),$newPage);
			$pages[] = $newPage;
			$pageMatch[$page->id] = $key;
			$completed[$key] = false;
		}

		$webinarData->numPages = count($pagesData);
		$webinarData->pages = $pages;
		$webinarData->pageMatch=$pageMatch;
		$webinarData->completed = $completed;
		$webinarData->uikit=$config->uikitversion;
		$webinarData->sessionId = uniqid($sessionId."_",true);

		return $webinarData;
	}

	function saveAnswer($webinarId,$pageId,$answer,$sessionId) {
		$db = JFactory::getDBO();
		$jinput = JFactory::getApplication()->input;
		$ip = $jinput->server->get('REMOTE_ADDR');

		foreach ($answer as $a) {
			$newAnswer             = new stdClass();
			$newAnswer->answer     = $db->escape( $a );
			$newAnswer->webinar    = $db->escape( $webinarId );
			$newAnswer->page       = $db->escape( $pageId );
			$newAnswer->created_at = date( "Y-m-d H:i:s" );
			$newAnswer->sessionid  = $sessionId;
			$newAnswer->sourceip   = $db->escape( $ip );
			if ( ! $db->insertObject( '#__mwebinar_webinaranswer', $newAnswer ) ) {
				$this->setError( "Error saving data" );

				return false;
			}
		}

		return true;
	}

	function saveField($webinarId,$pageId,$answer,$sessionId) {
		$db = JFactory::getDBO();
		$jinput = JFactory::getApplication()->input;
		$ip = $jinput->server->get('REMOTE_ADDR');

		$escapedAnswers = [];

		foreach ($answer as $ak=>$av) {
			$escapedAnswers[$db->escape($ak)] = $db->escape($av);
		}

		$newAnswer             = new stdClass();
		$newAnswer->answer     = json_encode($escapedAnswers);
		$newAnswer->webinar    = $db->escape( $webinarId );
		$newAnswer->page       = $db->escape( $pageId );
		$newAnswer->created_at = date( "Y-m-d H:i:s" );
		$newAnswer->sessionid  = $sessionId;
		$newAnswer->sourceip   = $db->escape( $ip );
		if ( ! $db->insertObject( '#__mwebinar_webinaranswer', $newAnswer ) ) {
			$this->setError( "Error saving data" );

			return false;
		}

		return true;
	}

}