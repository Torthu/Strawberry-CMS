<?php
if (!defined("str_modul")) {
	header("Location: ../../../index.php");
	exit;
}
// mail form v.2.5 for strawberry 1.1.1
### Параметры модуля
$tit = t("Обратная связь");
otable();
?>

<div class="nbtext"><?php echo t('Вы можете связаться с нами следующим образом'); ?>:</div>
<div class="nbtext">
<?php echo t('С главным администратором'); ?>:
<br><?php echo t('Имя'); ?>: Admin
<br><?php echo t('E-Mail'); ?>: admin@<?php echo str_replace("www.", "", $_SERVER['HTTP_HOST']); ?>
<br><?php echo t('ICQ'); ?>: xxx-xxx-xxx
<br><?php echo t('Телефон'); ?>: 000-000000 (phone); 000-000000 (fax)
</div>

<div><?php include includes_directory."/mail.inc.php"; ?></div>

<?php 
ctable();
echo on_page($modul); 
?>