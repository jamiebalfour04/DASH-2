<?php
  /*
   * This file should not be edited. This file is designed to setup TinyMCE.
   */

  echo '<script src="'.DASHBOARD_PATH.'/ui/editor/tinymce/tinymce.min.js"></script>';
  $custom_css_file = "";
  if($this->dashboard->getConfigOption("dashboard")){
    $custom_css_file = '?file=' . $this->dashboard->getConfigOption("dashboard")['custom_css'];
  }

  $plugins = "";

?>
<script type="text/javascript">
	tinymce.init({
    //skin: (window.matchMedia("(prefers-color-scheme: dark)").matches ? "oxide-dark" : "oxide"),
    skin: "oxide",
		selector : "#contents",
		setup : function(editor) {
      editor.on("change", function(editor) {
      	modified = true;
      });
      editor.on('NodeChange', function(e) {
        if (e && (e.element.nodeName.toLowerCase() == 'img' || e.element.nodeName.toLowerCase() == 'video')) {
          tinyMCE.DOM.setAttribs(e.element, {'width': null, 'height': null});
        }
      });
   	},
		mode : "exact",
		height : "400",
		entity_encoding : "named",
		entities : "quot,amp,pound,euro,copy,reg,trade,gt,lt,ge,le,deg,nbsp",
		fontsize_formats : '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
		formats : {
			bold : {
				inline : 'strong'
			},
			italic : {
				inline : 'em'
			},
			underline : {
				inline : 'u'
			}
		},
		style_formats : [{
			title : 'Headers',
			items : [
			{
				title : 'Header 1',
				format : 'h1'
			},
			{
				title : 'Header 2',
				format : 'h2'
			},
			{
				title : 'Header 3',
				format : 'h3'
			}, {
				title : 'Header 4',
				format : 'h4'
			}, {
				title : 'Header 5',
				format : 'h5'
			}, {
				title : 'Header 6',
				format : 'h6'
			}]
		}, {
			title : 'Inline',
			items : [{
				title : 'Bold',
				icon : 'bold',
				format : 'bold'
			}, {
				title : 'Italic',
				icon : 'italic',
				format : 'italic'
			}, {
				title : 'Underline',
				icon : 'underline',
				format : 'underline'
			}, {
				title : 'Strikethrough',
				icon : 'strikethrough',
				format : 'strikethrough'
			}, {
				title : 'Superscript',
				icon : 'superscript',
				format : 'superscript'
			}, {
				title : 'Subscript',
				icon : 'subscript',
				format : 'subscript'
			}, {
				title : 'Code',
				icon : 'code',
				format : 'code'
			}]
		}, {
			title : 'Blocks',
			items : [{
				title : 'Paragraph',
				format : 'p'
			}, {
				title : 'Blockquote',
				format : 'blockquote'
			}, {
				title : 'Div',
				format : 'div'
			}, {
				title : 'Pre',
				format : 'pre'
			}]
		}, {
			title : 'Alignment',
			items : [{
				title : 'Left',
				icon : 'alignleft',
				format : 'alignleft'
			}, {
				title : 'Center',
				icon : 'aligncenter',
				format : 'aligncenter'
			}, {
				title : 'Right',
				icon : 'alignright',
				format : 'alignright'
			}, {
				title : 'Justify',
				icon : 'alignjustify',
				format : 'alignjustify'
			}]
		}
		],

		// ===========================================
		// PUT PLUGIN'S BUTTON on the toolbar
		// ===========================================

		plugins : [
			"advlist autolink lists link image charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars code fullscreen imagetools",
			"insertdatetime media nonbreaking save table directionality",
			"emoticons template paste textpattern hr save"
		],
		menu: {
			file: {title: 'File', items: 'newdocument | visualchars visualblocks visualaid | preview fullscreen | code'},
	    edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall | editimage'},
	    insert: {title: 'Insert', items: 'insertdatetime | link image media | pagebreak hr anchor charmap | bullist numlist'},
	    format: {title: 'Format', items: 'effects colors | formats | removeformat'},
	    table : {title : 'Table', items : 'inserttable tableprops deletetable cell row column'}
	 	},
    setup: function(editor){
      editor.ui.registry.addNestedMenuItem('effects', {
        text: 'Styles',
        getSubmenuItems: function(){return "bold italic underline strikethrough superscript subscript";}
      });
      editor.ui.registry.addNestedMenuItem('colors', {
        text: 'Color',
        getSubmenuItems: function(){return "forecolor backcolor";}
      });
    },
    mobile: {
      menubar: false
    },
		toolbar1 : "undo redo | code | fullscreen",
		toolbar3 : "image media link | bullist numlist | styleselect ",

    images_upload_url : "<?php echo DASHBOARD_PATH . 'upload.php'?>",


		// ===========================================
		// SET RELATIVE_URLS to FALSE (This is required for images to display properly)
		// ===========================================
    convert_urls : false,
    object_resizing : false,
		style_formats_merge : false,
		relative_urls : false,
    content_css : '<?php echo DASHBOARD_PATH . 'ui/editor/editor_style.php'.$custom_css_file; ?>',
		valid_elements : '*[*]',
    resize : false,
		document_base_url:'<?php echo DASHBOARD_PATH; ?>'
	});
</script>
