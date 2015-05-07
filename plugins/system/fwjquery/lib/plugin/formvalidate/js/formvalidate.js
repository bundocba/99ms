(function($) {
$.fn.formvalidate = function(settings) {
	$(this).each(function() {
		var self = $(this);
		var labels = self.find(".formvalidate-label");
		var fields = self.find(".formvalidate-field");
		
		self.submit(function(e) {
			e.preventDefault();
			var flag = true;
			fields.each(function() {
				var field = $(this).find('input, textarea');
				
				var isRequired = field.data('required');
				if(isRequired === true) {
					if(field.val() == "") {
						var msg = $(this).find("small").html();
						flag = false;
						alert(msg);
					}
				}
				
				var isEmail = field.data('email');
				if(isEmail === true) {
					
					if(!checkEmail(field.val())) {
						var msg = $(this).find("small").html();
						flag = false;
						alert(msg);
					}
				}
				
				var isDate = field.data('date');
			});
			
			return false;
		})
	});
}
})(jQuery);

function checkEmail(email) {
	if(email == '') 
		return true;

	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!filter.test(email.value)) {
		return false;
	}
}