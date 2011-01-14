<?php if (!empty($_GET['comm'])) { ?>
<table cellpadding="2" cellspacing="2" width="98%" align="center" class="cntnt">
<tr><td class="text"><?php echo $tpl['comment']['number']; ?> » <b><?php echo $tpl['comment']['author']; ?></b> <small><?php if (!empty($tpl['comment']['homepage']) or !empty($tpl['comment']['mail'])) { ?>(<?php if (!empty($tpl['comment']['mail'])) { echo $tpl['comment']['mail']; } if (!empty($tpl['comment']['homepage']) and !empty($tpl['comment']['mail'])) { ?>, <?php } if (!empty($tpl['comment']['homepage'])){  echo $tpl['comment']['homepage']; } ?>) : <?php } ?>(<?php echo $tpl['comment']['date']; ?>)</small></td></tr>
<tr><td class="nbtext"><?php echo $tpl['comment']['story']; ?></td></tr>
</table>
<br>
<?php } ?>