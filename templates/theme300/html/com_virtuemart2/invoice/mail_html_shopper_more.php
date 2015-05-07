<?php
defined('_JEXEC') or die('');
//echo "==========================";

$userfileBT = $this->userfields['fields'];
$userfileST = $this->shipmentfields['fields'];
$normal_mail = array(3,4,5);
$regist_mail = array(6,7,8);

$shippingid = $this->orderDetails['details']['BT']->virtuemart_shipmentmethod_id;
?>

<p>
	<strong><?php echo JText::sprintf('COM_VIRTUEMART_MAIL_SHOPPER_NAME', $this->orderDetails['details']['BT']->title.' '.$this->orderDetails['details']['BT']->first_name.' '.$this->orderDetails['details']['BT']->last_name); ?></strong><br/>
	Email: <?php echo $this->orderDetails['details']['BT']->email; ?>
</p>
<p>
Thank you for making your online order with 99ms.com.sg. This is an acknowledgement from us that you have made the following order:
</p>
<p>
Order Number: <?php echo $this->orderDetails['details']['BT']->order_number; ?>
</p>

<p>
<?php
	foreach($this->orderDetails['items'] as $item) {
		$qtt = $item->product_quantity ;
		$_link =JURI::root().'index.php?option=com_virtuemart&view=productdetails&virtuemart_category_id=' . $item->virtuemart_category_id . '&virtuemart_product_id=' . $item->virtuemart_product_id;

		?>
		<a href="<?php echo $_link; ?>"><?php echo $item->order_item_name; ?></a> <br />
		Unit Price: <?php echo str_replace('$ ','$',$this->currency->priceDisplay($item->product_item_price)); ?> <br />
		Qty: <?php echo $qtt; ?><br />
		<?php 
		
		if (!empty($item->product_attribute)) {
				$arr_replace = array("Size: ","Color: ");
				$arr_search = array("Size","Color");
				$attribute = json_decode($item->product_attribute);
				foreach($attribute as $attr){
					echo str_replace($arr_search,$arr_replace,$attr)."<br />";
				}
			
		}
		?>
		<br />
<?php
	}
?>
</p>
<p>
Shipping to: <?php echo str_replace('</span>','</span> ',$this->orderDetails['shipmentName']); ?><br />
<?php if(in_array($shippingid,$normal_mail))
		echo "only (Unable To Track If Parcel Lost)<br />";
	else if(in_array($shippingid,$regist_mail))
		echo "(Comes With Tracking Number)<br />";
?>

Shipping Cost: <?php echo str_replace('$ ','$',$this->currency->priceDisplay($this->orderDetails['details']['BT']->order_shipment)); ?><br />
</p>

<p><strong>Total price:</strong>  <?php echo str_replace('$ ','$',$this->currency->priceDisplay($this->orderDetails['details']['BT']->order_total)); ?></p>

<?php if($this->orderDetails['details']['BT']->virtuemart_paymentmethod_id == 1){ ?>
<p>
You have chosen the payment method of ATM Transfer, please proceed to transfer the payment to one of the bank account below:<br /><br />
OCBC SAVING 558-722203-001.<br />
POSB/ DBS - POSB SAVING 030-96581-7.<br />
</p>

<p>
Once you have made the transfer, please inform us upon receiving the confirmation email.<br />
All ATM transfers must be accompanied with a snapshot (no scans allowed) of your transaction receipt.<br />
We reserve all rights to reject any orders without the snapshot. No refunds will be made as well.<br /><br />
</p>
<?php }
else{ ?>
<p><strong>You have chosen the payment method:</strong><?php echo str_replace("&nbsp"," ",$this->orderDetails['paymentName']); ?>
</p>
<?php } ?>
<br />
<p>
<?php if(!isset($this->orderDetails['details']['ST'])){ ?>
Delivery to Billing Address<br />
<?php }; ?>
Billing Address<br />
<?php echo $this->orderDetails['details']['BT']->first_name.' '.$this->orderDetails['details']['BT']->last_name; ?><br />
<?php echo $this->orderDetails['details']['BT']->address_1;?> <br />
<?php if(trim($userfileBT['unit_number_address']['value']) !='') echo $userfileBT['unit_number_address']['value'].'<br />'; ?> 
<?php echo $userfileBT['virtuemart_country_id']['value']; ?> <br />
Mobile No: <?php echo $userfileBT['phone_1']['value'];?> <br />
Other Tel No: <?php echo $userfileBT['phone_2']['value'];?> <br />
Postal Code: <?php echo $userfileBT['zip']['value'];?> <br />
</p>

<?php if(isset($this->orderDetails['details']['ST'])){ ?>
<br />
<p>
Shipping Address<br />
<?php echo $this->orderDetails['details']['ST']->first_name.' '.$this->orderDetails['details']['ST']->last_name; ?><br />
<?php echo $this->orderDetails['details']['ST']->address_1;?> <br />
<?php if(trim($userfileST['unit_number_address']['value']) !='') echo $userfileST['unit_number_address']['value'].'<br />'; ?> 
<?php echo $userfileST['virtuemart_country_id']['value']; ?> <br />
Mobile No: <?php echo $userfileST['phone_1']['value'];?> <br />
Other Tel No: <?php echo $userfileST['phone_2']['value'];?> <br />
Postal Code: <?php echo $userfileST['zip']['value'];?> <br />
</p>
<?php }; ?>

<p><br />
Sincerely,
<br />
Mummy's Secret
</p>
<?php //exit;