<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage
 * @author RolandD
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2011 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product.php 5908 2012-04-16 12:21:58Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the model framework
jimport( 'joomla.application.component.model');

if(!class_exists('VmModel'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'vmmodel.php');

// JTable::addIncludePath(JPATH_VM_ADMINISTRATOR.DS.'tables');
/**
 * Model for VirtueMart Products
 *
 * @package VirtueMart
 * @author RolandD
 * @todo Replace getOrderUp and getOrderDown with JTable move function. This requires the vm_product_category_xref table to replace the ordering with the ordering column
 */
class VirtueMartModelUploadimg extends VmModel {

	

	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct('id');
		
	}


	function _getAllColorQueryForFilter($filter){
		//        $lang = &JSFactory::getLang();
		$db =& JFactory::getDBO();
		$where = "";
		if ($filter['text_search']){
			$text_search = $filter['text_search'];
			$word = addcslashes($db->getEscaped($text_search), "_%");
			$where .=  "AND (LOWER(pr.`name`) LIKE '%" . $word . "%' OR LOWER(pr.`code`) LIKE '%" . $word . "%')";
		}
		if ($filter['published']){
			if ($filter['published']==1) $_publish = 1; else $_publish = 0;
			$where .= " AND pr.published = '".$db->getEscaped($_publish)."' ";
		}
		 
		return $where;
	}
	
	function getColorListing($filter, $limitstart = null, $limit = null){
		//        $jshopConfig = &JSFactory::getConfig();
		//        $lang = &JSFactory::getLang();
		$db =& JFactory::getDBO();
		if($limit > 0){
			$limit = " LIMIT ".$limitstart." , ".$limit;
		}else{
			$limit = "";
		}
		$where = $this->_getAllColorQueryForFilter($filter);
		 
		$query_filed = ""; $query_join = "";
		$query = "SELECT pr.* FROM `#__virtuemart_colors` AS pr "
		." WHERE 1 ".$where." ORDER BY pr.id ".$limit;
		$db->setQuery($query);
		//echo $query;
		return $db->loadObjectList();
	}
	
	function getColorSingle($id){
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__virtuemart_colors WHERE id ='{$id}'";
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	function getCountAllColors($filter){
		//        $lang = &JSFactory::getLang();
		$db =& JFactory::getDBO();
		//        $category_id = $filter['category_id'];
		$where = $this->_getAllColorQueryForFilter($filter);
	
		$query = "SELECT count(pr.id) FROM `#__virtuemart_colors` AS pr
		WHERE 1 ".$where;
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	function getColors(){
		$db = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		
		$query = "select * from #__virtuemart_colors ";
		$query .= " where `published`='1' ";
		$query .= " order by id ASC ";
		$db->setQuery($query);
		$rows_type=$db->loadObjectList();
		$options = array();
		for($i=0;$i<count($rows_type);$i++){
			$options[] = JHTML::_('select.option', $rows_type[$i]->id, $rows_type[$i]->name);
		}
		$dropdown_color = JHTML::_('select.genericlist', $options, 'color', 'class="inputbox" ', 'value', 'text', 0);
		
		return $dropdown_color;
	}
}
// No closing tag