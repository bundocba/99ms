<?php
/**
 *
 * Product controller
 *
 * @package	VirtueMart
 * @subpackage
 * @author RolandD
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product.php 5726 2012-03-30 00:17:55Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmController'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'vmcontroller.php');


/**
 * Product Controller
 *
 * @package    VirtueMart
 * @author
 */
class VirtuemartControllerColors extends VmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 * @author
	 */
	function __construct() {
		parent::__construct('id');
		$this->addViewPath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart' . DS . 'views');
		$this->registerTask( 'apply', 'save' );
		$this->registerTask('unpublish', 'publish');
	}


	/**
	 * Shows the product add/edit screen
	 */
	public function edit() {
 		parent::edit('colors_edit');
	}

	/**
	 * We want to allow html so we need to overwrite some request data
	 *
	 * @author Max Milbers
	 */
	function save(){
		if(!isset($_POST['published'])) $_POST['published'] = 0;

		$data = JRequest::get('post');

		JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
        $row = &JTable::getInstance("Colors","JTable");

    	if (!$row->bind(JRequest::get('post'))) 
		{
			JError::raiseError(500, $row->getError() );
		}
		if (!$row->store()){
			JError::raiseError(500, $row->getError() );
		}
		
		if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_virtuemart&view=colors&task=edit&id=".$row->id, 'Color Apply');
        }else{
            $this->setRedirect("index.php?option=com_virtuemart&view=colors", 'Color Save');
        } 
	}

	function publish(){
		if ($this->getTask() == 'unpublish') {
			$this->_publishColor(0);
			$this->setRedirect("index.php?option=com_virtuemart&view=colors");
		} else {
			$this->_publishColor(1);
			$this->setRedirect("index.php?option=com_virtuemart&view=colors");
		}
	}
	
	function _publishColor($flag) {
		//  $jshopConfig = &JSFactory::getConfig();
		$db = &JFactory::getDBO();
		$cid = JRequest::getVar('cid');
	
		foreach ($cid as $key => $value) {
			$query = "UPDATE `#__virtuemart_colors`
			SET `published` = '" . $db->getEscaped($flag) . "'
			WHERE `id` = '" . $db->getEscaped($value) . "'";
	
			$db->setQuery($query);
			$db->query();
		}
	}
	
	function remove(){
		$db = &JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		foreach ($cid as $key => $value) {
			JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
			$product =& JTable::getInstance('Colors', 'JTable');
			$product->load($value);
			$query = "DELETE FROM `#__virtuemart_colors` WHERE `id` = '" . $db->getEscaped($value) . "'";
			$db->setQuery($query);
			$db->query();
			$text[]= sprintf('Delete', $value)."<br>";
		}
	
		$this->setRedirect("index.php?option=com_virtuemart&view=colors", implode("</li><li>",$text));
	}
	

}
// pure php no closing tag
