/* Javscript Document  */
jQuery(document).ready(function() {
	jQuery(".moduletable-nav").find("li:last").css({background: "none"});		
	
	jQuery(".moduletable-nav .menu li").hover(function(){
		jQuery(this).find("ul:first").css({visibility: "visible",display: "none"}).fadeIn("slow");
		jQuery(this).parent().prev().addClass("active");
	},function(){
		jQuery(this).find("ul:first").css({visibility: "hidden"});
		jQuery(this).parent().prev().removeClass("active");
		console.log("3");
	});
	jQuery(".moduletable-nav li a").each(function(){
		if(this.href == document.location.href){
			jQuery(this).addClass("active");
		}
	});
});