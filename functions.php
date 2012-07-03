<?php
//CKEditor RichText Editor Plugin v3.6.3-alpha
class CKEditor
{
	var $params = array();

	function CKEditor()
	{
		global $modx, $editor;
		
		$this->params = $modx->event->params;
		$this->params['manager_language'] = $modx->config['manager_language'];
		$this->params['use_editor']       = $modx->config['use_editor'];
		$this->params['editor_css_path']  = '';
		if(isset($this->params['height']) && !empty($this->params['height']))
		{
			$this->params['height'] = str_replace('px', '', $this->params['height']) .'px';
		}
		else
		{
			$this->params['height'] = '300px';
		}
		if($modx->event->name === 'OnRichTextEditorRegister') $this->params['editor'] = $editor;
		// $params['format_tags'] ='p;h2;h3;pre';
		if (!empty($this->params['format_tags']))
		{
			$this->params['format_tags'] = str_replace('/', ';', $this->params['format_tags']);
		}
	}
	
	function run()
	{
		global $modx;
		
		$e = &$modx->event;
		switch ($e->name)
		{ 
			case "OnRichTextEditorRegister": // register only for backend
				$e->output('CKEditor');
				break;
				
			case "OnRichTextEditorInit": 
				if($this->params['editor']!=='CKEditor') return;
				
				$this->params['use_browser']     = $modx->config['use_browser'];
				
				if($modx->isBackend() || (intval($_GET['quickmanagertv']) == 1 && isset($_SESSION['mrgValidated'])))
				{
					$this->params['language'] = $this->get_cke_lang($modx->config['manager_language']);
					
					$this->params['toolbarset']      = (!empty($modx->config['ck_editor_toolbar']) ? $modx->config['ck_editor_toolbar']:"modx");
					$this->params['toolbarcustom']   = (!empty($modx->config['ck_editor_custom_toolbar']) && ($modx->config['ck_editor_toolbar'] == "custom") ? $modx->config['ck_editor_custom_toolbar']:"");
					$this->params['frontend']        = false;
					$this->params['autoLang']        = $modx->config['ck_editor_autolang'];
					$this->params['webuser']         = null;
					
					$html = $this->get_cke_script();
				}
				else
				{
					$frontend_language = isset($modx->config['fe_editor_lang']) ? $modx->config['fe_editor_lang']:"";
					$this->params['language'] = $this->get_cke_lang($frontend_language);
					$webuser = (isset($modx->config['rb_webuser']) ? $modx->config['rb_webuser'] : null);
					
					$this->params['toolbarset']      = (isset($ckwebset) ? $ckwebset: 'basic');
					$this->params['toolbarcustom']   = (isset($ckwebcustom) && ($ckwebset == 'custom') ? $ckwebcustom : '');
					$this->params['frontend']        = true;
					$this->params['autoLang']        = $ckwebautolang;
					$this->params['webuser']         = $webuser;
					
					$html = $this->get_cke_script();
				}
				$e->output($html);
				break;
				
			case "OnInterfaceSettingsRender":
				
				$this->params['toolbarset']       = $modx->config['ck_editor_toolbar'];
				$this->params['toolbarcustom']    = $modx->config['ck_editor_custom_toolbar'];
				$this->params['autolang']         = $modx->config['ck_editor_autolang'];
				
				$html = $this->get_cke_settings();
				$e->output($html);
				break;
				
		   default :
		      return;
		}
	}
	
	function get_cke_settings()
	{
		$params = $this->params;
		global $modx, $_lang;
		// language settings
		$cke_path = $modx->config['base_path'] . 'assets/plugins/ckeditor/';
		if(!@include_once("{$cke_path}lang/". $modx->config['manager_language'] .'.inc.php'))
		    @include_once("{$cke_path}lang/lang/english.inc.php");
	
		$arrToolbar['modx']    = $_lang['cke_lang_toolbar_modx'];
		$arrToolbar['full']    = $_lang['cke_lang_toolbar_full'];
		$arrToolbar['simple']  = $_lang['cke_lang_toolbar_simple'];
		$user_config = file_get_contents("{$cke_path}user_config.js");
		preg_match_all('@config\.toolbar_([0-9a-zA-Z]+)@',$user_config, $toolbar_name,PREG_SET_ORDER);
		foreach($toolbar_name as $value)
		{
			$arrToolbar[$value['1']] = $value['1'];
		}
	
		foreach ($arrToolbar as $key=>$value)
		{
			$toolbarOptions .= '<option value="'.$key.'"'.($key == $params['toolbarset'] ? ' selected="selected"' : '').'>'.$value."</option>\n";
		}
	
		switch($_SESSION['browser'])
		{
			case 'ie':
				$displayStyle = 'block';
				break;
			default:
				$displayStyle = 'table-row';
		}
		$ph = $_lang;
		$ph['display'] = $params['use_editor']==1 ? $displayStyle : 'none';
		$ph['cke_lang_toolbar_title'] = $_lang['cke_lang_toolbar_title'];
		$ph['cke_lang_toolbar_message'] = $_lang['cke_lang_toolbar_message'];
		$ph['toolbarOptions'] = $toolbarOptions;
		
		$gsettings = file_get_contents("{$cke_path}inc/view_gsettings.inc");
		foreach($ph as $name => $value)
		{
			$name = '[+' . $name . '+]';
			$gsettings = str_replace($name, $value, $gsettings);
		}
		
	return $gsettings;
	}
	
	function get_cke_script()
	{
		$params = $this->params;
		$modx_base_url = MODX_BASE_URL;
		$cke_path = "{$modx_base_url}assets/plugins/ckeditor/";
		$editor_css_path = ($params['editor_css_path']!=='') ? $params['editor_css_path'] : "{$cke_path}ckeditor/contents.css";
		$width  = (!empty($params['width'])) ? str_replace("px","",$params['width']) : "100%";
		$height = (!empty($params['height'])) ? $params['height'] : "600px";
	
		if($params['frontend']=='false' || ($params['frontend']=='true' && $params['webuser']))
		{
			if($params['use_browser']==1)
			{
				$allowrb = true;
			}
		}
	
		// build ck instances
	    $connector_path = MODX_BASE_URL . 'manager/media/browser/mcpuk/connectors/php/connector.php';
	    $cke_query = "?Connector={$connector_path}&ServerPath={$modx_base_url}&editor=tinymce3&editorpath={$cke_path}";
	    $rbpath_base  = "{$modx_base_url}manager/media/browser/mcpuk/browser.php{$cke_query}";
		$rbpath_image = "{$rbpath_base}&Type=images";
		$rbpath_flase = "{$rbpath_base}&Type=flash";
		$rbpath_link  = "{$rbpath_base}&Type=files";
		foreach($this->params['elements'] as $ckInstance)
		{
			$ckInstances[] = '<script language="javascript" type="text/javascript">';
			$ckInstances[] = "CKEDITOR.replace( '{$ckInstance}',";
			$ckInstances[] = '{';
			$ckInstances[] = "filebrowserBrowseUrl      : '{$rbpath_base}',";
			$ckInstances[] = "filebrowserImageBrowseUrl : '{$rbpath_image}',";
			$ckInstances[] = "filebrowserFlashBrowseUrl : '{$rbpath_flase}',";
			$ckInstances[] = "baseHref     : '" . MODX_SITE_URL . "',";
			$ckInstances[] = "contentsCss     : '{$editor_css_path}',";
			$ckInstances[] = "height     : '{$params['height']}',";
			if (!empty($params['format_tags']))
			{
				$ckInstances[] = "format_tags     : '{$params['format_tags']}',";
			}
			$ckInstances[] = "customConfig : '{$modx_base_url}assets/plugins/ckeditor/read_config.php?q=user_config.js'";
			$ckInstances[] = '}';
			$ckInstances[] = ' );';
			$ckInstances[] = '</script>';
		}
		
		$ck_path = "{$modx_base_url}assets/plugins/ckeditor/ckeditor/ckeditor.js";
		$script[] = '		<script language="javascript" type="text/javascript" src="' . $ck_path . '"></script>';
		$script[] = '		<script language="javascript" type="text/javascript">';
		$script[] = '			function CKeditor_OnComplete(edtInstance) {';
		$script[] = '				if (edtInstance){ // to-do: add better listener';
		$script[] = '					edtInstance.AttachToOnSelectionChange(tvOnCKChangeCallback);';
		$script[] = '				}';
		$script[] = '			};';
		$script[] = '';
		$script[] = '			function tvOnCKChangeCallback(edtInstance) {';
		$script[] = '				if (edtInstance) {';
		$script[] = '					elm = edtInstance.LinkedField;';
		$script[] = '					if(elm && elm.onchange) elm.onchange();';
		$script[] = '				}';
		$script[] = '			}';
		$script[] = '		</script>';
		$script[] = join("\n",$ckInstances);
		return join("\n", $script);
	}
	
	function get_cke_lang($lang)
	{
		switch($lang)
		{
			case 'danish'       : $lc = 'da';break;
			case 'finnish'      : $lc = 'fi';break;
			case 'francais'     :
			case 'francais-utf8': $lc = 'fr';break;
			case 'german'       : $lc = 'de';break;
			case 'italian'      : $lc = 'it';break;
			case 'japanese-utf8': $lc = 'ja';break;
			case 'nederlands'   : $lc = 'nl';break;
			case 'persian'      : $lc = 'fa';break;
			case 'polish'       : $lc = 'pl';break;
			case 'portuguese'   : $lc = 'pt';break;
			case 'russian'      :
			case 'russion-UTF8' : $lc = 'ru';break;
			case 'spanish'      : $lc = 'es';break;
			case 'svenska'      :
			case 'svenska-utf8' : $lc = 'sv';break;
			case 'simple_chinese-gb2312': $lc = 'zh-cn';break;
			default             : $lc = 'en';
		}
		return $lc;
	}
}