<?php
#_strawberry
if (!defined("str_adm")) { header("Location: ../../index.php"); exit; }

/**
 * @package Plugins
 * @access public
 */

function LoadActivePlugins(){
global $gplugin;
	foreach (active_plugins() as $plugin_filename => $active){
		$path = plugins_directory.'/'.$plugin_filename;

		if (is_file($path)){
			$plugin_data = getcontents($path);
			preg_match("{Application:(.*)}i", $plugin_data, $plugin['application']);

			if (strtolower(trim($plugin['application'][1])) == 'strawberry'){
				include $path;
			} else {
				disable_plugin($plugin_filename);
			}
		} else {
			disable_plugin($plugin_filename);
		}
	}
}





function plugin_enabled($plugin_filename){
	$plugins = active_plugins();
	if (!empty($plugin_filename) and !empty($plugins[$plugin_filename])){
		return true;
	} else {
		return false;
	}
}





function enable_plugin($plugin_filename){
	$plugins = active_plugins();
	$plugins[$plugin_filename] = true;
	savearray($plugins, active_plugins_file);
}




function disable_plugin($plugin_filename){
	$plugins = active_plugins();
	unset($plugins[$plugin_filename]);
	savearray($plugins, active_plugins_file);
}




/* List Plugins */
function available_plugins(){

	$plugins = filefolderlist(plugins_directory, 1);
	$plugins = $plugins['file'];

	if (!empty($plugins)){
		foreach ($plugins as $pluginfile){
			$plugin_data = getcontents($pluginfile);
			preg_match("{Plugin Name:(.*)}i", $plugin_data, $plugin['name']);
			preg_match("{Plugin URI:(.*)}i", $plugin_data, $plugin['uri']);
			preg_match("{Description:(.*)}i", $plugin_data, $plugin['description']);
			preg_match("{Author:(.*)}i", $plugin_data, $plugin['author']);
			preg_match("{Author URI:(.*)}i", $plugin_data, $plugin['author_uri']);
			preg_match("{Version:(.*)}i", $plugin_data, $plugin['version']);
			preg_match("{Application:(.*)}i", $plugin_data, $plugin['application']);
			preg_match("{Required Framework:(.*)}i", $plugin_data, $plugin['framework']);

			$required_version = !empty($plugin['framework'][1]) ? trim($plugin['framework'][1]) : "";
			$application = (!empty($plugin['application'][1]) ? trim($plugin['application'][1]) : '');

			// Skip plugins that need a better framework
			if (!empty($required_version) and version_compare(plugin_framework_version, $required_version, '<')){
				$compatible = false;
			} else {
				$compatible = true;
			}

			// Skip plugins designed for other systems
			if (strtolower($application) != 'strawberry'){
				continue;
			}

			$available_plugins[] = array(
			'name'  	  => t(trim($plugin['name'][1])),
			'uri'		  => (!empty($plugin['uri'][1]) ? trim($plugin['uri'][1]) : ''),
			'description'	  => t(trim($plugin['description'][1])),
			'author'	  => t(trim($plugin['author'][1])),
			'author_uri'	  => (!empty($plugin['author_uri'][1]) ? trim($plugin['author_uri'][1]) : ''),
			'version'	  => (!empty($plugin['version'][1]) ? trim($plugin['version'][1]) : ''),
			'application'	  => trim($plugin['application'][1]),
			'file'		  => basename($pluginfile),
			'framework'	  => $required_version,
			'compatible'	  => $compatible,
			);
		}
	} else {
		$available_plugins = array();
	}
return $available_plugins;
}

function active_plugins(){
if (!is_file(active_plugins_file)){
   return array();
}
   $active_plugins = loadarray(active_plugins_file);
   return $active_plugins;
}

/* Actions And Filters */
function add_action($hook, $functionname, $priority = plugin_default_priority){
global $actions;
$actions[$hook][] = array('name' => $functionname, 'priority' => $priority);
}

function run_actions($hookname){
global $actions;
$buffer = "";
	if (empty($actions[$hookname])){
	return false;
	}
	usort($actions[$hookname], 'SortByActionPriority');
	foreach ($actions[$hookname] as $action){
		$buffer .= $action['name']($hookname);
	}
return $buffer;
}

function add_filter($hook, $functionname, $priority = plugin_default_priority){
global $filters;
$filters[$hook][] = array('name' => $functionname, 'priority' => $priority);
}

function run_filters($hookname, $tofilter){
global $filters;
if (!empty($filters[$hookname])){
	usort($filters[$hookname], 'SortByActionPriority');
	foreach ($filters[$hookname] as $filter){
		$tofilter = $filter['name']($tofilter, $hookname);
	}
}
return $tofilter;
}

function SortByActionPriority($a, $b){
	return ($a['priority'] > $b['priority'] ? 1 : -1);
}

/* File Functions */
function FileFolderList($path, $depth = 0, $current = '', $level=0){
	if ($level==0 and !@file_exists($path)){
		return false;
	}
	if (is_dir($path)){
		$handle = @opendir($path);
		if ($depth == 0 or $level < $depth)
			while($filename = @readdir($handle)){
				if ($filename != '.' and $filename != '..'){
					$current = @filefolderlist($path.'/'.$filename, $depth, $current, $level+1);
				}
			}
		@closedir($handle);
		$current['folder'][] = $path.'/'.$filename;
	} else {
		if (is_file($path)){
			$current['file'][] = $path;
		}
	}
return $current;
}

function LoadArray($pathandfilename){
if (is_file($pathandfilename)){
	@include($pathandfilename);
	return $array;
}
return array();
}

function WriteContents($contents, $filename){
return file_write($filename, $contents);
}

function GetContents($filename){
return file_read($filename);
}

function SaveArray($array, $filename) {
return array_save($filename, $array);
}

/* Data Handling Classes */
class PluginSettings {
	function PluginSettings($plugin_name=""){
	       if (!empty($plugin_name)) {
		$this->name = $plugin_name;
		$this->all_settings = loadarray(settings_file);
		$this->settings = $this->all_settings[$plugin_name];
		}
	}
	function save(){
		$this->all_settings[$this->name] = $this->settings;
		return savearray($this->all_settings, settings_file);
	}
	function delete(){
		unset($this->settings);
		return $this->save();
	}
}











#####################################################
#####################################################
class XFieldsData {
	function XFieldsData(){
		$this->file = xfields_file;
		$this->data = loadarray(xfields_file);
	}

	function fetch($news_id="", $field_name=""){
		return !empty($this->data[$news_id][$field_name]) ? $this->data[$news_id][$field_name] : "";
	}

	function set($value, $news_id, $field_name){
		$this->data[$news_id][$field_name] = $value;
	}

	function increment($news_id, $field_name){
		return $this->data[$news_id][$field_name]++;
	}

	function decrement($news_id, $field_name){
		return $this->data[$news_id][$field_name]--;
	}

	function delete($news_id){
		unset($this->data[$news_id]);
	}

	function deletefield($news_id, $field_name){
		unset($this->data[$news_id][$field_name]);
	}

	function deletevalue($news_id, $field_name, $value){
		unset($this->data[$news_id][$field_name][$value]);
	}

	function save(){
		return SaveArray($this->data, $this->file);
	}
}
#####################################################
#####################################################






?>