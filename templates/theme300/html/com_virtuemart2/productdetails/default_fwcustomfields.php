<?php if(!empty($this->product->fwquantity)):?>
<input type="hidden" name="color" id="txtcolor" value="" />
<div class="jjcolor-wrap">
    <ul class="jcolor">
        <?php foreach($this->product->fwquantity as $key=>$value):?>
            <li><a href="javascript:void(null)" class="jcolor-item" rel="<?php echo $value->colorid?>" style="background:#<?php echo $value->colorcode?>"><?php echo $value->colorid;?></a></li>
        <?php endforeach;?>
        	<li><a href="javascript:void(null)" class="stamp_color">stamp_color</a></li>
    </ul>
</div>
<script>
jQuery(".jcolor-item").click(function(){
	var elem=jQuery(this);
	jQuery('.jcolor-item').parent().removeClass('active');
	jQuery("#txtcolor").val('');
	if(!elem.parent().hasClass('active')){
		elem.parent().addClass('active');
		jQuery("#txtcolor").val(elem.attr('rel'));
	}
});
</script>
<?php endif;?>