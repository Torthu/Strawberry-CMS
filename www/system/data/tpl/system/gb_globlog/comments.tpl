<div class="title" id="gb">
                                                <div class="comments">
                                             
                                                </div>
                                                <div class="date"></div>
                                                <div class="clear"></div>

                                                <div class="side_left_3">
                                                    <div class="side_right_3">
                                                        <div class="side_top_3">
                                                            <div class="side_bot_3">
                                                                <div class="left_top_3">
                                                                    <div class="right_top_3">
                                                                        <div class="left_bot_3">
                                                                            <div class="right_bot_3">
                                                                         
<div class="comhead"> 
<h3><a name="<?php echo $tpl['gb']['id']; ?>"></a>№<?php echo $tpl['gb']['number']; ?> <a href="javascript:insertext('[b]<?php echo $tpl['gb']['author']; ?>[/b], ', '', 'short')"><font class="newstitle">» <?php echo $tpl['gb']['author']; ?></font></a> <small><?php if ($tpl['gb']['homepage'] or $tpl['gb']['email'] or $tpl['gb']['icq']) { ?>(<?php if ($tpl['gb']['email']) {?><a href="mailto:<?php echo $tpl['gb']['email']; ?>">MAIL</a><?php } if ($tpl['gb']['homepage'] and $tpl['gb']['email']) { ?>, <?php } if ($tpl['gb']['homepage']){ ?><a href="go.php?go=<?php echo $tpl['gb']['homepage']; ?>">URL</a><?php } if ($tpl['gb']['homepage'] and $tpl['gb']['icq']) { ?>, <?php } if ($tpl['gb']['icq']){ ?> <a target="_blank" href="go.php?go=wwp.icq.com/scripts/search.dll?to=<?php echo $tpl['gb']['icq']; ?>"><?php echo $tpl['gb']['icq']; ?></a><?php } ?>) <?php } ?></small></h3>
<div class="datum"><?php echo $tpl['gb']['date']; ?></div>
</div>


                                                                            </div>
																			
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
											 

                                            <div class="comment_box">
								<table border="0" width="100%" cellspacing="2" cellpadding="2" class="nbtext">
							<tr>
								<td align="center" valign="top" width="70"><?php echo $tpl['gb']['avatar']; ?></td>
								<td valign="top"><?php echo $tpl['gb']['mesagge']; ?></td>
							</tr>

<?php if ($tpl['gb']['admin']['answer']) { ?>
<tr><td colspan="2">
<table width="70%" align="right" class="nbtext"><tr><td><b class="admin"><?php echo $tpl['gb']['admin']['name']; ?></b> отвечает:<br><?php echo $tpl['gb']['admin']['answer']; ?></td></tr></table>
</td></tr>
<?php } ?>
<?php if ($tpl['gb']['if-right-have']){ ?>
<tr><td class="sep" colspan="2"></td></tr>
<tr><td class="nbtext" colspan="2">» действие:
<a href="system/index.php?mod=gb&amp;act=view&amp;id=<?php echo $tpl['gb']['id']; ?>" target="_blank" title="Редактировать запись">edit</a>
<a href="system/index.php?mod=gb&amp;act=delete&amp;id=<?php echo $tpl['gb']['id']; ?>" target="_blank" title="Удалить комментарий">del</a>
</td></tr>
<?php } ?>
								</table>

<div class="clear"></div>
</div>


