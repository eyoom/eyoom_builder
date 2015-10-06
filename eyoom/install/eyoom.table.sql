-- --------------------------------------------------------

--
-- Table structure for table `g5_eyoom_respond`
--

DROP TABLE IF EXISTS `g5_eyoom_respond`;
CREATE TABLE IF NOT EXISTS `g5_eyoom_respond` (
  `rid` int(11) NOT NULL auto_increment,
  `bo_table` varchar(20) NOT NULL default '',
  `pr_id` mediumint(11) NOT NULL,
  `wr_id` int(11) NOT NULL default '0',
  `wr_cmt` int(11) NOT NULL default '0',
  `wr_subject` varchar(255) NOT NULL default '',
  `wr_mb_id` varchar(20) NOT NULL,
  `mb_id` varchar(20) NOT NULL,
  `mb_name` varchar(255) NOT NULL,
  `re_cnt` mediumint(3) NOT NULL default '0',
  `re_type` varchar(20) NOT NULL,
  `re_chk` tinyint(4) NOT NULL default '0',
  `regdt` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`rid`),
  KEY `mb_id` (`wr_mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g5_eyoom_member`
--

DROP TABLE IF EXISTS `g5_eyoom_member`;
CREATE TABLE IF NOT EXISTS `g5_eyoom_member` (
  `mb_id` varchar(30) NOT NULL,
  `level` mediumint(5) NOT NULL default '1',
  `level_point` mediumint(11) NOT NULL default '0',
  `photo` varchar(100) NOT NULL,
  `myhome_cover` varchar(100) NOT NULL,
  `main_index` enum('index','mypage','myhome','shop') NOT NULL default 'index',
  `mypage_main` enum('respond','timeline','favorite','followinggul') NOT NULL default 'respond',
  `myhome_hit` mediumint(11) NOT NULL default '0',
  `open_page` enum('y','n') NOT NULL default 'y',
  `respond` mediumint(5) NOT NULL default '0',
  `onoff_push` enum('on','off') NOT NULL default 'on',
  `onoff_push_respond` enum('on','off') NOT NULL default 'on',
  `onoff_push_memo` enum('on','off') NOT NULL default 'on',
  `onoff_push_social` enum('on','off') NOT NULL default 'on',
  `onoff_push_likes` enum('on','off') NOT NULL default 'on',
  `onoff_push_guest` enum('on','off') NOT NULL default 'on',
  `onoff_social` enum('on','off') NOT NULL default 'on',
  `open_email` enum('y','n') NOT NULL default 'y',
  `open_homepage` enum('y','n') NOT NULL default 'y',
  `open_tel` enum('y','n') NOT NULL default 'y',
  `open_hp` enum('y','n') NOT NULL default 'y',
  `view_timeline` char(1) NOT NULL default '1',
  `view_favorite` char(1) NOT NULL default '1',
  `view_followinggul` char(1) NOT NULL default '1',
  `favorite` text NOT NULL,
  `following` longtext NOT NULL,
  `follower` longtext NOT NULL,
  `likes` longtext NOT NULL,
  UNIQUE KEY `mb_id` (`mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g5_eyoom_new`
--

DROP TABLE IF EXISTS `g5_eyoom_new`;
CREATE TABLE IF NOT EXISTS `g5_eyoom_new` (
  `bn_id` int(11) NOT NULL auto_increment,
  `bo_table` varchar(20) NOT NULL default '',
  `pr_id` int(10) NOT NULL,
  `wr_id` int(11) NOT NULL default '0',
  `wr_parent` int(11) NOT NULL default '0',
  `ca_name` varchar(255) NOT NULL,
  `wr_subject` varchar(255) NOT NULL,
  `wr_option` set('html1','html2','secret','mail') NOT NULL,
  `wr_content` text NOT NULL,
  `wr_image` text NOT NULL,
  `wr_video` text NOT NULL,
  `wr_sound` text NOT NULL,
  `wr_comment` int(11) NOT NULL default '0',
  `wr_hit` int(11) NOT NULL default '0',
  `wr_good` int(11) NOT NULL default '0',
  `mb_id` varchar(20) NOT NULL default '',
  `mb_name` varchar(50) NOT NULL,
  `mb_nick` varchar(50) NOT NULL,
  `mb_level` varchar(255) NOT NULL,
  `bn_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `wr_1` varchar(255) NOT NULL,
  `wr_2` varchar(255) NOT NULL,
  `wr_3` varchar(255) NOT NULL,
  `wr_4` varchar(255) NOT NULL,
  `wr_5` varchar(255) NOT NULL,
  `wr_6` varchar(255) NOT NULL,
  `wr_7` varchar(255) NOT NULL,
  `wr_8` varchar(255) NOT NULL,
  `wr_9` varchar(255) NOT NULL,
  `wr_10` varchar(255) NOT NULL,
  PRIMARY KEY  (`bn_id`),
  KEY `mb_id` (`mb_id`),
  KEY `wr_hit` (`wr_hit`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g5_eyoom_banner`
--

DROP TABLE IF EXISTS `g5_eyoom_banner`;
CREATE TABLE IF NOT EXISTS `g5_eyoom_banner` (
  `bn_no` int(10) unsigned NOT NULL auto_increment,
  `bn_type` enum('intra','extra') NOT NULL default 'intra',
  `bn_location` mediumint(3) NOT NULL default '0',
  `bn_link` text,
  `bn_img` varchar(100) NOT NULL default '',
  `bn_target` varchar(20) NOT NULL default '',
  `bn_code` text NOT NULL,
  `bn_sort` int(10) default '0',
  `bn_theme` varchar(30) NOT NULL default 'default',
  `bn_state` smallint(1) NOT NULL default '0',
  `bn_period` char(1) NOT NULL default '1',
  `bn_start` varchar(10) NOT NULL,
  `bn_end` varchar(10) NOT NULL,
  `bn_exposed` mediumint(10) NOT NULL default '0',
  `bn_clicked` mediumint(10) NOT NULL default '0',
  `bn_regdt` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`bn_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g5_eyoom_activity`
--

DROP TABLE IF EXISTS `g5_eyoom_activity`;
CREATE TABLE IF NOT EXISTS `g5_eyoom_activity` (
  `act_id` mediumint(11) unsigned NOT NULL auto_increment,
  `mb_id` varchar(40) NOT NULL,
  `act_type` varchar(20) NOT NULL,
  `act_contents` text NOT NULL,
  `act_image` text NOT NULL,
  `act_regdt` datetime NOT NULL,
  PRIMARY KEY  (`act_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g5_eyoom_board`
--

DROP TABLE IF EXISTS `g5_eyoom_board`;
CREATE TABLE IF NOT EXISTS `g5_eyoom_board` (
  `bo_id` mediumint(11) unsigned NOT NULL auto_increment,
  `bo_table` varchar(20) NOT NULL,
  `gr_id` varchar(10) NOT NULL,
  `bo_theme` varchar(50) NOT NULL,
  `bo_skin` varchar(40) NOT NULL,
  `use_gnu_skin` enum('y','n') NOT NULL default 'n',
  `use_shop_skin` enum('y','n') NOT NULL default 'n',
  `bo_use_profile_photo` char(1) NOT NULL default '1',
  `bo_sel_date_type` enum('1','2') NOT NULL default '1',
  `bo_use_hotgul` char(1) NOT NULL default '1',
  `bo_use_anonymous` char(1) NOT NULL default '2',
  `bo_use_infinite_scroll` char(1) NOT NULL default '2',
  `bo_use_point_explain` char(1) NOT NULL default '1',
  `bo_cmtpoint_target` char(1) NOT NULL default '1',
  `bo_firstcmt_point` int(7) NOT NULL default '0',
  `bo_firstcmt_point_type` char(1) NOT NULL default '1',
  `bo_bomb_point` int(7) NOT NULL default '0',
  `bo_bomb_point_type` char(1) NOT NULL default '1',
  `bo_bomb_point_limit` int(3) NOT NULL default '10',
  `bo_bomb_point_cnt` int(2) NOT NULL default '1',
  `bo_lucky_point` int(7) NOT NULL default '0',
  `bo_lucky_point_type` char(1) NOT NULL default '1',
  `bo_lucky_point_ratio` int(2) NOT NULL default '1',
  PRIMARY KEY (`bo_id`),
  KEY `bo_table` (`bo_table`),
  KEY `bo_theme` (`bo_theme`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g5_eyoom_guest`
--

DROP TABLE IF EXISTS `g5_eyoom_guest`;
CREATE TABLE IF NOT EXISTS `g5_eyoom_guest` (
  `gu_no` mediumint(11) unsigned NOT NULL auto_increment,
  `mb_id` varchar(40) NOT NULL,
  `gu_id` varchar(40) NOT NULL,
  `gu_name` varchar(20) NOT NULL,
  `content` text NOT NULL,
  `gu_regdt` datetime NOT NULL,
  PRIMARY KEY  (`gu_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g5_eyoom_menu`
--

DROP TABLE IF EXISTS `g5_eyoom_menu`;
CREATE TABLE IF NOT EXISTS `g5_eyoom_menu` (
  `me_id` int(11) NOT NULL auto_increment,
  `me_theme` varchar(20) NOT NULL,
  `me_code` varchar(255) NOT NULL default '',
  `me_name` varchar(255) NOT NULL default '',
  `me_icon` varchar(100) NOT NULL,
  `me_shop` char(1) NOT NULL default '2',
  `me_path` varchar(255) NOT NULL,
  `me_type` varchar(30) NOT NULL,
  `me_pid` varchar(40) NOT NULL,
  `me_link` varchar(255) NOT NULL default '',
  `me_target` varchar(255) NOT NULL default '',
  `me_order` int(11) NOT NULL default '0',
  `me_side` enum('y','n') NOT NULL default 'y',
  `me_use` enum('y','n') NOT NULL default 'y',
  `me_use_nav` enum('y','n') NOT NULL default 'y',
  PRIMARY KEY  (`me_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g5_eyoom_theme`
--

DROP TABLE IF EXISTS `g5_eyoom_theme`;
CREATE TABLE IF NOT EXISTS `g5_eyoom_theme` (
  `tm_name` varchar(40) NOT NULL,
  `tm_alias` varchar(20) NOT NULL,
  `tm_code` varchar(100) NOT NULL,
  `tm_host` varchar(100) NOT NULL,
  `tm_mb_id` varchar(50) NOT NULL,
  `tm_ordno` varchar(20) NOT NULL,
  `tm_time` varchar(20) NOT NULL,
  UNIQUE KEY `tm_name` (`tm_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g5_eyoom_link`
--

DROP TABLE IF EXISTS `g5_eyoom_link`;
CREATE TABLE IF NOT EXISTS `g5_eyoom_link` (
  `s_no` int(11) unsigned NOT NULL auto_increment,
  `bo_table` varchar(20) collate utf8_unicode_ci NOT NULL,
  `wr_id` int(11) unsigned NOT NULL default '0',
  `theme` varchar(40) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`s_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;