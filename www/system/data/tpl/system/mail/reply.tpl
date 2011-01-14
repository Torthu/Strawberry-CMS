<?php
// inc/show.add-comments.php
?>

Subject: <?php echo $name; ?> ответил на ваш комментарий

Здравствуйте!

Вы оставляли комментарий к «<?php echo $row['title']; ?>», <?php echo $name; ?> на него ответил.

Комментарий:
------------
<?php echo str_replace('<br>', "\n", $commin_story); ?>

Посмотреть можно здесь <?php echo straw_get_link($row); ?>