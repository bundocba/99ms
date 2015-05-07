(function($) {
	$.fn.fwselect = function(options) {
		var settings = {
			'class'				:	'papi-selectbox',
			'backgroundColor'	:	'#292929',
			'textColor'			:	'#959595',
			'itemStyle'			: 	"", 
			'showSearchBox'		:	false,
			'searchBoxClass'	: 	"custom-seach-input", 
			'scrollBarColor'	: 	"#fff", 
			'maxHeight'			: 	150
		}
		
		for (var x in options) {
			if (settings.hasOwnProperty(x)) {
				settings[x] = options[x];
			}
		}
		
		$(this).each(function(i) {
			var $select = $(this);

			var width = $select.css('width');

			$select.hide();
			
			var selectBoxContainer = $('<div>',{
				'class'		:	"fwselect-wrapper "+settings['class'],
				'width'		: 	width,
				'html'		: 	'<div class="fwselect"></div>'
			})
			
			var selectBox = selectBoxContainer.find('.fwselect');
			selectBox.css('backgroundColor', settings['backgroundColor']);
			selectBox.css('color', settings['textColor']);
			
			var dropdownContainer = $('<div>',{
				'class'		: 	"fwselect-dropdown",
				'width'		: 	width
			})
			
			var dropdownScrollPane = $('<div>', {
				'class'		: 	"fwselect-dropdown-scrollpane",
				'width'		: 	width,
				'css'		: 	{
					'max-height' : settings.maxHeight
				}
			})
			
			var dropdownList = $('<ul>',{
				'class'		: 	"fwselect-dropdownlist",
				'width'		: 	width
			})
			
			var searchBox = $('<div>', {
				'class'		: 	"fwselect-search",
				'html'		: 	'<div class="fwselect-searchinput-wrapper '+settings['searchBoxClass']+'"><input class="fwselect-searchinput" type="text"/></div>'
			})
			
			dropdownScrollPane.append(dropdownList);
			
			dropdownContainer.append(searchBox);
			dropdownContainer.append(dropdownScrollPane);
			
			selectBoxContainer.append(dropdownContainer);
			$select.after(selectBoxContainer);
			
			selectBox.html($select.find('option:selected').text());
			
			$select.find('option').each(function(index) {
				var $option = $(this);
				
				
				var li = $('<li>', {
					'class'	: 	'fwselect-option',
					'html'	: 	$option.text(),
					'style'	: settings['itemStyle']
				});
				
				li.attr('mvalue',$option.val());
				li.attr('mindex',index);
				
				li.click(function(e) {
					e.preventDefault();
					selectBox.html(li.html());
					dropdownList.find('li').removeClass('selected');
					li.addClass('selected');
					$select.val(li.attr('mvalue'));
					$select.trigger('change');
				})
				if($option.is(':selected')) {
					selectBox.html($option.text());
					li.addClass('selected');
				}
				
				dropdownList.append(li);	
			})
			dropdownContainer.show();
			dropdownScrollPane.jascrollpane({
				width: '100%',
				scrollButtons: false,
				scrollBarColor: settings.scrollBarColor
			});
			dropdownContainer.hide();
			dropdownContainer.bind('showDropDown', function() {
				if(dropdownContainer.is(':animated')) return false;
				selectBoxContainer.addClass('opened');
				dropdownContainer.slideDown();
				searchBox.find('input').focus();
				dropdownList.trigger('select');
				dropdownScrollPane.jascrollpane('update');
				var selected = dropdownList.find('li.selected');
				if(selected.size() > 0) {
					dropdownScrollPane.jascrollpane('scrollToPosition', selected.position());
				}
				
			}).bind('hideDropDown', function() {
				if(dropdownContainer.is(':animated')) return false;
				selectBoxContainer.removeClass('opened');
				dropdownContainer.slideUp();
			}).bind('toggleDropDown', function() {
				if(selectBoxContainer.hasClass('opened')) {
					dropdownContainer.trigger('hideDropDown');
				} else {
					dropdownContainer.trigger('showDropDown');
				}
			})
			
			searchBox.bind('search', function() {
				var keyWord = searchBox.find('input').val();
				keyWord = $.trim(keyWord);
				
				if(keyWord == '') {
					dropdownContainer.find('li').show();
				} else {
					dropdownContainer.find('li').show();
					dropdownContainer.find('li').each(function() {
						var $li = $(this);
						var html = $li.html().toLowerCase();
						keyWord = keyWord.toLowerCase();
						
						if(html.indexOf(keyWord) == -1) {
							$li.hide();
						}
					})
				
				}
				dropdownScrollPane.jascrollpane('update');
			})
			
			dropdownList.bind('select', function() {
				var selected = dropdownList.find('li.selected');
				dropdownList.animate({
					scrollTop	:	(selected.position().top - 80 )+ 'px'
				},100);
			})
			
			searchBox.find('input').keyup(function() {
				searchBox.trigger('search');
			})


			
			selectBoxContainer.click(function() {
				dropdownContainer.trigger('toggleDropDown');
			})
			
			$(document).click(function() {
				dropdownContainer.trigger('hideDropDown');
			})

			searchBox.find('input').click(function(e) {
				e.stopPropagation();
			})

			if(!settings['showSearchBox']) {
				searchBox.hide();
			}
		})
	}
})(jQuery);