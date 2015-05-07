(function($) {
var FwFile = {
	init : function(options) {
		
		var settings = {
			'name' : 'upload',
			'fileTypes' : ["jpg", "png"],
			'onFileError' : function() {},
			'onFileEmpty' : function() {},
			'onSuccess' : function(data) {},
			'onError' : function() {}
		};
		
		settings = $.extend(settings, options);
	
		var self = $(this);
		self.hide();
		var FFileContainer = $("<div>", {
			'class' : 'ffile-container'
		});
		
		self.after(FFileContainer);
		
		var FFlileUploadText = $("<div>", {
			"class" : "ffile-upload-text"
		}).appendTo(FFileContainer);

		var FFlileUploadTextSpan = $("<div>", {
			'class' : 'ffile-upload-text-content',
			'html' : "No File Choosen"
		}).appendTo(FFlileUploadText);

		var FFileButtons = $("<div>", {
			'class' : "ffile-buttons"
		}).appendTo(FFileContainer);

		var FFileBrowse = $("<div>", {
			'class' : 'ffile-browse'
		}).appendTo(FFileButtons);

		var FFileInput = $("<input>", {
			'class' : 'ffile-input',
			'type' : 'file',
			'name' : settings.name
		}).appendTo(FFileBrowse);

		var FFileBrowseButton = $("<button>", {
			"class" : "ffile-browse-button",
			"type" : "button",
			"html" : 'Browse'
		}).appendTo(FFileBrowse);
		
		var FFileUploadButton = $("<button>", {
			"class" : "ffile-upload-button",
			"type" : "button",
			"html" : 'Upload Now'
		}).appendTo(FFileButtons);

		FFileInput.change(function(e) {
			alert("changed");
			var fileName = FFileInput.val();
			var extension = fileName.split(".");
			extension = extension[(extension.length-1)];
			
			if($.inArray(extension, settings.fileTypes) != 1) {
				settings.onFileError();
				FFileInput.replaceWith(FFileInput = FFileInput.clone(true));
				return;
			}
			
			FFlileUploadTextSpan.html(fileName);
		});

		FFileUploadButton.click(function() {
			if(FFileInput.val() == "") {
				settings.onFileEmpty();
				return;
			}
			
			// upload
			var iframe = $("<iframe>", {
				'width' : '350',
				'height': '30',
				"id" : "myframe",
				"frameBorder" : 0,
				"scrolling" : "no",
				"class" : "myframe"
			}).appendTo($("body"));

			var FForm = $("<form>", {
				'action' : 'http://localhost/iframeupload/upload.php',
				'id' : 'test',
				'method' : 'post',
				'enctype' : 'multipart/form-data'
			});

			$(iframe).load(function() {
				var FDocument = $(this).contents();
				var FBody = FDocument.find("body");

				var FHtml = FBody.html();

				if(FHtml != "") {
					
					try {
						response = $.parseJSON(FHtml);
						
						switch(response.status) {
							case 'success' :
								settings.onSuccess(response.data);
								break;
							case 'error':
							default:
								settings.onError();
						}
					} catch(e) {
						settings.onError();
					}
					FFileInput = $("<input>", {
						'class' : 'ffile-input',
						'type' : 'file',
						'name' : settings.name
					}).appendTo(FFileBrowse);
					iframe.remove();
					return;
				}
				FBody.empty();
				FBody.append(FForm);
				FForm.append(FFileInput);	
				FForm.submit();
			});

			var agent = navigator.userAgent;
			if(agent.indexOf("Chrome") > -1 || agent.indexOf("Safari") > -1) {
				$(iframe).trigger("load");
			}
		});

	}
};

$.fn.ifUpload = function(method) {
    if (FwFile[method]) {
    	//alert(1);
    	//return;
        //return methods[method].apply(this, [].slice.call(arguments, 1));
    } else {
    	$(this).each(function() {
        	return FwFile.init.apply(this, [method]);
    	});
    }
};

})(jQuery);