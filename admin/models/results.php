<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class MWebinarModelResults extends JModelList
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
		$query->andWhere('s.type IN ("question","rating")');
		$db->setQuery($query);
		$questionPages = $db->loadObjectList();

		$results = [];

		foreach ($questionPages as $qp) {
			$newResult = [];
			$total=0;
			$pageContent = json_decode($qp->content);
			$question = $pageContent->question;
			$answers = $pageContent->options;
			foreach ($answers as &$a) {
				$query = $db->getQuery(true);
				$query->select('s.*');
				$query->from('#__mwebinar_webinaranswer as s');
				$query->where('s.page = '.(int) $qp->id);
				$query->where('s.answer = "'.$a->name.'"');
				$db->setQuery($query);
				$answerResults = $db->loadObjectList();
				$a->count = count($answerResults);
				$total = $total+count($answerResults);
			}
			$newResult['question'] = $question;
			$newResult['answers'] = $answers;
			$newResult['total'] = $total;
			$results[] = $newResult;
		}

		return $results;
	}

	public function getWebinars() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__mwebinar_webinars');
		$db->setQuery($query);
		$webinars = $db->loadObjectList();
		$webinarsbyid=array();
		foreach ($webinars as $w) {
			$webinarsbyid[$w->id] = $w->name;
		}
		return $webinarsbyid;
	}


}
