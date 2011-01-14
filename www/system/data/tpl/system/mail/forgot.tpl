Subject: Ваш пароль от сайта <?php echo $config['home_title']; ?>

Здравствуйте!

Кто-то, возможно вы, воспользовался формой восстановления пароля для аккаунта <?php echo $row['username']; ?> на сайте <?php echo $config['home_title']; ?> (<?php echo $config['http_home'].'/'.$config['home_page']; ?>).

Если вы пройдёте по следующей ссылке, вы тем самым подтвердите намеренье сменить старый пароль на новый: <?php echo $new_password; ?>

Позже пароль можно будет поменять в профайле (<?php echo $config['http_script_dir']; ?>/?mod=account&act=profil).

Ссылка для подтверждения восстановления пароля:
<?php echo $activation_url; ?>