
<?php
echo "<div class=\"numbers\" id=\"numbers\"><div class=\"num_text\" id=\"num_text\">".t('Всего')." <span>".$count."</span> ".t('по')." <span>".$number."</span> ".t('на странице')."</div>";
?>

<p class="sideoversikt">
<?php if (!empty($tpl['prev-next']['prev-link'])){ ?><a title="<?php echo t('Перейти на предыдущую страницу'); ?>" href="<?php echo $tpl['prev-next']['prev-link']; ?>">&laquo;</a><?php } ?> 
<?php echo $tpl['prev-next']['pages']; ?> 
<?php if (!empty($tpl['prev-next']['next-link'])){ ?><a title="<?php echo t('Перейти на следущую страницу'); ?>" href="<?php echo $tpl['prev-next']['next-link']; ?>">&raquo;</a><?php } ?>
</p>

<?php
echo "</div>";
?>