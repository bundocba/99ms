(function($) {
	$(document).ready(function() {
		var $popupwrapper = $('<div>',{'class': 'fw_popup_wrapper'})
		var $popup = $('<div>',{'class': 'fw_popup'})
		var $popupmsg = $('<div>',{'class': 'fw_popup_msg'});
		var $popupbuttonwrapper = $('<div>',{'class': 'fw_popup_button_wrapper'});
		$('body').append($popupwrapper);
		$('body').append($popup);
		$popup.append($popupmsg);
		$popup.append($popupbuttonwrapper);
		$popup.hide();
		$popupwrapper.hide();
		
		$(document).keydown(function(e) {
			if(e.keyCode == 27) {
				$popup.trigger('hidePopup');
			}
		})
	})

	$.extend({
		fwpopup: function(options){
			var settings = null;
			settings = {
				message: '<div>asdfasdfasdf</div>',
				background: '#000',
				popupBackground: '#000',
				popupBorder: '1px solid red',
				popupWidth: '250',
				popupHeight: '200',
				popupTop: 0,
				popupClass: 'japopup',
				backgroundClickClose: false,
				speed: 300,
				opacity: 0.5,
				closeButton: false,
				closeButtonClass: 'closeButton',
				buttonClass: 'button',
				button: {
				},
				iframe: {
				}
			}

			for (var x in options) {
				if (settings.hasOwnProperty(x)) {
					settings[x] = options[x];
				}
			}
			
			//get element
			var $popupWrapper = $('.fw_popup_wrapper');
			var $popup = $('.fw_popup');
			var $popupMsg = $('.fw_popup_msg');
			var $popupButton = $('.fw_popup_button_wrapper');
			var $popupClose = $('.fw_closeButton');



			
			$popup.bind('showPopup', function(options) {
				$popup.addClass(settings['popupClass']);
				var _popupleft = 0;
				var _popupright = 0;
				var _w = $(window).outerWidth(true);
				
				$popupWrapper.show();
				$popup.fadeIn(settings['speed']);
				$popupWrapper.css('background', settings['background']);
				$popupWrapper.css('opacity', settings['opacity']);
				$popup.css('border', settings['popupBorder']);
				$popup.css('background', settings['popupBackground']);
				$popup.css('width', settings['popupWidth']);
				$popup.css('height', settings['popupHeight']);
				_popupleft = (_w - $popup.outerWidth(true))/2;
				$popup.css('left', _popupleft+'px');
				if(settings['popupTop'] == 'center') {
					var windowHeight = $(window).height();

					$popup.css('top', ((windowHeight - settings.popupHeight)/2 ) + 'px');
				} else {
					$popup.css('top', settings['popupTop']);
				}
				$popupButton.focus();
			}).bind('hidePopup', function() {
				$popup.removeClass(settings['popupClass']);
				$popupWrapper.hide();
				$popup.fadeOut(settings['speed']);
				$popupMsg.html('');
				
				$('.fw_closeButton').remove();
			})
			$popupButton.html('');
			if(options.hasOwnProperty('iframe')) {
				var _iframe = $('<iframe>',{
					src		: 	settings['iframe']['link'], 
					width	: 	settings['iframe']['width'],
					height	: 	settings['iframe']['height'],
					frameBorder : 0
				});
				$popupMsg.html(_iframe);
			} else {
				$popupMsg.html(settings['message']);
			}
			
			var count = 0;
				
			for (var x in settings['button']) {
				count++;
			}
			
			if(count >0) {
				$.each(settings['button'], function(i) {
					var buttonValue = $(this).attr('value');
					var buttonFunction = $(this).attr('onClick');
					
					var button = $('<input>', {
						'type'	:	'button',
						'class'	:	'fw_popupbutton',
						'value'	:	buttonValue
					})
					
					button.bind('click', function() {
						buttonFunction();
						$popup.trigger('hidePopup');
					})
					
					$popupButton.append(button);
					$popupButton.show();
				})
			} else {
				$popupButton.html('');
				$popupButton.hide();
			}
			
			if(settings['closeButton']) {
				var closeButton = $('<a>',{
					'class': settings.closeButtonClass + ' fw_closeButton '
				})
				$popup.append(closeButton);
				
				closeButton.click(function() {
					$popup.trigger('hidePopup');
				})
			} else {
				
			}
			
			if(settings['backgroundClickClose']) {
				$popupWrapper.click(function() {
					$popup.trigger('hidePopup');
				})
			} else {
				$popupWrapper.unbind('click');
			}
			
			$popup.trigger('showPopup');
		}
	});

	
})(jQuery)	


function fwconfirm(html, function_yes, function_no) {
	jQuery.fwpopup({
		message: html,
		background: '#000',
		popupBackground: '#c3c3c3',
		popupBorder: '1px solid #ccc',
		popupWidth: '250',
		popupHeight: '200',
		speed: 300,
		opacity: 0.5,
		buttonClass: 'button',
		'button':{
			button_yes: {
				onClick: function_yes,
				value: 'Yes'
			},
			button_no: {
				onClick: function_no,
				value: 'No'
			}
		},
		closeButton: false,
		closeButtonClass: 'closeButton'
	})
}



function fwajax(link) {
	jQuery.fwpopup({
		background: '#000',
		popupBackground: '#fff',
		popupBorder: '1px solid #ccc',
		popupWidth: 400,
		popupHeight: 300,
		backgroundClickClose: true,
		speed: 300,
		opacity: 0.5,
		top: 20,
		buttonClass: 'button',
		closeButton: true,
		closeButtonClass: 'closeButton',
		iframe: {
			link: link,
			width: 350,
			height: 250
		}
	})
}