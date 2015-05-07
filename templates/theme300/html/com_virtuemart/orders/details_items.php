<?php
/**
*
* Order items view
*
* @package	VirtueMart
* @subpackage Orders
* @author Oscar van Eijk, Valerie Isaksen
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: details_items.php 5836 2012-04-09 13:13:21Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
if (!class_exists( 'VmConfig' )) 
	require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'config.php');

VmConfig::loadConfig();
$productModel = VmModel::getModel('Product');

if($this->format == 'pdf'){
	$widthTable = '100';
	$widtTitle = '27';
} else {
	$widthTable = '100';
	$widtTitle = '49';
}

?>
<div class='order_items'>
<h4 class='title_sup'>Shopping Card</h4>
<table width="100%" cellspacing="0" cellpadding="0" border="1">
	<tr align="left" class="sectiontableheader">
		<!--<th align="left" width="5%"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_SKU') ?></th>-->
		<th align="center" >Name</th>
		<!--<th align="center" width="10%"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_STATUS') ?></th>-->
		<th align="center" width="140px" ><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PRICE') ?></th>
		<th align="center" width="140px">Quantity</th>
		<?php /* if ( VmConfig::get('show_tax')) { ?>
		<th align="right" width="10%" ><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_TAX') ?></th>
		  <?php } ?>
		<th align="right" width="11%"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_SUBTOTAL_DISCOUNT_AMOUNT') ?></th>
		*/ ?>
		<th align="center" width="140px"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_TOTAL') ?></th>
	</tr>
<?php

	$db = JFactory::getDBO();
	foreach($this->orderdetails['items'] as $item) {
	$product = $productModel->getProductSingle($item->virtuemart_product_id, true, false, true, false);
	$productModel->addImages($product);
	
		$query="Select * From #__virtuemart_colors where id=".$item->colorid ;
		$db->setQuery($query);
		$rows_state=$db->loadObjectList();
		$namecolor='';
		if(count($rows_state)) $namecolor=$rows_state[0]->name;
		
		$qtt = $item->product_quantity ;
		$_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_category_id=' . $item->virtuemart_category_id . '&virtuemart_product_id=' . $item->virtuemart_product_id);
?>
		<tr valign="top">
			<!--<td align="left">
				<?php  echo $item->order_item_sku; ?>
			</td>-->
			<td align="center">
				<?php
					echo $product->images[0]->displayMediaThumb('class="productImage"', false);
				?>
				<br />
				<a href="<?php echo $_link; ?>"><?php echo $item->order_item_name; ?></a>
				<?php

					if (!empty($item->product_attribute)) {
							if(!class_exists('VirtueMartModelCustomfields'))require(JPATH_VM_ADMINISTRATOR.DS.'models'.DS.'customfields.php');
							$product_attribute = VirtueMartModelCustomfields::CustomsFieldOrderDisplay($item,'FE');
						echo $product_attribute;
					}
					if (!empty($namecolor)) {
						echo '<div class="vm-customfield-cart">Color: '.$namecolor.'</div>';
					}
					if (!empty($item->sizename)) {
						echo '<div class="vm-customfield-cart">Size: '.$item->sizename.'</div>';
					}
				?>
			</td>
			<!--
			<td align="center">
				<?php echo $this->orderstatuses[$item->order_status]; ?>
			</td>
			-->
			<td align="center"   class="priceCol" >
			    <?php echo '<span >'.$this->currency->priceDisplay($item->product_item_price) .'</span><br />'; ?>
			</td>
			<td align="center" >
				<?php echo $qtt; ?>
			</td>
			<?php /* if ( VmConfig::get('show_tax')) { ?>
				<td align="right" class="priceCol"><?php echo "<span  class='priceColor2'>".$this->currency->priceDisplay($item->product_tax ,0, $qtt)."</span>" ?></td>
                                <?php } ?>
			<td align="right" class="priceCol" >
				<?php echo  $this->currency->priceDisplay( $item->product_subtotal_discount );  //No quantity is already stored with it ?>
			</td>
			*/ ?>
			<td align="center"  class="priceCol">
				<?php
				$item->product_basePriceWithTax = (float) $item->product_basePriceWithTax;
				$class = '';
				if(!empty($item->product_basePriceWithTax) && $item->product_basePriceWithTax != $item->product_final_price ) {
					echo '<span class="line-through" >'.$this->currency->priceDisplay($item->product_basePriceWithTax,0,$qtt) .'</span><br />' ;
				}

				echo $this->currency->priceDisplay(  $item->product_subtotal_with_tax ,0); //No quantity or you must use product_final_price ?>
			</td>
		</tr>

<?php
	}
?>
</table>
</div>
