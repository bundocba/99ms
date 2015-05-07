<?php if(!empty($this->product->fwquantity)):?>
<div class="fwcustomfields-wrap" id="fwcustomfields-wrap">
	<div class="jselect-wrap jsize-wrap jjsize-wrap">
		<input type="hidden" name="size" id="txtsize" value="" />
		<input type="hidden" name="color" id="txtcolor" value="" />
		<label class="size-label">Size: </label>
		<ul class="jsize">
		<?php foreach($this->product->fwquantity as $size=>$field):
				$class='';
				if($size=='No size') $class='nosize';
		?>
			<li class="<?php echo $class?>"><a href="javascript:void(null)" class="jselect-item jsize-item"><?php echo $size?></a>
				<div class="jjcolor-wrap">
					<label>Color: </label> 
					<ul class="jcolor">
						<?php foreach($field as $item):?>
							<li><a href="javascript:void(null)" class="jcolor-item" rel="<?php echo $item->colorid?>" style="background:#<?php echo $item->colorcode?>"></a></li>
						<?php endforeach?>
					</ul>
				</div>
			</li>
			<?php if($size == 'No size'):?>
				<script>
				jQuery(function(){
					jQuery(".nosize > a").click();
					jQuery(".size-label").hide();
					jQuery(".nosize  .jsize-item").css('visibility','hidden');
				})
				</script>
			<?php endif;?>
		<?php endforeach;?>
		</ul>
	</div>
</div>
<div class="clr h20"></div>
<script>
jQuery(".jsize-item").click(function(){
	var elem=jQuery(this);
	var parent = elem.parents('li').first();
	jQuery('.jjcolor-wrap').hide();
	jQuery("#txtsize").val('');
	jQuery("#txtcolor").val('');
	if(elem.hasClass('active')){
		elem.removeClass('active');
	}
	else{
		jQuery('.jsize-item').removeClass('active');
		elem.addClass('active');
		jQuery("#txtsize").val(elem.text());
		elem.parents('li').first().find('.jjcolor-wrap').show();
	}
})
jQuery(".jcolor-item").click(function(){
	var elem=jQuery(this);
	jQuery('.jcolor-item').removeClass('active');
	jQuery("#txtcolor").val('');
	if(!elem.hasClass('active')){
		elem.addClass('active');
		jQuery("#txtcolor").val(elem.attr('rel'));
	}
})


</script>
<style>
ul.jsize li a.active {
    color: #DD9C19;
}
ul.jsize, ul.jsize li, .fwcustomfields-wrap label {
    color: #000000;
    display: inline-block;
    font-size: 14px;
    font-weight: bold;
}
.jjsize-wrap {
    height: 50px;
    position: relative;
    width: 200px;
}
.jjsize-wrap .jsize .jjcolor-wrap {
    display: none;
    left: 0px;
    position: absolute;
    top: 20px;
    width: 100%;
}
.jjsize-wrap .jsize ul.jcolor {
    left: 30px;
    position: absolute;
    top: 2px;
}
.jjsize-wrap .jsize ul.jcolor li {
    white-space: nowrap;
}
</style>
<?php endif;?>