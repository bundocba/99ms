(function($) {
	$.fn.fwradio = function(options) {
		
		var settings = {
			radioClass		:	'demo-radio',
			radioMarkClass	:	'demo-radio',
			labelClass		:	'demo-radio'
		}
		
		for (var x in options) {
			if (settings.hasOwnProperty(x)) {
				settings[x] = options[x];
			}
		}
		
		//var radioname = $(this).attr('name');
		
		$(this).each(function() {
			var radioname= '';

			var $radio = $(this);
			var w = $radio.outerWidth();
			var h = $radio.outerHeight();
			radioname = $radio.attr('name');
			
			$radio.hide();
			
			var $label = $('label[for="'+$radio.attr('id')+'"]');
			
			var radioWrapper = $('<div>', {
				'class'		:	'fwradio-wrapper '+settings['radioClass'],
				'radioname'	:	radioname
			})
			
			var radioContainer = $('<div>', {
				'class'	:	'fwradio-container'
			})
			
			var radioMark	= 	$('<span>', {
				'class'	:	'fwradio-mark '+settings['radioMarkClass']
			})
			
			radioContainer.bind('checkRadio', function() {
				$('.fwradio-wrapper[radioname="'+radioname+'"]').find('.fwradio-checked').removeClass('fwradio-checked');
				radioMark.addClass('fwradio-checked');
				$radio.attr('checked', true);
				$radio.trigger('change');
			});
			
			if($radio.is(':checked')) {
				radioContainer.trigger('checkRadio');
			}
			
			radioContainer.click(function() {
				radioContainer.trigger('checkRadio');
			})
			
			radioContainer.append(radioMark);
			radioWrapper.append(radioContainer);
			
			if($label.size() == 1) {
				var label = $('<span>', {
					'class'	:	'fwradio-label ' + settings['labelClass'],
					'html'	:	$label.html()
				})
				
				label.click(function() {
					radioContainer.click();
				})
				
				radioWrapper.append('&nbsp;');
				radioWrapper.append(label);
				
				$label.hide();
			}
			
			
			$radio.after(radioWrapper);
			
		})
		
		
	}
})(jQuery)