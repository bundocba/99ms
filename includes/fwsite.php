<?php 
class fwSite{
	// Begin Thumb
	function getThumb($file_name = 'abc.jpg', $width ='230', $height ='230',$dir_name = 'product')
	{
		return JURI::root()."mythumb/$dir_name/$width/$height/$file_name";
	}
	function getProductThumb($file_name = 'abc.jpg', $width ='230', $height ='230')
	{	
		$file_name=str_replace('images/stories/virtuemart/product/', '', $file_name);
		return JURI::root()."mythumb/product/$width/$height/$file_name";
	}
	function getCategoryThumb($file_name = 'abc.jpg', $width ='230', $height ='230')
	{
		return JURI::root()."mythumb/category/$width/$height/$file_name";
	}
	// End Thumb
	
	// Begin getCustomFilter Virtuemart
	function getMaxMinPrice($max_min = 'MAX'){
		$db = JFactory::getDbo();
		if($max_min != 'MAX'){
			$max_min = 'MIN';
		}
		$query = "SELECT $max_min(product_price) AS maxPrice FROM #__virtuemart_products as pd LEFT JOIN  #__virtuemart_product_prices as pp ON pd.virtuemart_product_id = pp.virtuemart_product_id;";
		$db->setQuery($query);
		if($max_min == 'MAX'){
			return ceil($db->loadResult());
		}
		return floor($db->loadResult());
	}
	function getCusomFieldSearch(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array("DISTINCT  `cf`.`virtuemart_customfield_id`","`ct`.`virtuemart_custom_id`","`ct`.`custom_title`", "`cf`.`custom_value`"));
		$query->from("#__virtuemart_product_customfields as cf");
		$query->join('left', '`#__virtuemart_customs` as `ct` ON `ct`.`virtuemart_custom_id` = `cf`.`virtuemart_custom_id`');
		//$query->where(" `id`='".$id."' ");
		$query->order('custom_value ASC');
	
		$db->setQuery($query);
		$list= $db->loadObjectList();
		$result=array();
		if(!empty($list)){
			foreach($list as $item){
				$result[$item->virtuemart_custom_id.'|||'.$item->custom_title][$item->custom_value]=$item;
			}
		}

		return $result;
	}

	function getfwCustomColor(){
		$db = JFactory::getDBO();
		$query = "SELECT c.* FROM #__virtuemart_image as i LEFT JOIN  #__virtuemart_colors as c ON c.id = i.colorid group by colorid";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getfwCustomCategory(){
		$db = JFactory::getDBO();
		$query = "SELECT virtuemart_category_id,category_name FROM #__virtuemart_categories_en_gb";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	
	function getfwCustomByID($productid){
		$id=(int)$productid;
		$db = JFactory::getDBO();
		$query = "SELECT fwquantity.*, cl.name as colorname, cl.code as colorcode FROM #__virtuemart_quantity as fwquantity LEFT JOIN  #__virtuemart_colors as cl ON fwquantity.colorid = cl.id WHERE productid = $productid";
		$db->setQuery($query);
		$result= $db->loadObjectList();
		$list= array();
		if(!empty($result)){
			foreach($result as $row){
			$list[$row->sizename][] = $row;
			}
		}
		return $list;
	}

	function getLinkFilter($get){

		if(!empty($get)){

			$link = JURI::Root().'index.php?option=com_customfilters&view=products';
			if(!empty($_GET['virtuemart_category_id'])&& !empty($_GET['virtuemart_category_id'][0])){
				$link.='&virtuemart_category_id[0]='.$_GET['virtuemart_category_id'][0];
			}
			if(!empty($_GET['price_from'])){
				$link.='&price_from='.$_GET['price_from'];
			}
			if(!empty($_GET['price_to'])){
				$link.='&price_to='.$_GET['price_to'];
			}
			if(!empty($_GET['colors'])){
				$link.='&colors='.$_GET['colors'];
			}
			return $link;
		}
		return JURI::Root();
	}
	// End getCustomFilter Virtuemart
}
