#_strawberry
# table create

CREATE TABLE IF NOT EXISTS `{pref}blocks` (
  `bid` int(10) NOT NULL auto_increment,
  `title` varchar(60) default NULL,
  `bposition` char(1) default NULL,
  `weight` int(10) NOT NULL default '1',
  `active` int(1) NOT NULL default '1',
  `blockfile` varchar(255) default NULL,
  `view` text NOT NULL,
  `which` varchar(255) default NULL,
  PRIMARY KEY  (`bid`),
  KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=14;

CREATE TABLE IF NOT EXISTS `{pref}categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `icon` varchar(255) default NULL,
  `url` varchar(255) default NULL,
  `parent` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `template` varchar(255) default NULL,
  `description` text,
  `usergroups` varchar(255) default NULL,
  `numb` int(11) NOT NULL,
  `modul` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=4;

CREATE TABLE IF NOT EXISTS `{pref}comments` (
  `date` int(11) NOT NULL,
  `author` varchar(255) default NULL,
  `mail` varchar(255) default NULL,
  `homepage` varchar(255) default NULL,
  `ip` varchar(255) default NULL,
  `comment` text,
  `reply` text,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  `admin` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `{pref}count_ip` (
  `id_ip` int(32) NOT NULL auto_increment,
  `ip` text,
  `putdate` text,
  `id_page` int(10) default NULL,
  `referer` text,
  PRIMARY KEY  (`id_ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `{pref}count_pages` (
  `id_page` int(10) NOT NULL auto_increment,
  `name` text,
  `id_site` int(4) default NULL,
  PRIMARY KEY  (`id_page`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `{pref}fields` (
  `fid` int(11) NOT NULL auto_increment,
  `modul` varchar(255) default NULL,
  `content_id` int(11) NOT NULL,
  `fname` text,
  `fvalue` text,
  `fnum` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `add_one` text,
  `add_two` text,
  PRIMARY KEY  (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `{pref}flood` (
  `date` int(11) NOT NULL,
  `ip` varchar(255) default NULL,
  `post_id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `{pref}gb` (
  `id` int(11) NOT NULL auto_increment,
  `namesender` varchar(255) default NULL,
  `emailsender` varchar(255) default NULL,
  `isqsender` int(11) NOT NULL,
  `sitesender` varchar(255) default NULL,
  `textsender` text,
  `ipsender` varchar(20) default NULL,
  `date` int(11) NOT NULL,
  `activ` int(11) NOT NULL,
  `admin` varchar(255) default NULL,
  `answer` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `{pref}golink` (
`id` INT(11) NOT NULL auto_increment,
`link` VARCHAR(500) NOT NULL,
`referer` VARCHAR(500) DEFAULT '0',
`counter` INT(11) DEFAULT '0',
`date` VARCHAR(255) DEFAULT '0' NOT NULL,
`ip` VARCHAR(255) DEFAULT '0.0.0.0' NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `{pref}ipban` (
  `ip` varchar(255) default NULL,
  `count` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE IF NOT EXISTS `{pref}keywords` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `url` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `{pref}lang` (
  `id` varchar(255) default NULL,
  `name` varchar(255) default NULL,
  `text` text,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE IF NOT EXISTS `{pref}mail` (
  `id` int(11) NOT NULL auto_increment,
  `namesender` varchar(255) default NULL,
  `emailsender` varchar(255) default NULL,
  `isqsender` int(11) NOT NULL,
  `sitesender` varchar(255) default NULL,
  `subjectsender` varchar(255) default NULL,
  `textsender` text default NULL,
  `ipsender` varchar(20) default NULL,
  `agentsender` text,
  `date` int(11) NOT NULL,
  `activ` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `{pref}money` (
  `to` varchar(255) default NULL,
  `from` varchar(255) default NULL,
  `motivation` text,
  `money` varchar(255) default NULL,
  `date` int(11) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE IF NOT EXISTS `{pref}news` (
  `date` int(11) NOT NULL,
  `author` varchar(255) default NULL,
  `title` varchar(255) NOT NULL,
  `c_short` int(225) NOT NULL,
  `c_full` int(225) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  `views` int(11) NOT NULL,
  `comments` int(11) NOT NULL,
  `hidden` tinyint(1) NOT NULL,
  `sticky` tinyint(1) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL,
  `votes` int(11) NOT NULL,
  `template` varchar(255) NOT NULL,
  `bookmark` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `{pref}session` (
  `id` int(11) NOT NULL  auto_increment,
  `cod` varchar(100) NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `user` text NOT NULL,
  `name` varchar(225) NOT NULL,
  `ip` text NOT NULL,
  `iwh` text NOT NULL,
  `dop` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `{pref}story` (
  `post_id` int(11) NOT NULL auto_increment,
  `short` text NOT NULL,
  `full` longtext NOT NULL,
  `metatitle` text NOT NULL,
  `metakeywords` text NOT NULL,
  `metadescription` text NOT NULL,
  `ico` text NOT NULL,
  `add_comm` text,
  `stop_comm` text,
  `format` varchar(15) NOT NULL default 'html_with_br',
  `two` text NOT NULL,
  `three` text NOT NULL,
  PRIMARY KEY  (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `{pref}usergroups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `access` text,
  `permissions` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=5;

CREATE TABLE IF NOT EXISTS `{pref}users` (
  `date` int(11) NOT NULL,
  `usergroup` int(11) NOT NULL,
  `username` varchar(255) default NULL,
  `password` varchar(255) default NULL,
  `name` varchar(255) default NULL,
  `mail` varchar(255) default NULL,
  `publications` int(11) NOT NULL,
  `hide_mail` tinyint(1) NOT NULL,
  `avatar` varchar(255) default NULL,
  `last_visit` int(11) NOT NULL,
  `homepage` varchar(255) default NULL,
  `icq` int(11) NOT NULL,
  `location` varchar(255) default NULL,
  `about` text,
  `lj_username` varchar(255) default NULL,
  `lj_password` varchar(255) default NULL,
  `id` int(11) NOT NULL auto_increment,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1;
