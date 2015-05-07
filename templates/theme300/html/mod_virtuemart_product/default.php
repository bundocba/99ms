<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$col= 1 ;
$pwidth= ' width'.floor ( 100 / $products_per_row );
if ($products_per_row > 1) { $float= "floatleft";}
else {$float="center";}
?>
<div class="vmgroup<?php echo $params->get( 'moduleclass_sfx' ) ?>">

<?php if ($headerText) { ?>
	<div class="vmheader"><?php echo $headerText ?></div>
<?php }
if ($display_style =="div") { ?>
<?php 
$last = count($products)-1;

?>
<ul id="vmproduct" class="vmproduct<?php echo $params->get('moduleclass_sfx'); ?>">
 <li class="item">
  <?php 
  $k = 1;
  foreach ($products as $product) : ?>
 <div class="product-box spacer item_<?php echo ($k%4);?>">
 
 <?php $k++; if ($show_img) { ?> 
    <div class="browse_Image">
		
	<?php
		$stockhandle = VmConfig::get('stockhandle', 'none');
		if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($product->product_in_stock - $product->product_ordered) < 1) {
			echo '<div class="sold_out"></div>';
		}
	 
		 /* if ($product->prices['discountAmount']>0)
		 <div class="sale"></div>
		 */ 
		?>
		<table width='100%' height='100%'>
			<tr><td valign='middle' align='center' width='100%'>
			<?php
			if (!empty($product->images[0]) )
					$image = $product->images[0]->displayMediaThumb('class="browseProductImage featuredProductImage" border="0"',false) ;
				else $image = '';
					echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id),$image,'class="img2"');
			?>
			</td></tr>
		</table>
	</div>
		<?php } ?>
        <div class="fleft">
		<div class="cat">
			<?php echo JHTML::link(JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$product->virtuemart_category_id), $product->category_name); ?>
		</div>
		<?php  if ($show_title) { ?>
		<div class="Title">
			<?php echo JHTML::link(JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id), $product->product_name, array('title' => $product->product_name)); ?>
		</div>
	<?php } ?>	
	<?php if ($show_desc) { ?>
				<div class="description">
					<?php echo shopFunctionsF::limitStringByWord($product->product_s_desc, $row, '...') ?>
				</div>
			<?php } ?>	
         <div class="wrapper">   
		<?php if ($show_price) { ?>	
			<div class="Price">
			
			<?php
				
				$sale = $currency->createPriceDiv('priceWithoutTax','',$product->prices,true);
				$discount = $currency->createPriceDiv('discountAmount','',$product->prices,true);
			
					//if ($product->prices['salesPrice']>0)
						echo '<span class="sales">' . $currency->createPriceDiv('salesPrice','',$product->prices,true) . '</span>';
					if ($product->prices['priceWithoutTax']>0) 
						echo '<span class="WithoutTax">' . $currency->createPriceDiv('priceWithoutTax','',$product->prices,true) . '</span>';
					if ($product->prices['discountAmount']>0) 
					echo '<span class="discount">' . $currency->createPriceDiv('discountAmount','',$product->prices,true) . '</span>';
					?>
					<?php /*?><?php 
					if ((round((substr($discount,1)/substr($sale,1)),2)*100)>0) { ?>
					<span><?php  echo round((substr($discount,1)/substr($sale,1)),2)*100;?>% off</span>
					<?php } ?><?php */?>
						
			</div>
			<?php } ?>
			<?php if ($show_addtocart) echo mod_virtuemart_product::addtocart($product);?>
			<?php if ($show_det) { ?>
			<div class="Details">
			<?php echo JHTML::link(JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id), 'Detail'); ?><?php ?> 
			</div>
			<?php } ?>
			<div class='clear'></div>
			</div>
        </div> 
    </div>
		<?php
            if ($col == $products_per_row && $products_per_row && $last) {
                echo "</li><li class='items'>";
                $col= 1 ;
            } else {
                $col++;
            }
			$last--;
            endforeach; ?>
	</li>
</ul>
<?php
} else {
JHTML::script( 'modules/mod_virtuemart_product/js/jquery.anythingslider.js' );
JHTML::script( 'modules/mod_virtuemart_product/js/jquery.easing.1.3.js' );
?>
<?php 
$last = count($products)-1;
?>
<ul id="slider" class="vmproduct">
 <li>
  <?php foreach ($products as $product) : ?>
 <div class="product-box spacer">
 <?php if ($show_img) { ?>
    <div class="browseImage">
			<?php
			if (!empty($product->images[0]) )
					$image = $product->images[0]->displayMediaThumb('class="browseProductImage featuredProductImage" border="0"',false) ;
				else $image = '';
					echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id),$image);
			?>
		</div>
		<?php } ?>
		 <?php if ($show_title) { ?>
            <div class="Title">
				<?php echo JHTML::link(JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id), $product->product_name, array('title' => $product->product_name)); ?>
			</div>
		<?php } ?>	
		<?php if ($show_desc) { ?>
			<div class="description">
				<?php echo shopFunctionsF::limitStringByWord($product->product_s_desc, $row, '...') ?>
			</div>
		<?php } ?>	
		<?php if ($show_price) { ?>	
			<div class="Price">
			<?php
					if ($product->prices['salesPrice']>0)
						echo '<span class="sales">' . $currency->createPriceDiv('salesPrice','',$product->prices,true) . '</span>';
					if ($product->prices['priceWithoutTax']>0) 
						echo '<span class="WithoutTax">' . $currency->createPriceDiv('priceWithoutTax','',$product->prices,true) . '</span>';
					if ($product->prices['discountAmount']>0) 
					echo '<span class="discount">' . $currency->createPriceDiv('discountAmount','',$product->prices,true) . '</span>';
			?>			
			</div>
			<?php } ?>
            <div class="wrapper-slide">
			<?php if ($show_addtocart) echo mod_virtuemart_product::addtocart($product);?>
			<?php if ($show_det) { ?>
			<div class="Details">
			<?php echo JHTML::link(JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id), JText::_('TM_DETAILS')); ?><?php ?> 
			</div>
			<?php } ?>
			</div>
    	</div>
		<?php
            if ($col == $products_per_row && $products_per_row && $last) {
                echo "</li><li>";
                $col= 1 ;
            } else {
                $col++;
            }
			$last--;
            endforeach; ?>
	</li>
</ul>
<?php }
	if ($footerText) : ?>
	<div class="vmfooter <?php echo $params->get( 'moduleclass_sfx' ) ?>">
		 <?php echo $footerText ?>
	</div>
<?php endif; ?>
</div>