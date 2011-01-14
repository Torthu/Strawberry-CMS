<?php
#_strawberry
if (!defined("str_plug")) {
header("Location: ../../../index.php");
exit;
}

if (function_exists('ddb_get')){
	if ($ddb == 'html' and straw_get_rights('ddb', 'read')){
		exit(highlight_string(ob_get_clean(), true));
	}

	$dragdropblocks = array();
	$dragdropblocks['output'] = ddb_get(ob_get_clean());
	$dragdropblocks['blocks'] = new PluginSettings('Blocks');
	$dragdropblocks['blocks'] = $dragdropblocks['blocks']->settings;

	dragdropblocks(blocks_directory);
	preg_match_all('/\<\!--block:(.*?)--\>/i', $dragdropblocks['output'], $matches);

	foreach ($matches[1] as $dragdropblocks['position']){
	    if ($dragdropblocks['blocks'][$dragdropblocks['position']]){
	        ob_start();

	        foreach (explode('|', $dragdropblocks['blocks'][$dragdropblocks['position']]) as $dragdropblocks['section']){
	        	if ($ddb == 'edit' and straw_get_rights('ddb', 'read')){
	            	echo '<dfn style="font-style: normal;" itemID="'.$dragdropblocks['section'].'">';
	            	echo '<div class="dragblock">['.$dragdropblocks['section'].']';
	            	echo  '['.t('<a href="%url">редактировать</a>', array('url' => $config['http_script_dir'].'?plugin=ddb&action=block&block='.$dragdropblocks['section'])).']';
	            	echo '</div>';
	            	include $dragdropblocks['block'][$dragdropblocks['section']];
	            	echo '</dfn>';
	            } else {
	            	include $dragdropblocks['block'][$dragdropblocks['section']];
	            }
	        }

	        $dragdropblocks['result'][$dragdropblocks['position']] = ob_get_clean();
	    }

		$dragdropblocks['include'] = blocks_directory.'/'.$dragdropblocks['position'];

		if (is_dir($dragdropblocks['include'])){
			if ($ddb == 'edit'){
				$dragdropblocks['result'][$dragdropblocks['position']] .= t('В этой секции есть автоматически подключающиеся блоки.<br /><a href="%url">Перейти в редактирование содержания блоков</a>.', array('url' => $config['http_script_dir'].'?plugin=ddb&action=category&category='.$dragdropblocks['position']));
			} else {
	            $handle = opendir($dragdropblocks['include']);
	            while ($file = readdir($handle)){
	                if ($file != '.' and $file != '..' and is_dir($dragdropblocks['include'].'/'.$file)){
	                    $dragdropblocks['include'] = $dragdropblocks['include'].'/'.$file;

	                    ob_start();

	                    if (file_exists($dragdropblocks['include'].'/'.$_GET[$file].'.block')){
	                        include $dragdropblocks['include'].'/'.$_GET[$file].'.block';
	                    } else {
	                    	if (file_exists($dragdropblocks['include'].'/else.block')){
	                        	include $dragdropblocks['include'].'/else.block';
	                        }
	                    }

	                    $dragdropblocks['result'][$dragdropblocks['position']] .= ob_get_clean();
	                }
	            }
			}
	    }

        if ($ddb == 'edit' and straw_get_rights('ddb', 'read')){
	    	$dragdropblocks['output'] = str_replace('<!--block:'.$dragdropblocks['position'].'-->',

            '<div class="blockheader">['.$dragdropblocks['position'].']</div>'.
	    	'<div id="'.$dragdropblocks['position'].'" class="block">'.$dragdropblocks['result'][$dragdropblocks['position']].'</div>', $dragdropblocks['output']);
	    } else {
	    	$dragdropblocks['output'] = str_replace('<!--block:'.$dragdropblocks['position'].'-->', $dragdropblocks['result'][$dragdropblocks['position']], $dragdropblocks['output']);
	    }
	}

	echo $dragdropblocks['output'];
}
?>