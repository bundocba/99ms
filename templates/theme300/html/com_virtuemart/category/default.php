<?php 
/**
*
* Show the products in a category
*
* @package	VirtueMart
* @subpackage
* @author RolandD
* @author Max Milbers
* @todo add pagination
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default.php 5007 2011-12-10 00:05:41Z electrocity $
*/
//vmdebug('$this->category '.$this->category->category_name);
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHTML::_( 'behavior.modal' );
/* javascript for list Slide
  Only here for the order list
  can be changed by the template maker
*/
$js = "
jQuery(document).ready(function () {
	jQuery('.orderlistcontainer').hover(
		function() { jQuery(this).find('.orderlist').stop().show()},
		function() { jQuery(this).find('.orderlist').stop().hide()}
	)
});
";

$document = JFactory::getDocument();
$document->addScriptDeclaration($js);
$button_name ='';
?>
<?php 
if ($this->category->category_name) 
{ ?>
	<h1 class="browse-view"><span><span><?php echo $this->category->category_name; ?></span></span></h1>
	
<?php } ?>

<?php /* if ($this->category->category_description) { ?>
<div class="category_description">
	<?php echo $this->category->category_description ; ?>
</div>
<?php } ?>
<?php

if ( VmConfig::get('showCategory',1) && $this->search ==null && $this->category->category_name) {
	if ($this->category->haschildren) {

		// Category and Columns Counter
		$iCol = 1;
		$iCategory = 1;

		// Calculating Categories Per Row
		$categories_per_row = 4;
		$category_cellwidth = ' width'.floor ( 100 / $categories_per_row );

		// Separator
		$verticalseparator = " vertical-separator";
		?>

		<div class="category-view pad-bot">

		<?php // Start the Output
		if(!empty($this->category->children)){
		foreach ( $this->category->children as $category ) {

			// Show the horizontal seperator
			if ($iCol == 1 && $iCategory > $categories_per_row) { ?>
			<div class="horizontal-separator"></div>
			<?php }

			// this is an indicator wether a row needs to be opened or not
			if ($iCol == 1) { ?>
			<div class="row">
			<?php }

			// Show the vertical seperator
			if ($iCategory == $categories_per_row or $iCategory % $categories_per_row == 0) {
				$show_vertical_separator = ' ';
			} else {
				$show_vertical_separator = $verticalseparator;
			}

			// Category Link
			$caturl = JRoute::_ ( 'index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id );

				// Show Category ?>
				<div class="category floatleft<?php echo $show_vertical_separator ?>">
					<div class="spacer">
						<h2>
							<a href="<?php echo $caturl ?>" title="<?php echo $category->category_name ?>">
							<div class="category-border">
							<?php // if ($category->ids) {
								echo $category->images[0]->displayMediaThumb("",false);
							//} ?>
							</div>
							<div class="category-title"><?php echo $category->category_name ?></div>
							</a>
						</h2>
					</div>
				</div>
			<?php
			$iCategory ++;

		// Do we need to close the current row now?
		if ($iCol == $categories_per_row) { ?>
		<div class="clear"></div>
		</div>
			<?php
			$iCol = 1;
		} else {
			$iCol ++;
		}
	}
	}
	// Do we need a final closing row tag?
	if ($iCol != 1) { ?>
		<div class="clear"></div>
		</div>
	<?php } ?>
	</div>

<?php }
} */
?>

<?php
// Show child categories
if (!empty($this->products)) {
						if (!empty($this->keyword)) {
							?>
							<h3>Search</h3>
						<?php }	?>
					
						<?php if ($this->search == null ) { ?>
							<?php if ($this->category->category_description ): ?>
							<div class="category_description">
								<?php echo $this->category->category_description ; ?>
							</div>
							<?php endif; ?>
						<?php } ?>
					
<?php } ?>
					
 		<?php 
	
		if ($this->search !==null ) { ?>
		
			<?php if (empty($this->products)) { ?>
				<h2>Search</h2>
			<?php } ?>
			
		    <form action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&limitstart=0&virtuemart_category_id='.$this->category->virtuemart_category_id ); ?>" method="get">

		    <!--BEGIN Search Box --><div class="virtuemart_search">
		    <?php echo $this->searchcustomvalues ?>
		    <input name="keyword" class="inputbox" type="text" size="20" value="<?php echo $this->keyword ?>" />
		    <input type="submit" value="<?php echo JText::_('COM_VIRTUEMART_SEARCH') ?>" class="button" onclick="this.form.keyword.focus();"/>
		    </div>
				    <input type="hidden" name="search" value="true" />
				    <input type="hidden" name="view" value="category" />
					 <input type="hidden" name="option" value="com_virtuemart" />
			

		</form>
		<?php if (empty($this->products)) { ?>
		<div class='no_result'>No result match your keywords</div>
		<?php } ?>
		<!-- End Search Box -->
		<?php } 
		
		?>
		
		
<?php if (!empty($this->products)) { ?>

			<div class="orderby-displaynumber">
				<div class="width90">
					<?php echo $this->orderByList['orderby']; ?>
					<?php //echo $this->orderByList['manufacturer']; ?>
					<div class="Results">
						<div class="floatleft display-number"><span><?php echo $this->vmPagination->getResultsCounter();?></span><?php echo $this->vmPagination->getLimitBox(); ?></div>
					</div>
				</div>
			<div class="clear"></div>
			</div>
			 <!-- end of orderby-displaynumber -->
	<div id="tabs" class="tabs-position">
		<!--	<ul class="tabs">
				<li class="first"><a href="#tabs-1">&nbsp;</a></li>
				<li class="second"><a href="#tabs-2">&nbsp;</a></li>
				<li class="three"><a href="#tabs-3">&nbsp;</a></li>
			</ul> -->
	
	<div id="bottom-pagination" class="pag-bot">
	<div style='display:none;'>
	<?php echo $this->vmPagination->getPagesLinks(); ?>
	</div>
	</div>	
	
	<div class="tab_container">
	
	<div id="tabs-3" class="tab_content">
			<div id="product_list3">
					<?php // Category and Columns Counter
					$counterr = 0;
					$counter = 0;
					$iBrowseCol = 1;
					$iBrowseProduct = 1;
					
					// Calculating Products Per Row
					$BrowseProducts_per_row = 4;
					$Browsecellwidth = ' width'.floor ( 100 / $BrowseProducts_per_row );
					
					// Separator
					$verticalseparator = " vertical-separator";
					?>
					<div class="browse-view">
					
					<?php // Start the Output
					$col = 1;
					foreach ( $this->products as $product ) {
					
					/*$product->link = JRoute::_("index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id={$product->virtuemart_product_id}&virtuemart_category_id={$product->virtuemart_category_id}&Itemid=".JRequest::getVar('Itemid'));*/
					$class_col = $col++%4;
						// Show the horizontal seperator
						if ($iBrowseCol == 1 && $iBrowseProduct > $BrowseProducts_per_row) { ?>
						<div class="horizontal-separator2"></div>
						<?php }
					
						// this is an indicator wether a row needs to be opened or not
						if ($counterr%2) $cls = "first"; else $cls = "second";
						if ($counter%2) $cls = "even"; else $cls = "odd";
						if ($iBrowseCol == 1) { ?>
						
						<div class="row <?php echo $cls; ?>">
						<?php }
					
						// Show the vertical seperator
						if ($counter%2) $cls = "even"; else $cls = "odd";
						if ($iBrowseProduct == $BrowseProducts_per_row or $iBrowseProduct % $BrowseProducts_per_row == 0) {
							$show_vertical_separator = $verticalseparator;;
						} else {
							$show_vertical_separator = $verticalseparator;
						}
					
							// Show Products ?>
							<div class="product1 floatleft <?php echo $cls . $show_vertical_separator." col_".$class_col; ?>">
								<div class="spacer column2">
									<div class="col-1">
										<div class="browseProductImageContainer">
											<?php
												$stockhandle = VmConfig::get('stockhandle', 'none');
												if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($product->product_in_stock - $product->product_ordered) < 1) {
													echo '<div class="sold_out"></div>';
												}
											?>
											<table width='100%' height='100%'>
												<tr><td align='center' valign='middle' width='100%'>
											<?php /** @todo make image popup */
													echo "<a href='{$product->link}'>".$product->images[0]->displayMediaThumb('class="browseProductImage"',false)."</a>";
											?>
												</td></tr>
											</table>
										</div>
											<div class="rating">
												<!-- The "Average Customer Rating" Part -->
												<?php /*
												if ($this->showRating || !empty($product->rating)) {
													$maxrating = VmConfig::get('vm_maximum_rating_scale',5);
													if(!isset($product->rating->rating))
														$product->rating->rating = 0;
													$ratingwidth = ( $product->rating->rating * 100 ) / $maxrating;//I don't use round as percetntage with works perfect, as for me
												?>
													<span class="vote">
														<span title="" class="vmicon ratingbox" style="display:inline-block;">
															<span class="stars-orange" style="width:<?php echo $ratingwidth;?>%">
															</span>
														</span>
													</span>
												<?php
												} */
												?>
											</div>
									</div>
									<div class="col-2">
										<div class="title-indent">
											<h2><?php echo JHTML::link($product->link, $product->product_name); ?></h2>
												<div style="display:none;">
												<?php // Product Short Description
												if(!empty($product->product_s_desc)) { ?>
												<?php echo shopFunctionsF::limitStringByWord($product->product_s_desc, 100, '...') ?>
												<?php } ?>
												</div>
												<?php if (!VmConfig::get('use_as_catalog') and !(VmConfig::get('stockhandle','none')=='none') && (VmConfig::get ( 'display_stock', 1 )) ){?>
												<div class="padding-stock" style="display:none;">
													<span class="vmicon vm2-<?php echo $product->stock->stock_level ?>" title="<?php echo $product->stock->stock_tip ?>"></span>
													<span class="stock-level"><?php echo JText::_('COM_VIRTUEMART_STOCK_LEVEL_DISPLAY_TITLE_TIP') ?></span>
												</div>
												<?php }?>
						
											<div class="detal" style="display:none;">
											<?php // Product Details Button
											/*echo JHTML::link($product->link, JText::_('COM_VIRTUEMART_PRODUCT_DETAILS'), array('title' => $product->product_name,'class' => 'product-details'));*/
											?>
											</div>
										</div>
										<div class="product-price marginbottom12" id="productPrice<?php echo $product->virtuemart_product_id ?>">
											<?php
											if ($this->show_prices == '1') {
												if( $product->product_unit && VmConfig::get('vm_price_show_packaging_pricelabel')) {
													echo "<strong>". JText::_('COM_VIRTUEMART_CART_PRICE_PER_UNIT').' ('.$product->product_unit."):</strong>";
												}
												echo $this->currency->createPriceDiv('salesPrice','',$product->prices);
												//print_r($product->prices);
												if ($product->prices["discountAmount"] > 0) {
													echo $this->currency->createPriceDiv('priceWithoutTax','',$product->prices);
												}
												//echo $this->currency->createPriceDiv('discountAmount','COM_VIRTUEMART_PRODUCT_DISCOUNT_AMOUNT',$product->prices);
												
												//echo $this->currency->createPriceDiv('salesPrice','COM_VIRTUEMART_PRODUCT_SALESPRICE',$product->prices);
												//echo $this->currency->createPriceDiv('priceWithoutTax','COM_VIRTUEMART_PRODUCT_SALESPRICE_WITHOUT_TAX',$product->prices);
												
												//echo $this->currency->createPriceDiv('variantModification','COM_VIRTUEMART_PRODUCT_VARIANT_MOD',$product->prices);
												//echo $this->currency->createPriceDiv('basePriceWithTax','COM_VIRTUEMART_PRODUCT_BASEPRICE_WITHTAX',$product->prices);
												//echo $this->currency->createPriceDiv('discountedPriceWithoutTax','COM_VIRTUEMART_PRODUCT_DISCOUNTED_PRICE',$product->prices);
												//echo $this->currency->createPriceDiv('salesPriceWithDiscount','COM_VIRTUEMART_PRODUCT_SALESPRICE_WITH_DISCOUNT',$product->prices);
												//echo $this->currency->createPriceDiv('taxAmount','COM_VIRTUEMART_PRODUCT_TAX_AMOUNT',$product->prices);
											} ?>
										</div>
										
										<div class='btn_detail'>
											<?php // Product Details Button
											echo JHTML::link($product->link, 'Detail', array('title' => $product->product_name,'class' => 'product_detail'));
											?>
										</div>
									
									</div>
								<div class="clear"></div>
								</div>
							</div>
							
						<?php
						$iBrowseProduct ++;
						$counterr++;
					
						// Do we need to close the current row now?
						if ($iBrowseCol == $BrowseProducts_per_row) { ?>
						<div class="clear"></div>
						</div>
							<?php
							$iBrowseCol = 1;
						} else {
							$iBrowseCol ++;
							$counter++;
						}
					}
					// Do we need a final closing row tag?
					if ($iBrowseCol != 1) { ?>
						<div class="clear"></div>
						</div>
					<?php
					}
					?>
					
					
				</div>
			</div>
	</div>
	
	<div id="bottom-pagination"><?php echo $this->vmPagination->getPagesLinks(); ?></div>	
</div>
</div>
	<?php // Show child categories
	}
	?>
<script type='text/javascript'>
if(jQuery("html.ie7").length == 1)
{
	jQuery(".browse-view > .row").prepend("<span id='fixie7'>&nbsp</span>");
}
</script>