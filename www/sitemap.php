<?php
#_strawberry 1.2

$ap = 1; // указатель админ панели. (admin panel marker)
include_once 'system/head.php'; // система strawberry.

header ('Content-type: text/xml; charset=UTF-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>


<urlset xmlns="http://www.google.com/schemas/sitemap/0.84"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84
http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">
<url>
<loc><?php echo $config['http_home']; ?></loc>
<lastmod><?php echo date('Y-m-d\TH:i:s\Z', time); ?></lastmod>
<changefreq>always</changefreq>
<priority>1.0</priority>
</url>
<?php


$arr_query = $db->sql_query("SELECT * FROM ".$config['dbprefix']."news
WHERE hidden=0 AND date < ".time." ORDER BY date DESC 
LIMIT 0, 4000");

while($row = $db->sql_fetchrow($arr_query)) {
$row['category'] = substr(substr($row['category'], 0, -1), 1);

    foreach ((!empty($rufus_file) ? $rufus_file : parse_ini_file(rufus_file, true)) as $type_k => $type_v){
        if (is_array($type_v)){
            foreach ($type_v as $k => $v){
            
                if ($type_k == 'home'){
                    $tpl['post']['link'][$k] = straw_get_link($row, $k);
                } else {
#zakr skobka
                    $tpl['post']['link'][$type_k.'/'.$k] = straw_get_link($row, $k, $type_k);
                }
                
            }
        }
    }

?>
<url>
<loc><?php echo $config['http_home']; ?>/<?php echo $tpl['post']['link']['post']; ?></loc>
<lastmod><?php echo date( 'Y-m-d\TH:i:s\Z', $row['date']); ?></lastmod>
<changefreq>daily</changefreq>
<priority>0.8</priority>
</url>
<?php
}
?>
</urlset>