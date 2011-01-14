<?php
// inc/show.add-comment.php
?>

Subject: Новый комментарий от <?php echo $name; ?>

URL: <?php echo $homepage; ?>/<?php echo straw_get_link($row); ?>

Заголовок: <?php echo $row['title']; ?>

Имя: <?php echo $name; ?>

IP: <?php echo getip(); ?>

E-mail: <?php echo $mail; ?>

Homepage: <?php echo $homepage; ?>


Комментарий:
------------
<?php echo str_replace('<br>', "\n", $commin_story); ?>

Редактировать:
<?php echo $config['http_script_dir']; ?>/?mod=editcomments&action=editcomment&newsid=<?php echo $id; ?>&comid=<?php echo $comid; ?>

Удалить:
<?php echo $config['http_script_dir']; ?>/?mod=editcomments&action=doeditcomment&newsid=<?php echo $id; ?>&delcomid[]=<?php echo $comid; ?>&deletecomment=yes