(function($) {
	$.fn.fwcheckbox = function(options) {
	
		var settings = {
			checkboxClass		:	'demo-checkbox',
			checkMarkClass		:	'demo-checkmark',
			labelClass			:	'demo-label'
		}
		
		for (var x in options) {
			if (settings.hasOwnProperty(x)) {
				settings[x] = options[x];
			}
		}
		
		$(this).each(function() {
			var $checkbox = $(this);
			var $label = $('body').find('label[for="'+$checkbox.attr('id')+'"]');
			$checkbox.hide();
			
			var w = $checkbox.outerWidth();
			var h = $checkbox.outerHeight();
			
			var checkboxWrapper = $('<div>', {
				'class'	: 	'fwcheckbox-wrapper '+settings['checkboxClass']
			})
			
			var checkboxContainer = $('<div>', {
				'class'		: 	'fwcheckbox-container'
			})
			
			var checkMark	=	$('<span>', {
				'class'		:	'fwcheckbox-mark '+settings['checkMarkClass']
			})
			
			checkboxContainer.append(checkMark);
			checkboxWrapper.append(checkboxContainer);
			
			if($checkbox.is(':checked')) {
				checkMark.addClass('fwcheckbox-checked');
			}
			
			$checkbox.after(checkboxWrapper);
			
			checkMark.bind('checkCheckbox', function() {
				checkMark.addClass('fwcheckbox-checked');
				$checkbox.attr('checked', true);
				$checkbox.trigger('change');
			}).bind('uncheckCheckbox', function() {
				checkMark.removeClass('fwcheckbox-checked');
				$checkbox.attr('checked', false);
				$checkbox.trigger('change');
			}).bind('toggleCheckbox', function() {
				if(checkMark.hasClass('fwcheckbox-checked')) {
					checkMark.trigger('uncheckCheckbox');
				} else {
					checkMark.trigger('checkCheckbox');
				}
			})
			
			checkboxContainer.click(function() {
				checkMark.trigger('toggleCheckbox');
			})
			
			if($label.size() ==1) {
				var label = $('<span>', {
					'class'	:	'fwcheckbox-label '+settings['labelClass'],
					'html'	:	$label.html()
				})
				
				label.click(function() {
					checkMark.trigger('toggleCheckbox');
				})
				checkboxWrapper.append('&nbsp;');
				checkboxWrapper.append(label);
				
				$label.hide();
			}
		})
	}
})(jQuery)

