<?php
ini_set('display_errors',1);
/**
 *
 * Show Confirmation message from Offlien Payment
 *
 * @package	VirtueMart
 * @subpackage
 * @author Valerie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 3217 2011-05-12 15:51:19Z alatak $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
if (!class_exists( 'CurrencyDisplay' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'currencydisplay.php');
$this->addHelperPath(JPATH_VM_ADMINISTRATOR.DS.'helpers');
$productModel = VmModel::getModel('product');
$currency = CurrencyDisplay::getInstance( );

$order_number = JRequest::getVar('on',0);
$db = &JFactory::getDBO();
$query = "Select * from #__virtuemart_orders where order_number='$order_number'";
$db->setQuery($query);
$payment = $db->loadObject();
/*echo "<h3>" . $this->paymentResponse . "</h3>";
if ($this->paymentResponseHtml) {
    echo "<fieldset>";
    echo $this->paymentResponseHtml;
    echo "</fieldset>";
}
*/
?>
<div class="vm_order">
	<div class='header_order'>
		<table width='100%'>
			<tr>
				<td class='left'></td>
				<td class='middle'>
					<?php
						echo "<h3>".JText::_('COM_VIRTUEMART_CART_ORDERDONE_THANK_YOU')."</h3>";
					?> 
				</td>
				<td class='right'></td>
			</tr>
		</table>
	</div>
	<div class='content'>
		<table>
			<tr>
				<td class='label'>Order Number:</td>
				<td><?php echo $order_number; ?> </td>
			</tr>
			
			<tr>
				<td class='label'>Amount:</td>
				<td><?php echo $currency->priceDisplay($payment->order_total); ?> </td>
			</tr>
		</table>
		
		<hr class='separate' />
		
		<div class='control'>
			<a href='<?php echo JRoute::_('index.php?option=com_virtuemart&view=orders&layout=details&order_number='.$order_number.'&Itemid=538'); ?>' class='order_history'>Order History</a>
			<a href='<?php echo JRoute::_('index.php?option=com_virtuemart&view=orders&layout=list&Itemid=538',false); ?>' class='orders_history'>View Orders History</a>
		</div>

	</div>
</div>