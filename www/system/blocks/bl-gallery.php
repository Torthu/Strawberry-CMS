<?php
#_strawberry
if (!defined("str_block")) {
header("Location: ../../index.php");
exit;
}
$names = array();
$img_way = ".".way("images/blocks/random");

//read folder
$folder=@opendir($img_way); 
while ($file = @readdir($folder)) {
$names[count($names)] = $file; 
}
@closedir($folder);

//sort file names in array
sort($names);

//remove any non-images from array
$tempvar=0;
for ($i=1;$i<count($names);$i++){
$ext=strtolower(substr($names[$i],-4));
if ($ext==".jpg"||$ext==".gif"||$ext=="jpeg"||$ext==".png"){$names1[$tempvar]=$names[$i];$tempvar++;}
}

//random
srand ((double) microtime() * 10000000);
$rand_keys = array_rand ($names1, 2);

//random image from array
$slika=$names1[$rand_keys[0]]; 

//image dimensions

$dimensions = GetImageSize($img_way."/".$slika);

$bl_out = "<center><img border=\"0\" src=\"".way("".$img_way."/".$slika."")."\" width=\"".$dimensions[0]."\" height=\"".$dimensions[1]."\" title=\"".$config['description']."\" alt=\"".$config['description']."\"></center>";

?>