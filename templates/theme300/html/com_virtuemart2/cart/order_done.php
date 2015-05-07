<?php
defined('_JEXEC') or die('');

/**
*
* Template for the shopping cart
*
* @package	VirtueMart
* @subpackage Cart
* @author Max Milbers
*
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*/
$order_amount = JRequest::getVar('order_amount',null);
$order_number = JRequest::getVar('order_number',null);

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
	<div class='content' <?php echo (($order_number===null) ? "style='min-height:600px'" : ''); ?> >
		<?php
			if($order_number === null)
			{	
				echo $this->html;
			}
			else {
		?>
		<table>
			<tr>
				<td class='label'>Order Number:</td>
				<td><?php echo $order_number; ?> </td>
			</tr>
			
			<tr>
				<td class='label'>Amount:</td>
				<td><?php echo $order_amount; ?> </td>
			</tr>
		</table>
		
		<hr class='separate' />
		
		<div class='control'>
			<a href='<?php echo JRoute::_('index.php?option=com_virtuemart&view=orders&layout=details&order_number='.$order_number.'&Itemid=538'); ?>' class='order_history'>Order History</a>
			<a href='<?php echo JRoute::_('index.php?option=com_virtuemart&view=orders&layout=list&Itemid=538',false); ?>' class='orders_history'>View Orders History</a>
		</div>
		<?php } ?>
	</div>
</div>