<?php
$ap=1;

Header("Content-Type: application/rss+xml");

### ïîäêëş÷àåì ôóíêöèè
include_once 'system/head.php';

if (!empty($config['use_rss'])) {

### íàñòğàèâàåìûå ïàğàìåòğû
// òåìà îôîğìëåíèÿ. Íàõîäèòñÿ â ïàïêå data/tpl/system/rss - åñëè íå ğàçáèğàåòåñü, ëó÷øå íå òğîãàòü ;)

### âûâîä rss
include root_directory.'/show_feed_rss.php'; // ãëàâíûé ôàéë rss



} else {
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="'.$config['charset'].'" ?>';
?>
<rss version="2.0"
xmlns:content="http://purl.org/rss/1.0/modules/content/"
xmlns:wfw="http://wellformedweb.org/CommentAPI/"
xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
<title><?php echo $config['home_title']; ?>
</title>
<link><?php echo $config['http_home_url']; ?></link>
<description><?php echo $config['description']; ?></description>
<language>ru</language>
<generator><?php echo $config['version_name'].' '.$config['version_id']; ?></generator>

<item>
<title><?php echo t('RSS Ëåíòà.'); ?></title>
<description><?php echo t('Â äàííûé ìîìåíò RSS ëåíòà íåàêòèâèğîâàíà.'); ?></description>
<pubDate><?php echo date("D, j M Y H:m:s O"); ?></pubDate>
<dc:creator><?php echo $config['home_author']; ?></dc:creator>
<guid isPermaLink="false"><?php echo $config['http_home_url']; ?></guid>
<link><?php echo $config['http_home_url']; ?></link>
<comments><?php echo $config['http_home']; ?></comments>
<wfw:commentRSS><?php echo $config['http_home_url']; ?></wfw:commentRSS>
</item>

</channel>
</rss>
<?
}
?>