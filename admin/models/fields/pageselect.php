<?php

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldPageSelect extends JFormField
{
	protected $type = 'PageSelect';

	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		$attr = '';
		$db = JFactory::getDBO();
		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->element['multiple'] ? ' multiple ' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		// Build the query for the ordering list.
		$query = $db->getQuery(true);
		$query->select('id AS value, title AS text');
		$query->from('#__mwebinar_pages');
		$query->where("webinar_id = ".$this->form->getValue('webinar_id'));
		$query->order('ordering');
		$db->setQuery($query);
		$html[] = '<select name="'.$this->name.'" class="inputbox input-xxlarge" '.$attr.'>';
		$html[] = '<option value="0">-- Default --</option>';
		$html[] = JHtml::_('select.options',$db->loadObjectList(),"value","text",$this->value);
		$html[] = '</select>';

		return implode($html);
	}
}
