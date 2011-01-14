<?php
# strawberry
if (!defined("str_adm")) { header("Location: ../../../index.php"); exit; }

$database = array(

// users
'users'        => array(
'date'         => array('type' => 'int'),
'usergroup'    => array('type' => 'int', 'default' => 0),
'username'     => array('type' => 'string'),
'password'     => array('type' => 'string'),
'name'         => array('type' => 'string'),
'mail'         => array('type' => 'string'),
'publications' => array('type' => 'int', 'default' => 0),
'hide_mail'    => array('type' => 'bool'),
'avatar'       => array('type' => 'string'),
'last_visit'   => array('type' => 'int'),
'homepage'     => array('type' => 'string'),
'icq'          => array('type' => 'int'),
'location'     => array('type' => 'string'),
'about'        => array('type' => 'text'),
'lj_username'  => array('type' => 'string'),
'lj_password'  => array('type' => 'string'),
'id'           => array(
	              'type'           => 'int',
	              'auto_increment' => 1,
	              'primary'        => 1
			),
'deleted'      => array('type' => 'bool', 'default' => 0)
),

// categories
'categories'  => array(
'id'          => array('type' => 'int', 'primary' => 1),
'name'        => array('type' => 'string'),
'icon'        => array('type' => 'string'),
'url'         => array('type' => 'string'),
'parent'      => array('type' => 'int', 'default' => 0),
'level'       => array('type' => 'int', 'default' => 0),
'template'    => array('type' => 'string'),
'description' => array('type' => 'text'),
'usergroups'  => array('type' => 'string'),
'numb' => array('type' => 'int'),
'modul'  => array('type' => 'string')
),

// comments
'comments' => array(
'date'     => array('type' => 'int'),
'author'   => array('type' => 'string'),
'mail'     => array('type' => 'string'),
'homepage' => array('type' => 'string'),
'ip'       => array('type' => 'string'),
'comment'  => array('type' => 'text'),
'reply'    => array('type' => 'text'),
'post_id'  => array('type' => 'int'),
'user_id'  => array('type' => 'int', 'default' => 0),
'parent'   => array('type' => 'int', 'default' => 0),
'level'    => array('type' => 'int', 'default' => 0),
'id'       => array(
           'type'           => 'int',
           'auto_increment' => 1,
           'primary'        => 1
           ),
'admin'    => array('type' => 'text')
),




// news
'news'     => array(
'date'     => array('type' => 'int'),
'author'   => array('type' => 'string'),
'title'    => array('type' => 'string'),
'short'    => array('type' => 'int', 'default' => 0),
'full'     => array('type' => 'int', 'default' => 0),
'avatar'   => array('type' => 'string'),
'category' => array('type' => 'string'),
'url'      => array('type' => 'string'),
'id'       => array(
           'type'           => 'int',
           'auto_increment' => 1,
           'primary'        => 1
           ),
'views'    => array('type' => 'int', 'default' => 0),
'comments' => array('type' => 'int', 'default' => 0),
'hidden'   => array('type' => 'bool', 'default' => 0),
'sticky'   => array('type' => 'bool', 'default' => 0),
'keywords'   => array('type' => 'string'),
'type' => array('type' => 'string'),
'parent'   => array('type' => 'int'),
'level' => array('type' => 'int'),
'password' => array('type' => 'string'),
'rating'   => array('type' => 'int'),
'votes' => array('type' => 'int'),
'template' => array('type' => 'string')
),


// ipban
'ipban' => array(
'ip'    => array('type' => 'string'),
'count' => array('type' => 'int', 'default' => 0)
),

// flood
'flood'   => array(
'date'    => array('type' => 'int'),
'ip'      => array('type' => 'string'),
'post_id' => array('type' => 'int', 'primary' => 1)
),

// story
'story'   => array(
'post_id' => array('type' => 'int', 'primary' => 1),
'short'   => array('type' => 'text'),
'full'    => array('type' => 'text'),
'metatitle'   => array('type' => 'text'),
'metakeywords'    => array('type' => 'text'),
'metadescription'   => array('type' => 'text'),
'ico'    => array('type' => 'text'),
'add_comm'    => array('type' => 'text'),
'stop_comm'    => array('type' => 'text'),
'format'    => array('type' => 'string'),
'two'    => array('type' => 'text'),
'three'    => array('type' => 'text'),
),

// keywords
'keywords'  => array(
'id'          => array(
                'type'           => 'int',
                'auto_increment' => 1,
                'primary'        => 1
                ),
'name'        => array('type' => 'string'),
'url'      => array('type' => 'string')
),

// usergroups
'usergroups'  => array(
'id'          => array(
                'type'           => 'int',
                'auto_increment' => 1,
                'primary'        => 1
                ),
'name'        => array('type' => 'string'),
'access'      => array('type' => 'text'),
'permissions' => array('type' => 'text')
),

// money
'money'      => array(
'to'         => array('type' => 'string'),
'from'       => array('type' => 'string'),
'motivation' => array('type' => 'text'),
'money'      => array('type' => 'string'),
'date'       => array('type' => 'int')
),

//lang
'lang' => array(
'id'   => array('type' => 'string', 'permanent' => 1),
'name' => array('type' => 'string'),
'text' => array('type' => 'text')
),

// blocks
'blocks'     => array(
'bid'       => array('type' => 'int', 'auto_increment' => 1, 'primary' => 1),
'title'    => array('type' => 'string'),
'bposition'    => array('type' => 'string'),
'weight'    => array('type' => 'int'),
'active'    => array('type' => 'int'),
'blockfile'    => array('type' => 'string'),
'view'    => array('type' => 'text'),
'which'    => array('type' => 'string')
),

// count_pages
'count_ip'     => array(
'id_page'       => array('type' => 'int', 'auto_increment' => 1, 'primary' => 1),
'name'    => array('type' => 'text'),
'id_site'    => array('type' => 'int')
),

// count_ip
'count_ip'     => array(
'id_ip'       => array('type' => 'int', 'auto_increment' => 1, 'primary' => 1),
'ip'    => array('type' => 'text'),
'putdate'    => array('type' => 'text'),
'id_page'    => array('type' => 'int'),
'referer'    => array('type' => 'text')
),

// mail
'mail'     => array(
'id'       => array('type' => 'int', 'primary' => 1),
'namesender'    => array('type' => 'string'),
'emailsender'    => array('type' => 'string'),
'isqsender'    => array('type' => 'int'),
'sitesender'    => array('type' => 'string'),
'subjectsender'    => array('type' => 'string'),
'textsender'    => array('type' => 'text'),
'ipsender'    => array('type' => 'string'),
'agentsender'    => array('type' => 'text'),
'date'     => array('type' => 'int'),
'activ'     => array('type' => 'int')
),

// gb
'gb'     => array(
'id'     => array('type' => 'int', 'primary' => 1),
'namesender'    => array('type' => 'string'),
'emailsender'    => array('type' => 'string'),
'isqsender'    => array('type' => 'int'),
'sitesender'    => array('type' => 'string'),
'textsender'    => array('type' => 'text'),
'ipsender'    => array('type' => 'string'),
'date'     => array('type' => 'int'),
'activ'     => array('type' => 'int'),
'admin'    => array('type' => 'string'),
'answer'    => array('type' => 'text')
),

// fields
'fields'  => array(
'fid'      => array('type' => 'int', 'primary' => 1),
'modul'   => array('type' => 'string'),
'content_id' => array('type' => 'int'),
'fname'   => array('type' => 'text'),
'fvalue'  => array('type' => 'text'),
'fnum'    => array('type' => 'int'),
'status'  => array('type' => 'int'),
'add_one' => array('type' => 'text'),
'add_two' => array('type' => 'text')
),

// session
'session' => array(
'id'    => array('type' => 'int', 'primary' => 1),
'cod' => array('type' => 'string'),
'putdate' => array('type' => 'datetime'),
'user'    => array('type' => 'text'),
'ip'    => array('type' => 'text'),
'iwh'    => array('type' => 'text'),
'dop'    => array('type' => 'text')
),

// links
'links' => array(
  'id' => array(
           'type'           => 'int',
           'auto_increment' => 1,
           'primary'        => 1
           ),
  'date' => array('type' => 'int'), // дата добавления
  'name' => array('type' => 'string'), // название сайта
  'url' => array('type' => 'string'), 
  'feed' => array('type' => 'string'), //адрес RSS
  'banner' => array('type' => 'string'), // банер 88х31
  'banner2' => array('type' => 'string'), // другой баннер ,например 468 х60
  'mail' => array('type' => 'string'), // мыло юзера оставившего ссылку
  'linkcat' => array('type' => 'string'), // категория (мона и без них)
  'description'=> array('type' => 'text'), // описание сайта
  'publication' => array('type' => 'bool'), // если "1" значит выполняется условие  
  'project' => array('type' => 'bool'),  // если "1" значит выполняется еще одно условие
  'who'=> array('type' => 'text'), // описание сайта
  'ip'=> array('type' => 'text') // описание сайта
),

//linkcats
'linkcats' => array(
'id' => array('type' => 'int'),
  'name' => array('type' => 'string'),
  'url' => array('type' => 'string'),
  'parent' => array('type' => 'int'),
  'level' => array('type' => 'string'),
  'description' => array('type' => 'text')
),

//sonnic
'sonnic' => array(
'id' => array('type' => 'int', 'auto_increment' => 1, 'primary' => 1),
  'name' => array('type' => 'text'),
  'valid' => array('type' => 'text')
),

);
?>