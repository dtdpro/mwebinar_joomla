<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class MWebinarModelSessions extends JModelList
{

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		$webinarId = $this->getUserStateFromRequest($this->context.'.filter.webinar', 'filter_webinar', null, 'int');
		$this->setState('filter.webinar', $webinarId);

		// List state information.
		parent::populateState('s.ordering', 'asc');
	}

	public function getResults()
	{
		$db = JFactory::getDBO();
		$webinar = $this->getState('filter.webinar');

		$query = $db->getQuery(true);
		$query->select('s.*');
		$query->from('#__mwebinar_pages as s');
		$query->order('s.ordering ASC');
		$query->where('s.webinar_id = '.(int) $webinar);
		$query->andWhere('s.type IN ("field","question","rating")');
		$db->setQuery($query);
		$questionPages = $db->loadObjectList();

		$optionsByPage = [];
		$pageTypes = [];

		foreach ($questionPages as &$qp) {
			$pageTypes[$qp->id] = $qp->type;
			$qp->content = json_decode($qp->content);
			if ($qp->type == "question") {
				$options = [];
				foreach ($qp->content->options as $o) {
					$options[$o->name] = $o->title;
				}
				$optionsByPage[$qp->id] = $options;
			}
		}

		$query = $db->getQuery(true);
		$query->select('s.*');
		$query->from('#__mwebinar_webinaranswer as s');
		$query->where('s.webinar = '.(int) $webinar);
		$query->order('s.created_at desc');
		$db->setQuery($query);
		$answerResults = $db->loadObjectList();

		$results = [];
		foreach ($answerResults as $ar) {
			$results[$ar->sessionid][0] = $ar->created_at;
			if ($pageTypes[$ar->page] == "field") $results[$ar->sessionid][$ar->page] = json_decode($ar->answer,true);
			else if ($pageTypes[$ar->page] == "question") {
				if (isset($results[$ar->sessionid][$ar->page])) $results[$ar->sessionid][$ar->page] = $results[$ar->sessionid][$ar->page].", ".$optionsByPage[$ar->page][$ar->answer];
				else $results[$ar->sessionid][$ar->page] = $optionsByPage[$ar->page][$ar->answer];
			}
			else $results[$ar->sessionid][$ar->page] = $ar->answer;
		}

		return ['results'=>$results,'pages'=>$questionPages];
	}

	public function getWebinars() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query->select('s.id, CONCAT(c.title," - ",s.name) AS title');
		$query->from('#__mwebinar_webinars as s');
		$query->join('LEFT', '#__categories AS c ON c.id = s.catid');
		$query->order('name');
		$db->setQuery($query);

		$webinars = $db->loadObjectList();
		$webinarsbyid=array();
		foreach ($webinars as $w) {
			$webinarsbyid[$w->id] = $w->title;
		}
		return $webinarsbyid;
	}


}
