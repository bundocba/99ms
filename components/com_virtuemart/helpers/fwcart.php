<?php

defined('_JEXEC') or die('Restricted access');

abstract class fwcartHelper{
	public static function checkStock($data){
		$productid = intval($data['productId']); 
		$colorid = intval($data['color']);
		$sizename = addslashes($data['size']);
		$parent_id=intval($parent_id);

		
		$query = "SELECT quantity,color_ordered FROM #__virtuemart_quantity ";
		$where[]= " productid = $productid ";
		if(!empty($colorid)){
			$where[]= " colorid = $colorid ";
		}
		if(!empty($sizename)){
			$where[]= " sizename = '$sizename' ";
		}
		$where = ' where '.implode (' and ', $where);
		$query.= $where;
		
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$row = $db->loadAssoc();
		if(empty($row)) return 0;
		return $row['quantity'] - $row['color_ordered'];
	
	}
	public static function getadditionalImages($productid){
		$db = JFactory::getDBO();
		$productid = intval($productid);
		$query = "	SELECT `id`, `productid`, `colorid`, `name`, `description`, `img`, `type`, `ordering`, `published` 
					FROM `#__virtuemart_image` WHERE productid = $productid ORDER BY `colorid`";
			
		$db->setQuery($query);
		return $db->loadAssocList();
	}
}