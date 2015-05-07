var $j = jQuery.noConflict();
$j(document).ready(function() {
	$j('#products').slides({
		preload: true,
		preloadImage: false,
		effect: 'fade',
		crossfade: true,
		slideSpeed: 350,
		fadeSpeed: 500,
		generateNextPrev: false,
		generatePagination: false
	});	
	$j('.jqzoom').jqzoom({
		zoomType: 'reverse',
		lens:true,
		zoomWidth:300,
		zoomHeight:230,
		preloadImages: false,
		title:false,
		alwaysOn:false
	});
	$j("#carousel").jcarousel();

});	
$j(document).ready(function() {
// Tabs Fly-page		
	if ($j('.desc2 > div').hasClass('video')) {
		$j('.tab7').css({display:'block'})
	} else {
		$j('.tab7').css({display:'none'})	
};

//accordion begin
	$j("#accordion dt").eq(0).addClass("active");
	$j("#accordion dd").eq(0).show();
	$j("#accordion dt").click(function(){
		$j(this).next("#accordion dd").slideToggle("slow")
		.siblings("#accordion dd:visible").slideUp("slow");
		$j(this).toggleClass("active");
		$j(this).siblings("#accordion dt").removeClass("active");
		return false;
	});

	$j(function(){
		try {
		$j("#tabs").tabs({
		fx: { opacity: 'toggle' },			  
		cookie: {
		// store cookie for a day, without, it would be a session cookie
		expires: 1
		}
		});
		}catch(err){}
	});

});
 $j(window).load(function() {
	$j('.tab_container , .Fly-tabs , .share1 , .share , .checkout-button-top , #products_example , .box-prod , dl#accordion').css('visibility', 'visible');
	$j('.checkout-button-top').css({visibility:'visible',display:'block'});
});	
