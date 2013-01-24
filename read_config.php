<?php
$self = 'assets/plugins/ckeditor/read_config.php';
$base_path = str_replace($self,'',str_replace('\\','/',__FILE__));
include_once($base_path . 'manager/includes/config.inc.php');
include_once($base_path . 'manager/includes/document.parser.class.inc.php');
$modx = new DocumentParser();
$modx->getSettings();

$ph['toolbar'] = (empty($modx->config['ck_editor_toolbar'])) ? 'modx' : $modx->config['ck_editor_toolbar'];

$q = str_replace('\\','/',$_GET['q']);
if(preg_match('@[^0-9a-zA-Z\-_\.]@', $q)) exit;
if(preg_match('..', $q)) exit;
if(preg_match('/', $q)) exit;
$cke_path = "{$base_path}assets/plugins/ckeditor/";
$cke_config  = file_get_contents($cke_path . $q);
$cke_config .= file_get_contents($cke_path . 'modx_config.js');
foreach($ph as $name => $value)
{
	$name = '[+' . $name . '+]';
	$cke_config = str_replace($name, $value, $cke_config);
}
echo $cke_config;
