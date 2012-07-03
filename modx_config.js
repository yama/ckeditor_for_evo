CKEDITOR.dtd.del = CKEDITOR.dtd.strike;
CKEDITOR.dtd.ins = CKEDITOR.dtd.u;
CKEDITOR.config.coreStyles_underline = { element : 'ins' };
CKEDITOR.config.coreStyles_strike = { element : 'del' };
CKEDITOR.config.dialog_backgroundCoverColor = 'rgb(55, 55, 55)';

// CKEDITOR.config.extraPlugins = '';
CKEDITOR.editorConfig = function(config)
{
	config.toolbar ='[+toolbar+]'; // MODx, full or simple
	config.toolbar_modx =
	[
		['Source'],
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['PasteText','PasteFromWord'],
		['Undo','Redo','-','Find','-','RemoveFormat'],
		'/',
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight'],
		['Link','Unlink','Anchor'],
		['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar'],
		'/',
		['Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Maximize', 'ShowBlocks','-','About']
	];
	config.toolbar_full =
	[
		['Source','-','Save','NewPage','Preview','-','Templates'],
		['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
		'/',
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink','Anchor'],
		['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		'/',
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Maximize', 'ShowBlocks','-','About']
	];
	config.toolbar_simple =
	[
		['Source'],['Bold','Strike','TextColor','BGColor'],
		['JustifyLeft','JustifyRight'],
		['Link','Unlink'],
		['Image','HorizontalRule','Smiley'],
		['Maximize', '-','About']
	];
}
