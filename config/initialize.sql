CREATE TABLE IF NOT EXISTS `pmc_article` (
  `no` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `board_id` int(11) unsigned NOT NULL,
  `category` varchar(20) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `writer_id` int(11) unsigned NOT NULL,
  `top_no` int(11) unsigned DEFAULT NULL,
  `order_key` tinytext,
  `is_secret` tinyint(1) NOT NULL DEFAULT '0',
  `is_notice` tinyint(1) NOT NULL DEFAULT '0',
  `allow_comment` tinyint(1) NOT NULL DEFAULT '1',
  `upload_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `files` tinytext,
  PRIMARY KEY (`no`),
  KEY `board_id` (`board_id`),
  KEY `writer_id` (`writer_id`),
  KEY `top_no` (`top_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `pmc_article_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_no` int(11) unsigned NOT NULL,
  `content` tinytext NOT NULL,
  `writer_id` int(11) unsigned NOT NULL,
  `regtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `writer_id` (`writer_id`),
  KEY `article_no` (`article_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `pmc_board` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `name_kr` varchar(20) NOT NULL,
  `categorys` tinytext,
  `read_permission` tinytext,
  `comment_permission` tinytext,
  `write_permission` tinytext,
  `extra_vars` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_en` (`name`)
) ENGINE=InnoDB;

INSERT INTO `pmc_board` (`id`, `name`, `name_kr`, `categorys`, `read_permission`, `comment_permission`, `write_permission`, `extra_vars`) VALUES
	(1, 'freeboard', '자유게시판', NULL, '["*"]', '["*"]', '["*"]', NULL),
	(2, 'notice', '공지사항', NULL, '["*"]', '["*"]', '["*"]', NULL);


CREATE TABLE IF NOT EXISTS `pmc_login_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `input_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `succeed` tinyint(1) NOT NULL,
  `auto_login` tinyint(1) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


REATE TABLE IF NOT EXISTS `pmc_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `title_kr` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `level` int(1) unsigned NOT NULL,
  `is_index` tinyint(1) NOT NULL DEFAULT '0',
  `css_property` tinytext COLLATE utf8_unicode_ci,
  `css_hover_property` tinytext COLLATE utf8_unicode_ci,
  `css_active_property` tinytext COLLATE utf8_unicode_ci,
  `module` tinytext COLLATE utf8_unicode_ci,
  `action` tinytext COLLATE utf8_unicode_ci,
  `extra_vars` tinytext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

INSERT INTO `pmc_menu` (`id`, `title`, `title_kr`, `level`, `is_index`, `css_property`, `css_hover_property`, `css_active_property`, `module`, `action`, `extra_vars`) VALUES
	(1, 'home', '홈', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
	(2, 'notice', '공지사항', 1, 0, NULL, NULL, NULL, 'board', NULL, NULL),
	(3, 'freeboard', '자유게시판', 1, 0, NULL, NULL, NULL, 'board', NULL, NULL);


CREATE TABLE IF NOT EXISTS `pmc_session` (
  `session_key` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `expire_time` datetime NOT NULL,
  `ip_address` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(10) unsigned NOT NULL,
  `extra_vars` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`session_key`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `pmc_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `input_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'SHA256',
  `password_salt` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'MD5',
  `nick_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `user_name` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `email_address` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `permission` tinytext COLLATE utf8_unicode_ci,
  `last_logined_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `extra_vars` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`input_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

INSERT INTO `pmc_user` (`id`, `input_id`, `password`, `password_salt`, `nick_name`, `user_name`, `email_address`, `phone_number`, `permission`, `last_logined_ip`, `extra_vars`) VALUES
	(1, 'tester', '875bdbdd2cdb7326981de9c27bf9d76d52c75cd9bb1299417b1135b69a748b69', 'f98c94ebb87dc80be2a26991e3d5cc62', 'Tester', '테스터', 'tester@parmeter.kr', '010-1234-5678', NULL, '127.0.0.1', NULL);


ALTER TABLE `pmc_article`
  ADD CONSTRAINT `pmc_article_ibfk_1` FOREIGN KEY (`board_id`) REFERENCES `pmc_board` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pmc_article_ibfk_2` FOREIGN KEY (`writer_id`) REFERENCES `pmc_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pmc_article_ibfk_3` FOREIGN KEY (`top_no`) REFERENCES `pmc_article` (`no`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pmc_article_comment`
  ADD CONSTRAINT `pmc_article_comment_ibfk_1` FOREIGN KEY (`article_no`) REFERENCES `pmc_article` (`no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pmc_article_comment_ibfk_2` FOREIGN KEY (`writer_id`) REFERENCES `pmc_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
