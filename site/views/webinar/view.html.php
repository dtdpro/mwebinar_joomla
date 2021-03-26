<?php

jimport( 'joomla.application.component.view');


class MWebinarViewWebinar extends JViewLegacy
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$jinput = JFactory::getApplication()->input;

		$model = $this->getModel();
		$doc = JFactory::getDocument();
		$output = null;

		$this->params = $app->getParams();
		$webinar = $jinput->get('webinar');
		$this->task = $jinput->get( 'task',"webinar" );
		$this->config = $model->getConfig();

		if ($this->task == "webinar") {
			$this->webinar = $model->getWebinar($webinar);
			$this->webinarJSON = json_encode($model->getWebinar($webinar));
			$doc->setTitle($this->webinar->category_title." - ".$this->webinar->name);
			parent::display( $tpl );
		}

		if ($this->task == "answer") {
			$pageId = $jinput->get( 'page' );
			$answer = $jinput->get(  'page_question' );
			$sessionId = $jinput->get(  'page_sessionid' );
			if (!is_array($answer)) {
				$answer = [$answer];
			}
			$model->saveAnswer($webinar,$pageId,$answer,$sessionId);
			echo 1;
			exit;
		}

		if ($this->task == "savefield") {
			$pageId = $jinput->get( 'page' );
			$answer = $jinput->get(  'page_field' );
			$sessionId = $jinput->get(  'page_sessionid' );
			if (!is_array($answer)) {
				$answer = [$answer];
			}
			$model->saveField($webinar,$pageId,$answer,$sessionId);
			echo 1;
			exit;
		}
	}

}
?>
