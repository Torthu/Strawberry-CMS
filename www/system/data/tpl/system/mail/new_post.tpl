<?php
// inc/mod/addnews.mdu
?>

Subject: Новая публикация на сайте <?php echo $config['home_title']; ?>

<?php echo langdate($config['timestamp_comment'], $added_time); ?> добавлена новая новость от пользователя <?php echo $member['username']; ?>.

<?php echo $title; ?>

<?php echo replace_news('admin', $short_story); ?>

--
<?php echo $config['http_home'].'/'.$config['home_page']; ?>?id=<?php echo $id; ?>