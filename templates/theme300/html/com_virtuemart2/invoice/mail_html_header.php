<?php
defined('_JEXEC') or die('Restricted access');
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="html-email">
    <tr>
    <td colspan="3">
	<img src="<?php  echo JURI::root() . $this-> vendor->images[0]->file_url ?>">
	<br/>
	<strong><?php echo JText::sprintf('COM_VIRTUEMART_MAIL_SHOPPER_NAME', $this->orderDetails['details']['BT']->title.' '.$this->orderDetails['details']['BT']->first_name.' '.$this->orderDetails['details']['BT']->last_name); ?></strong><br/>
    </td>
 </tr>
</table>

<p>Thank you for making your online order with MummySecret.com. This is an
acknowledgement from us that you have made the following order:</p>