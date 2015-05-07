<?php
/**
 *
 * Order detail view
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
 * @version $Id: details_order.php 5341 2012-01-31 07:43:24Z alatak $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$db = &JFactory::getDBO();
$order_number = JRequest::getVar("order_number");
$query = "Select * From #__virtuemart_paymentmethods_en_gb p
			where p.virtuemart_paymentmethod_id in (Select virtuemart_paymentmethod_id from #__virtuemart_orders where order_number='$order_number')";
$db->setQuery($query);
$payment = $db->loadObject();

$tmp1 = ceil(count($this->userfields['fields'])/2);
$userfields = array();
$i = 0;
foreach ($this->userfields['fields'] as $field){
	if($i++ < $tmp1)
	{
		$userfields[0][] = array($field['title'],$field['value']);
	}
	else
	{
		$userfields[1][] = array($field['title'],$field['value']);
	}
	
}

$tmp2 = ceil(count($this->shipmentfields['fields'])/2);
$shipmentfields = array();
$i = 0;
foreach ($this->shipmentfields['fields'] as $field){
	if($i++ < $tmp2)
	{
		$shipmentfields[0][] = array($field['title'],$field['value']);
	}
	else
	{
		$shipmentfields[1][] = array($field['title'],$field['value']);
	}
	
}
?>
<div class='order_detail'>
	<table width='100%' class='order'>
	
					<tr>
						<td class='lable_1'>Order Number:</td>
						<td width='270px'><?php echo $this->orderdetails['details']['BT']->order_number; ?></td>
						
						<td class='lable_2'>Shipment:</td>
						<td><?php echo $this->shipment_name; ?></td>
					<tr>
					<tr>
						<td class='lable_1'>Order Status:</td>
						<td><?php echo $this->orderstatuses[$this->orderdetails['details']['BT']->order_status]; ?></td>
						
						<td class='lable_2'>Payment:</td>
						<td><?php echo $payment->payment_name; ?></td>
					<tr>
					<tr>
						<td class='lable_1'>Order Date:</td>
						<td><?php echo vmJsApi::date($this->orderdetails['details']['BT']->created_on, 'LC4', true); ?></td>
						
						<td class='lable_2'>Comment:</td>
						<td><?php echo $this->orderdetails['details']['BT']->customer_note; ?></td>
					<tr>
					<tr>
						<td class='lable_1'>Last Updated:</td>
						<td><?php echo vmJsApi::date($this->orderdetails['details']['BT']->modified_on, 'LC4', true); ?></td>
						
						<td class='lable_2'>Amount:</td>
						<td><?php echo $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_total); ?></td>
					<tr>
		</tr>
	</table>
	
	<div class='billing_address'>
		<h4 class='title_sup'>Billing address</h4>
		<table border="0"><?php
		$num = count($userfields[0]);

	    for($i=0; $i < $num; $i++) {?>
			
				<tr>
					<td class="label_1"><?php echo $userfields[0][$i][0]; ?></td>
					<td class="value_1"><?php echo $userfields[0][$i][1]; ?></td>
					<?php
					if (isset($userfields[1][$i])):?>
					<td class="label_2"><?php echo $userfields[1][$i][0]; ?></td>
					<td class="value_2"><?php echo $userfields[1][$i][1]; ?></td>
					<?php else: 
						echo '<td colspan="2"></td>';
					 endif; ?>
				</tr>
			<?php
	    }
	    ?></table>
	</div>
	
	<div class='shipping_address'>
		<h4 class='title_sup'>Shipping address</h4>
		<table border="0"><?php
	    $num = count($shipmentfields[0]);

	    for($i=0; $i < $num; $i++) {?>
			
				<tr>
					<td class="label_1"><?php echo $shipmentfields[0][$i][0]; ?></td>
					<td class="value_1"><?php echo $shipmentfields[0][$i][1]; ?></td>
					<?php
					if (isset($shipmentfields[1][$i])):?>
					<td class="label_2"><?php echo $shipmentfields[1][$i][0]; ?></td>
					<td class="value_2"><?php echo $shipmentfields[1][$i][1]; ?></td>
					<?php else: 
						echo '<td colspan="2"></td>';
					 endif; ?>
				</tr>
			<?php
	    }
	    ?></table>
	</div>
</div>