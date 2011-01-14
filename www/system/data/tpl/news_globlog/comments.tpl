<div class="comment" style="margin-left: <?php echo ($tpl['comment']['level'] * 20); ?>px;">
<div id="comment<?php echo $tpl['comment']['id']; ?>" class="<?php echo $tpl['comment']['alternating']; ?>">

<div class="title">
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
<h3><a name="<?php echo $tpl['comment']['id']; ?>"></a> <a href="javascript:insertext('[b]<?php echo $tpl['comment']['author']; ?>[/b], ', '', 'commin')"><font class="newstitle">» <?php echo $tpl['comment']['author']; ?></font></a> <small><?php if ($tpl['comment']['homepage'] or $tpl['comment']['mail']) { ?>(<?php if ($tpl['comment']['mail']) {?><a href="mailto:<?php echo $tpl['comment']['mail']; ?>">MAIL</a><?php } if ($tpl['comment']['homepage'] and $tpl['comment']['mail']) { ?>, <?php } if ($tpl['comment']['homepage']){ ?><a href="go.php?go=<?php echo $tpl['comment']['homepage']; ?>">URL</a><?php } ?>) : <?php } ?></small></h3>
<div class="datum"><?php echo $tpl['comment']['date']; ?></div>
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
						<table border="0" width="100%" cellspacing="2" cellpadding="2">
							<tr>
								<td align="center" valign="top" width="70"><?php echo $tpl['comment']['avatar']; ?></td>
								<td valign="top" align="left"><?php echo $tpl['comment']['story']; ?></td></tr>

<?php if ($tpl['comment']['answer']) { ?>
<tr><td colspan="2" style="padding: 0px 0px 0px 80px;">
<table width="70%" class="nbtext"><tr><td><b class="admin"><?php echo $tpl['comment']['admin']; ?></b> отвечает:<br><?php echo $tpl['comment']['answer']; ?></td></tr></table>
</td></tr>
<?php } ?>

<tr><td class="attr" colspan="2" align="left">» действие:
<?php if (!empty($tpl['comment']['if-right-have'])){ ?>
<a href="system/index.php?mod=comments&amp;act=view&amp;id=<?php echo $tpl['comment']['id']; ?>" target="_blank" title="Редактировать комментарий">правка</a>
<a href="system/index.php?mod=comments&amp;act=delete&amp;id=<?php echo $tpl['comment']['id']; ?>&amp;nid=<?php echo $tpl['comment']['post_id']; ?>" target="_blank" title="Удалить комментарий">удалить</a>
<?php } ?>
<?php if (!empty($member['name']) and $member['name'] != $tpl['comment']['author']){ ?>
<a href="#" id="reply<?php echo $tpl['comment']['id']; ?>" onclick="quickreply(<?php echo $tpl['comment']['id']; ?>); return false;" title="Ответить на комментарий от <?php echo $tpl['comment']['author']; ?>">ответить</a>
<?php } ?>
</td></tr>									
						</table>

<div class="clear"></div>
</div>

</div>
</div>