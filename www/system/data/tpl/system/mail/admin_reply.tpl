<?php
// inc/mod/editcomments.mdu
?>

Subject: Ответ на комментарий

Здравствуйте, <?php echo $poster; ?>!

Вы <?php echo langdate('d M Y \в H:i', $date); ?> оставили комментарий на сайте <?php echo $config['home_title']; ?> (<?php echo $config['http_home'].'/'.$config['home_page']; ?>).
Вам был написан ответ.

Комментарий:
<?php echo $poster; ?>> <?php echo $commin_story; ?>
Ответ:
<?php echo $reply; ?>

--
Ответить или прочитать все комментарии вы можете по этому адресу <?php echo straw_get_link($row); ?>

Большое спасибо за ваш комментарий.