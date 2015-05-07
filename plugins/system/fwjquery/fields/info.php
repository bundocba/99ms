<?php
defined('JPATH_BASE') or die;

/**
 * Show info Html.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_content
 * @since		1.6
 */
class JFormFieldInfo extends JFormField
{
	protected $type = 'info';
	protected function getInput()
	{
		return "<span style='color: ".$this->element->getAttribute('color')."'>- ".$this->element->data()."</span>";
	}
	protected function getLabel() {
		return "";
	}
}