<?php
#_strawberry
if (!defined("str_block")) {
header("Location: ../../index.php");
exit;
}
$bl_out = "<form method=\"post\" action=\"\">";
$bl_out .= t('Выбрать тему сайта:')."<br>";
$sys_con_theme_arr = array();

    $handle = opendir(themes_directory);
    while ($file = readdir($handle)){
        if ($file != '.' and $file != '..' and is_dir(themes_directory.'/'.$file)){
            $sys_con_theme_arr[$file] = $file;
        }
    }

$bl_out .= makeDropDown($sys_con_theme_arr, 'sel_theme', $config['mytheme'], 'style="width:100px;"');
$bl_out .= "<input type=\"submit\" value=\" ".t('Выбрать')." \" />";
$bl_out .= "</form>";

$bl_out .= "<br /><form method=\"post\" action=\"\">";
$bl_out .= t('Выбрать язык сайта:')."<br>";
$sys_con_lang_arr = array();

    $lhandle = opendir(languages_directory);
    while ($lfile = readdir($lhandle)){
        if ($lfile != '.' and $lfile != '..' and is_dir(languages_directory.'/'.$lfile)){
            $sys_con_lang_arr[$lfile] = $lfile;
        }
    }

$bl_out .= makeDropDown($sys_con_lang_arr, 'lang', $config['lang'], 'style="width:100px;"');
$bl_out .= "<input type=\"submit\" value=\" ".t('Выбрать')." \" />";
$bl_out .= "</form>";

?>