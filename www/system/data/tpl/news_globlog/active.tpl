<!-- Начало новости -->
										
						<div class="title" id="news<?php echo $tpl['post']['id']; ?>">
                                                <div class="comments">
                                               <font style="color:#6ac015;"><?php if (!empty($tpl['post']['if-right-have'])){ ?>Действие: <a href="system/index.php?mod=editnews&amp;id=<?php echo $tpl['post']['id']; ?>" title="Редактировать новость">edit</a> / <a href="system/index.php?mod=editnews&amp;action=delete&amp;selected_news[]=<?php echo $tpl['post']['id']; ?>" title="Удалить новость">del</a><?php } ?> Новость добавил: <?php echo $tpl['post']['author']; ?>. Просмотров: <?php echo $tpl['post']['views']; ?> | <span title="Рейтинг/Голос: <?php echo tpl('rating')."/".$tpl['post']['votes']; ?>">Рейтинг: <?php echo tpl('rating'); ?> |</span><?php if ($tpl['post']['comments'] and $config['use_comm']){ ?> <a href="<?php echo $tpl['post']['link']['post']; ?>#comments" title="Комментарии к новости">Комментариев: <?php echo $tpl['post']['comments']; ?></a> |<?php } ?> 
                                               <a href="<?php echo $tpl['post']['link']['home/print.php/post']; ?>" title="<?php echo $tit." - ".$tpl['post']['title']; ?> - Версия для печати">Печать</a></font>
                                                </div>
                                                <div class="date"><?php echo $tpl['post']['date']; ?></div>
                                                <div class="clear"></div>

                                                <div class="side_left_5">
                                                    <div class="side_right_5">
                                                        <div class="side_top_5">
                                                            <div class="side_bot_5">
                                                                <div class="left_top_5">
                                                                    <div class="right_top_5">
                                                                        <div class="left_bot_5">
                                                                            <div class="right_bot_5">
                                                                                <h3><a href="<?php echo $tpl['post']['link']['post']; ?>" title="<?php echo $tit." - ".$tpl['post']['title']; ?>" class="theme"><?php echo $config['delitel']." ".$tpl['post']['title']." ".$config['delitel']; ?></a></h3>
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
<?php echo $tpl['post']['short-story']; ?>
<div class="clear">{f-news-<?php echo $tpl['post']['id']; ?>-1-main-f} 
{f-news-<?php echo $tpl['post']['id']; ?>-2-main-f} 
{f-news-<?php echo $tpl['post']['id']; ?>-3-main-f} 
{f-news-<?php echo $tpl['post']['id']; ?>-4-main-f} 
{f-news-<?php echo $tpl['post']['id']; ?>-5-main-f}</div>
<div class="clear"></div>
</div>


<!-- Конец одной новости -->