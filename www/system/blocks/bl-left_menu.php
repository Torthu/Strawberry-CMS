<?php
#_strawberry
if (!defined("str_block")) {
header("Location: ../../index.php");
exit;
}

$bl_out = "
� <a href=\"".way("".$config['home_page'])."\" title=\"".t('������� �������� �����')."\">".t('�������')."</a><br>
� <a href=\"".way("".$config['home_page']."?mod=news")."\" title=\"".t('�������')."\">".t('�������')."</a><br>
� <a href=\"".way("".$config['home_page']."?mod=useful")."\" title=\"".t('����������')."\">".t('����������')."</a><br>
� <a href=\"".way("".$config['home_page']."?mod=strawberry")."\" title=\"".t('������� �������� Strawberry 1.2')."\">".t('��� Strawberry 1.2')."</a><br>
� <a href=\"".way("".$config['home_page']."?mod=download")."\" title=\"".t('������� Strawberry')."\">".t('����� ������')."</a><br>
� <a href=\"".way("".$config['home_page']."?mod=myrobo")."\" title=\"".t('Strawberry`s knowledge base about robots')."\">".t('���� ������ � �������')."</a><br>
� <a href=\"".way("".$config['home_page']."?mod=gb")."\" title=\"".t('������ � �����')."\">".t('�������� �����')."</a><br>
� <a href=\"".way("".$config['home_page']."?mod=account&amp;act=users")."\" title=\"".t('������ ������������� �����')."\">".t('������������')."</a><br>
� <a href=\"".way("".$config['home_page']."?mod=search")."\" title=\"".t('����� �� �����')."\">".t('�����')."</a><br>
� <a href=\"".way("".$config['home_page']."?mod=sitemap")."\" title=\"".t('����������� ����� �����')."\">".t('����� �����')."</a><br>
� <a href=\"".way("".$config['home_page']."?mod=callback")."\" title=\"".t('�������� ��� ������')."\">".t('�������� �����')."</a><br>
<hr />
� <a href=\"http://update.strawberry.su/svn.php\" title=\"".t('SVN Strawberry 1.2')."\">".t('Strawberry SVN project')."</a><br>
� <a href=\"http://strawberry.goodgirl.ru/forum/topic/3446/\" title=\"".t('���� strawberry 1.2 �� ����������� �����')."\">".t('�����')." Strawberry 1.2.x</a><br>
� <a href=\"http://forum.cutenewsru.com/viewtopic.php?f=15&amp;t=6707\" title=\"".t('���� strawberry 1.2 ��')." CuteNews.Ru\">".t('�� ������')." CuteNews.Ru</a><br>
<hr />
� <a href=\"http://strawberry.goodgirl.ru/\" title=\"".t('����������� ���� ������������� Strawberry')."\">Strawberry 1.1.1</a><br>
� <a href=\"http://strawberry.goodgirl.ru/docs/\" title=\"".t('����������� ������������ ��� Strawberry 1.1.1')."\">".t('������������ ��� Strawberry 1.1.1')."</a><br>
� <a href=\"http://strawberry.goodgirl.ru/forum/\" title=\"".t('����������� �����')."\">".t('�����')."</a><br>
� <a href=\"http://strawberry.goodgirl.ru/forum/topic/1/\" title=\"".t('F.A.Q.')."\">".t('F.A.Q.')." - ".t('�����')."</a><br>
<hr />
� <a href=\"http://strawberry.goodgirl.ru/forum/topic/3542/\" title=\"".t('����� �������� - ����� �������������')."\">".t('����� ��������')."</a><br>";

?>