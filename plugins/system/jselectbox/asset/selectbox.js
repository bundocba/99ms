(function($) {

var jselectbox = {
	init : function(options){
		var settings = {
			'effect':'slide',
			'optionMaxHeight':200,
			'search':false,
			'speed':'fast' // fast, slow, null
		};

		settings = $.extend(settings, options);	

		// generate
		var selectbox = $(this);
		selectbox.addClass('jsb');
		var num = selectbox.find('option').length;

		var holder = $("<div>", {
			'class' : 'jsbHolder',
			'width' : selectbox.width()
		});

		selectbox.after(holder);

		var selectorWrap = $("<div>",{
			'class' : 'jsbSelector-wrapper'
		})
		var input = $("<input>", {
			'class' : 'jsbSelector',
			'type': 'text'
		});
		var toggle = $("<div>", {
			'class' : 'jsbToggle jhidden'
		});
		toggle.appendTo(selectorWrap);
		input.appendTo(selectorWrap);
		selectorWrap.append("<div class='clr' />");
		selectorWrap.appendTo(holder);

		var options = $("<ul>", {
			'class' : 'jsbOptions',
			'width' : holder.width()
		});
		holder.append(options);

		selectbox.find('option').each(function(index,element){
			var value = $(element).val();
			var text = $(element).text();
			var option = $("<li>",{
				'rel' : value,
				'text': text
			});
			if(index == num-1) option.addClass('jlast');

			option.appendTo(options);

		})

		setVal(selectbox.val());
		// end generate

		function setVal(value){
			selectbox.val(value).change();
			input.val(selectbox.find("option:selected").text());
			options.find('li').removeClass('jsbSelected');
			options.find('li[rel="'+value+'"]').addClass('jsbSelected');
		}
		function show(){
			if(options.hasClass('running')) return false;
			options.addClass('running');
			switch(settings.effect){
				case 'slide':
					options.slideDown(settings.speed,function(){
						options.removeClass('running');
					});
				break;
			}
			toggle.removeClass('jhidden');
		}
		function hide(){
			if(options.hasClass('running')) return false;
			options.addClass('running');
			switch(settings.effect){
				case 'slide':
					options.slideUp(settings.speed,function(){
						options.removeClass('running');
					});
				break;
			}
			toggle.addClass('jhidden');
		}
		function toggleOptions(){
			if(toggle.hasClass('jhidden')) show();
			else hide();
		}
		// set max-height
		options.css('max-height',settings.optionMaxHeight);

		// disable search function
		if(!settings.search){
			input.attr('readonly','readonly');
			input.focus(function() {
			    input.blur();
			    toggleOptions();
			});
		}

		options.find('li').click(function(){
			setVal($(this).attr('rel'));
			hide();
		})

		toggle.click(function(){
			toggleOptions();
		})
	}
};	


$.fn.jselectbox = function(method) {
	if (!jselectbox[method]) {
    	$(this).each(function() {
        	return jselectbox.init.apply(this, [method]);
    	});
    }
};
})(jQuery);