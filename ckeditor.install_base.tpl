//<?php
/**
 * CKEditor
 *
 * CKEditor - RichText Editor Plugin
 *
 * @category 	plugin
 * @version 	3.6.6.1
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties &format_tags=Format selector;text; &height=height;text;
 *
 * @internal	@events OnRichTextEditorInit, OnRichTextEditorRegister, OnInterfaceSettingsRender
 * @internal	@modx_category Manager and Admin
 *
 *
 * Original Written By Jeff Whitfield - April 30, 2007
 * Modify By yama - 2013-01-24
 */

include_once $modx->config['base_path'] . 'assets/plugins/ckeditor/functions.php';
$cke = new CKEditor();
$cke->run();
