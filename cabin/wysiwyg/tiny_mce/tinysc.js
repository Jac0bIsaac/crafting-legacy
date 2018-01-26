tinymce.init({
	selector: 'textarea#sc',
	theme: 'modern',
	menubar: false,
	
	plugins: [
		'advlist autolink lists link image charmap print preview hr anchor pagebreak',
	    'searchreplace wordcount visualblocks visualchars code fullscreen',
	    'insertdatetime media nonbreaking save table contextmenu directionality',
	    'emoticons template paste textcolor colorpicker textpattern imagetools'
	],
	        
 toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor emoticons',
 image_advtab: true,
	
	  
	content_css: '../assets/css/style.css',
	
	 file_browser_callback: function(field, url, type, win) {
	        tinyMCE.activeEditor.windowManager.open({
	            file: '../cabin/kcfinder/browse.php?opener=tinymce4&field=' + field + '&type=' + type,
	            title: 'File Manager',
	            width: 700,
	            height: 500,
	            inline: true,
	            close_previous: false
	        }, {
	            window: win,
	            input: field
	        });
	        return false;
	    }

 });