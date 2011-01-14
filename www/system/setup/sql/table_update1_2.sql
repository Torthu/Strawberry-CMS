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

ALTER TABLE `{pref}categories`
ADD `numb` int(11) NOT NULL;

ALTER TABLE `{pref}categories`
ADD `modul` varchar(255) default NULL;

ALTER TABLE `{pref}comments`
ADD `admin` text;

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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

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

CREATE TABLE IF NOT EXISTS `{pref}keywords` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `url` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

ALTER TABLE `{pref}news` 
ADD `keywords` varchar(255) NOT NULL;

ALTER TABLE `{pref}news`
ADD  `type` varchar(255) NOT NULL;

ALTER TABLE `{pref}news`
ADD  `parent` int(11) NOT NULL;

ALTER TABLE `{pref}news`
ADD  `level` int(11) NOT NULL;

ALTER TABLE `{pref}news`
ADD  `password` varchar(255) NOT NULL;

ALTER TABLE `{pref}news` 
ADD  `rating` int(11) NOT NULL;

ALTER TABLE `{pref}news`
ADD  `votes` int(11) NOT NULL;

ALTER TABLE `{pref}news` 
ADD  `template` varchar(255) NOT NULL;

ALTER TABLE `{pref}news` 
ADD  `bookmark` int(1) NOT NULL;

ALTER TABLE `{pref}news` 
CHANGE `short` `ñ_short` INT(225);

ALTER TABLE `{pref}news`
CHANGE `full` `ñ_full` INT(225);

ALTER TABLE `{pref}story` 
CHANGE `post_id` `post_id` INT(11) NOT NULL AUTO_INCREMENT;

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


ALTER TABLE `{pref}story`
CHANGE `full` `full` LONGTEXT;

ALTER TABLE `{pref}story`
ADD  `metatitle` text NOT NULL;

ALTER TABLE `{pref}story`
ADD  `metakeywords` text NOT NULL;

ALTER TABLE `{pref}story`
ADD  `metadescription` text NOT NULL;

ALTER TABLE `{pref}story`
ADD  `ico` text NOT NULL;

ALTER TABLE `{pref}story`
ADD  `add_comm` text;

ALTER TABLE `{pref}story`
ADD  `stop_comm` text;

ALTER TABLE `{pref}story`
ADD  `format` varchar(15) NOT NULL default 'html_with_br';

ALTER TABLE `{pref}story`
ADD  `two` text NOT NULL;

ALTER TABLE `{pref}story`
ADD  `three` text NOT NULL;

