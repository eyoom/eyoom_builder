/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		//{ name: 'forms' },
		{ name: 'document',	   groups: [ 'mode' ] },
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'insert'},
		{ name: 'links' },
		//{ name: 'tools' },
		{ name: 'about' },
		'/',
		{ name: 'others' },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup', 'oembed' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] }
		
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Save,Copy,Cut,Iframe,Scayt,Flash,Styles,Format,Strike';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	// Set the file uploader
	config.filebrowserUploadUrl = g5_editor_url+"/upload.php?type=Images";

	// Set Editor default Height
	config.height = 350;

	// Set Editor Skin
	config.skin = 'icy_orange';

	/*
	config.extraPlugins = 'quicktable,panelbutton,floatpanel,panel,oembed,widget,lineutils,dialog,dialogui,glyphicons';

	config.oembed_maxWidth = '600';
	config.oembed_maxHeight = '450';
	config.oembed_WrapperClass = 'embededContent';

	config.contentsCss = '../plugin/editor/ckeditor_4.4.5_full/plugins/glyphicons/bootstrap/css/bootstrap.css';
	*/

};
