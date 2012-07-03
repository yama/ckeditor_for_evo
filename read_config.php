<?php
include_once '../../../manager/includes/config.inc.php';
include_once(MODX_BASE_PATH . 'manager/includes/document.parser.class.inc.php');
$modx = new DocumentParser();
$modx->getSettings();

$ph['toolbar'] = (empty($modx->config['ck_editor_toolbar'])) ? 'modx' : $modx->config['ck_editor_toolbar'];

if(preg_match('@[^0-9a-zA-Z\-_\.]@', $_GET['q'])) exit;
$cke_config  = file_get_contents($_GET['q']);
$cke_config .= file_get_contents('modx_config.js');
foreach($ph as $name => $value)
{
	$name = '[+' . $name . '+]';
	$cke_config = str_replace($name, $value, $cke_config);
}
echo $cke_config;
