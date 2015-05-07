(function($){

	$.fn.mousehold = function(timeout, f) {
		if (timeout && typeof timeout == 'function') {
			f = timeout;
			timeout = 100;
		}
		if (f && typeof f == 'function') {
			var timer = 0;
			var fireStep = 0;
			return $(this).each(function() {
				$(this).mousedown(function() {
					fireStep = 1;
					var ctr = 0;
					var t = this;
					timer = setInterval(function() {
						ctr++;
						f.call(t, ctr);
						fireStep = 2;
					}, timeout);
				})

				clearMousehold = function() {
					clearInterval(timer);
					if (fireStep == 1) f.call(this, 1);
					fireStep = 0;
				}
				
				$(this).mouseout(clearMousehold);
				$(this).mouseup(clearMousehold);
			})
		}
	}

	var method = {
		init : function(options) {
			var $obj = this;
			var settings = {
				height: 200,
				width: 300,
				marginRight: 10,
				buttonUpImage: '',
				buttonDownImage: '',
				scrollButtons: false,
				scrollBarColor: '#000',
				callBacks : {
					beforeScroll : function() {},
					afterScroll: function() {}
				}
			}
			$.extend(settings, options);

			return $(this).each(function() {
				var $obj = $(this);


				if($obj.css('max-width') != 'none') {
					var width = $obj.css('max-width');
				} else {
					var width = settings.width;
				}

				if($obj.css('max-height') != 'none') {
					var height = $obj.css('max-height');
				} else {
					var height = settings.height;
				}


				var jaScrollPaneWrap = $("<div>", {
					'class':'jaScrollPaneWrap'
				});
				var jaScrollPane = $("<div>", {
					'class':'jaScrollPane',
					'css' : {
						'margin-right' : settings.marginRight
					}
				});
				var jaScrollBarWrap = $("<div>", {
					'class':'jaScrollBarWrap'
				});
				var jaScrollBar = $("<div>", {
					'class' : 'jaScrollBar'
				})
				var jaScrollBarButton = $("<span>", {
					'class' : 'jaScrollBarButton',
					'css'	: {
						'background-color' : settings.scrollBarColor
					} 
				})
				var jaScrollButtonUp = $("<a>", {
					'class':'jaScrollButton jaScrollButtonUp',
					'html' : (settings.buttonUpImage != '')?'<img src="' + settings.buttonUpImage + '"/>':""
				})
				var jaScrollButtonDown = $("<a>", {
					'class':'jaScrollButton jaScrollButtonDown',
					'html' : (settings.buttonUpImage != '')?'<img src="' + settings.buttonDownImage + '"/>':"	"
				})

				height = height.replace('px', '');
				height = eval(height);

				$obj.data({
					'beforeScroll':settings.callBacks.beforeScroll,
					'afterScroll':settings.callBacks.afterScroll,
					'height':height,
					'width':width,
					'allowScroll' : true
				})

				jaScrollBar.append(jaScrollBarButton);
				jaScrollBarWrap.append(jaScrollBar);
				jaScrollPane.html($obj.children());
				jaScrollPaneWrap.append(jaScrollPane);

				jaScrollPaneWrap.css("width", width);
				jaScrollPaneWrap.css("height", height);

				jaScrollBarWrap.append(jaScrollButtonUp);
				jaScrollBarWrap.append(jaScrollButtonDown);
				jaScrollPaneWrap.append(jaScrollBarWrap);
				$obj.append(jaScrollPaneWrap);

				if(settings.scrollButtons) {
					jaScrollBar.css({
						'height':height - (jaScrollButtonDown.outerHeight(true) * 2),
						'top' : jaScrollButtonDown.outerHeight(true)
					})
				} else {
					jaScrollButtonDown.hide()
					jaScrollButtonUp.hide()
				}
				
				var percent = Math.floor((height/jaScrollPane.outerHeight(true))*100);

				jaScrollBarButton.css("height", percent+'%');
				jaScrollBarButton.draggable({
					containment: "parent",
					cursor: "pointer",
					drag : function(event) {
						var pos = $(this).position();
						var percent = pos.top / jaScrollBar.outerHeight(true);
						var top = Math.floor(percent * jaScrollPane.outerHeight(true));
						jaScrollPane.css({
							'top' : -top,
							'left': pos.left
						})
					}
				});

				jaScrollPane.draggable({
					axis: "y",
					cursor: 'pointer',
					drag : function() {
						var pos = jaScrollPane.position();
						var percent = pos.top / jaScrollPane.outerHeight(true);
						var top = Math.floor(percent * jaScrollBar.outerHeight(true));
						
						jaScrollBarButton.css({
							'top' : -top,
							'left': pos.left
						})
					},
					stop : function(event) {
						var pos = $(this).position();
						var maxTop = 0;
						var maxBottom = jaScrollPane.outerHeight(true) - jaScrollPaneWrap.outerHeight(true);
						var maxBottomButton = jaScrollBarWrap.outerHeight(true) - jaScrollBarButton.outerHeight(true);
						
					//	alert(maxBottom)
						if(pos.top > 0) {
							jaScrollPane.animate({'top': 0},500);
							jaScrollBarButton.animate({'top': 0},500);
						} else if(pos.top <= - maxBottom) {
							jaScrollPane.animate({'top': -maxBottom},500);
							jaScrollBarButton.animate({'top': maxBottomButton},500);
						}
						
					}
				});

				jaScrollButtonDown.mousehold(50, function() {
					var pos = jaScrollPane.position();
					method.scrollTo($obj, pos, 200);
				});

				jaScrollButtonUp.mousehold(50, function() {
					var pos = jaScrollPane.position();
					method.scrollTo($obj, pos.top + 50, pos.left, 200);
				});

				jaScrollPaneWrap.bind("mousewheel", function(e, delta, deltaX, deltaY) {
					e.stopPropagation();
					e.preventDefault();
					if(!$obj.data("allowScroll")) 
						return false;
					var pos = jaScrollPane.position();
					if(deltaY > 0) {
						method.scrollTo($obj, pos.top + 50, pos.left, 200);
					} else if(deltaY < 0) {
						method.scrollTo($obj, pos.top - 50, pos.left, 200);
					}
				})	
				jaScrollPaneWrap.click(function(e) {e.preventDefault()})
			})

			
		},
		updateContent: function(html) {
			this.each(function() {
				var self = $(this);
				self.find('.jaScrollPane').append(html);
			})
		},
		destroy: function(obj) {
			var html = this.jaScrollPane.html();
			obj.html(html);
			this.jaScrollPaneWrap.remove();
			this.jaScrollPane.remove();
			this.jaScrollBarWrap.remove();
			this.jaScrollButtonUp.remove();
			this.jaScrollButtonDown.remove();
		},
		update: function(obj) {
			this.each(function() {
				var percent = Math.floor(($(this).find('.jaScrollPaneWrap').outerHeight(true)/$(this).find('.jaScrollPane').outerHeight(true))*100);
				if(percent > 100) {
					$(this).find('.jaScrollBarButton').hide();
					$(this).data({'allowScroll': false});
				} else {
					$(this).find('.jaScrollBarButton').show();
					$(this).data({'allowScroll': true});
					$(this).find('.jaScrollBarButton').css("height", percent+'%');
					$(this).find('.jaScrollBarButton').css("top", '0');
					$(this).find('.jaScrollPane').css("top", '0');
				}
			})
		},
		show : function() {
			//alert(this.jaScrollButtonUp);
		},
		scrollTo: function(obj, top, left, speed, isDrag) {
			if(top > 0 || left >0) {
				top = 0;
			}
			var outerHeight = obj.find('.jaScrollPane').outerHeight(true);

			var height = obj.data('height');

			if(top - height <= (-1 * outerHeight)) {
				top = (outerHeight*-1) + height;	
			}

			if(!obj.find('.jaScrollPane').is(':animated')) {
				obj.data("beforeScroll").apply();
				if(typeof speed != 'number') {
					speed = 200;
				}
				obj.find('.jaScrollPane').animate({'top': top, 'left': left}, speed, "swing", obj.data("afterScroll"));
				if(!isDrag)
					obj.find('.jaScrollBarButton').animate({'top': ((top*-1)/obj.find('.jaScrollPane').outerHeight(true))*100 + '%'}, 'fast');
			}
		},
		bindEvent: function() {
			var self = this;
			
		},
		scrollToPosition: function(position, speed) {
			if($(this).find('.jaScrollPane').outerHeight(true) - $(this).data("height") < position.top) {
				position.top = $(this).find('.jaScrollPane').outerHeight(true) - $(this).data("height");
			}
			if(!$(this).data('allowScroll'))
				position.top = 0;
			$(this).find('.jaScrollPane').animate({'top': -position.top, 'left': position.left}, speed, "swing");
			$(this).find('.jaScrollBarButton').animate({'top': ((position.top)/$(this).find('.jaScrollPane').outerHeight(true))*100 + '%'}, 'fast');
		}
	}

	$.fn.jascrollpane = function(options) {
		if(method[options]) {
			method[options].apply($(this),Array.prototype.slice.call(arguments,1));
		} else {
			var obj = $(this);
			method.init.apply(obj, [options]);
		}
	}

	
})(jQuery);