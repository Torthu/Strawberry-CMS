<!-- Начало новости -->
										
										<div class="title">
                                                <div class="comments">
<font style="color:#6ac015;"><nobr><form name="cnpostrating" method="post" style="margin: 0; padding: 0;">
просмотров: <?php echo $tpl['post']['views'];
 if ($tpl['post']['comments'] and $config['use_comm']){ ?> | <a href="<?php echo $tpl['post']['link']['post']; ?>#comments">комментарии (<?php echo $tpl['post']['comments']; ?>)</a><?php } ?> | <a href="<?php echo $tpl['post']['link']['home/print.php/post']; ?>" title="<?php echo $tit." - ".$tpl['post']['title']; ?> - Версия для печати">печать</a> | <a href="<?php echo $tpl['post']['link']['trackback.php/post']; ?>">trackback</a> | <span title="Рейтинг/Голос: <?php echo tpl('rating')."/".$tpl['post']['votes']; ?>">рейтинг: <?php echo tpl('rating'); ?></span> <?php if ( !rating('check') ) { ?>| оценить новость: <select size="1" name="rating_<?php echo $tpl['post']['id']; ?>">
 <option value="0">0</option>
 <option value="1">1</option>
 <option value="2">2</option>
 <option value="3">3</option>
 <option value="4">4</option>
 <option value="5">5</option>
</select><input class="ratingok" type="submit" value="ok">
<?php } ?>
                                              </form></nobr></font>
                                                </div>
                                                <div class="date">(<?php echo $tpl['post']['date']; ?>) <?php if ($tpl['post']['pages']){ ?>(<?php echo $tpl['post']['pages']; ?>)<?php } ?></div>
                                                <div class="clear"></div>

                                                <div class="side_left_5">
                                                    <div class="side_right_5">
                                                        <div class="side_top_5">
                                                            <div class="side_bot_5">
                                                                <div class="left_top_5">
                                                                    <div class="right_top_5">
                                                                        <div class="left_bot_5">
                                                                            <div class="right_bot_5">
                                                                                <h3><a href="<?php echo $tpl['post']['link']['post']; ?>" title="<?php echo $tpl['post']['author']." ".$config['delitel']." ".$tpl['post']['title']; ?>"><?php echo $tpl['post']['title']; ?></a></h3>
                                                                            </div>
									</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text_box">
<?php echo (!empty($tpl['post']['full-story']) ? $tpl['post']['full-story'] : $tpl['post']['short-story']); ?>

<?php if (!empty($tpl['post']['attachment'])) { ?><br><br><b>» Прикреплённые файлы:</b><?php echo $tpl['post']['attachment']; ?><br><?php } ?>

<div class="clear">{f-news-<?php echo $tpl['post']['id']; ?>-1-main-f} 
{f-news-<?php echo $tpl['post']['id']; ?>-2-main-f} 
{f-news-<?php echo $tpl['post']['id']; ?>-3-main-f} 
{f-news-<?php echo $tpl['post']['id']; ?>-4-main-f} 
{f-news-<?php echo $tpl['post']['id']; ?>-5-main-f}</div>

<?php if (!empty($tpl['post']['if-right-have'])){ ?><br><br>
<hr>Действие: <a href="system/index.php?mod=editnews&amp;id=<?php echo $tpl['post']['id']; ?>" title="Редактировать новость">Редактировать новость</a> / <a href="system/index.php?mod=editnews&amp;action=delete&amp;selected_news[]=<?php echo $tpl['post']['id']; ?>" title="Удалить новость">Удалить новость</a><hr>
<?php } ?>
<div class="clear"></div>


                                            </div>
											<div class="text_box">
											
											</div>
											<!-- Конец одной новости -->
<div class="b_font"></div>