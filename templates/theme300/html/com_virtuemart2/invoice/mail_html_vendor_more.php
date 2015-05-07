<?php
defined('_JEXEC') or die('');

?>
<p><strong>Payment method:</strong><br />
<?php echo str_replace("&nbsp"," ",$this->orderDetails['paymentName']); ?>
</p>