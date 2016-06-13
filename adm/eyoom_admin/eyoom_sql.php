<?php
if (!defined('_GNUBOARD_')) exit;
if ($is_admin != 'super') alert('최고관리자로 로그인 후 실행해 주십시오.', G5_URL);

if (defined('_EYOOM_VESION_')) {
	$eb_version = $eb->version_score(str_replace("EyoomBuilder_", "", _EYOOM_VESION_));
} else {
	$eb_version = $eb->version_score('1.0.6');
}

/**
 * EyoomBuilder_1.0.3
 */
if ($eb_version >= $eb->version_score('1.0.3')) {
	// 장바구니 상품 주문폼 등록시간 기록 필드 추가
	if(!sql_query(" select bo_use_anonymous from {$g5['eyoom_board']} limit 1 ", false)) {
		sql_query(" alter table `{$g5['eyoom_board']}` add `bo_use_anonymous` char(1) not null default '2' after `bo_use_hotgul` ", true);
	}
}

/**
 * EyoomBuilder_1.0.5
 */
if ($eb_version >= $eb->version_score('1.0.5')) {
	// 장바구니 상품 주문폼 등록시간 기록 필드 추가
	if(!sql_query(" select bo_firstcmt_point from {$g5['eyoom_board']} limit 1 ", false)) {
		$sql = "
			alter table `{$g5['eyoom_board']}`
				add `bo_use_point_explain` char(1) NOT NULL default '1' after `bo_use_anonymous`,
				add `bo_firstcmt_point` int(7) NOT NULL default '0' after `bo_use_point_explain`,
				add `bo_firstcmt_point_type` char(1) NOT NULL default '1' after `bo_firstcmt_point`,
				add `bo_bomb_point` int(7) NOT NULL default '0' after `bo_firstcmt_point_type`,
				add `bo_bomb_point_type` char(1) NOT NULL default '1' after `bo_bomb_point`,
				add `bo_bomb_point_limit` int(3) NOT NULL default '10' after `bo_bomb_point_type`,
				add `bo_bomb_point_cnt` int(2) NOT NULL default '1' after `bo_bomb_point_limit`,
				add `bo_lucky_point` int(7) NOT NULL default '0' after `bo_bomb_point_cnt`,
				add `bo_lucky_point_type` char(1) NOT NULL default '1' after `bo_lucky_point`,
				add `bo_lucky_point_ratio` int(2) NOT NULL default '1' after `bo_lucky_point_type`
		";
		sql_query($sql, true);
	}
}

/**
 * EyoomBuilder_1.0.6
 */
if ($eb_version >= $eb->version_score('1.0.6')) {
	// 이윰 New에 카테고리 분류명 기록 필드 추가
	if(!sql_query(" select ca_name from {$g5['eyoom_new']} limit 1 ", false)) {
		$sql = "alter table `{$g5['eyoom_new']}` add `ca_name` varchar(255) NOT NULL after `wr_parent`";
		sql_query($sql, true);
	}

	// 이윰메뉴에 폰트어썸 입력 필드 추가
	if(!sql_query(" select me_icon from {$g5['eyoom_menu']} limit 1 ", false)) {
		$sql = "alter table `{$g5['eyoom_menu']}` add `me_icon` varchar(100) NOT NULL after `me_name`";
		sql_query($sql, true);
	}
}

/**
 * EyoomBuilder_1.1.0
 */
if ($eb_version >= $eb->version_score('1.1.0')) {
	// 이윰메뉴의 me_type 속성 변경
	$sql = "ALTER TABLE `{$g5['eyoom_menu']}` CHANGE `me_type` `me_type` VARCHAR(30) NOT NULL;";
	sql_query($sql, true);

	// 이윰NEW 테이블에 추천필드 추가
	if(!sql_query(" select wr_good from {$g5['eyoom_new']} limit 1 ", false)) {
		// 필드 추가
		sql_query("alter table `{$g5['eyoom_new']}` add `wr_good` int(11) NOT NULL default 0 after `wr_hit`", true);

		// 오리지널 테이블의 추천값을 이윰NEW 테이블에 적용
		$sql = "select bn_id, bo_table, wr_id from `{$g5['eyoom_new']}` where wr_id = wr_parent order by bn_datetime asc";
		$res = sql_query($sql, false);
		for($i=0;$row=sql_fetch_array($res);$i++) {
			$temp_table = $g5['write_prefix'].$row['bo_table'];
			sql_query("update `{$g5['eyoom_new']}` as a set a.wr_good=(select b.wr_good from {$temp_table} as b where b.wr_id='{$row['wr_id']}') where a.bn_id='{$row['bn_id']}'", false);
		}
	}

	// 이윰NEW 테이블에 여유필드 추가
	if(!sql_query(" select wr_1 from {$g5['eyoom_new']} limit 1 ", false)) {
		$sql = "
			alter table  `{$g5['eyoom_new']}`
			add `wr_1` varchar( 255 ) not null,
			add `wr_2` varchar( 255 ) not null,
			add `wr_3` varchar( 255 ) not null,
			add `wr_4` varchar( 255 ) not null,
			add `wr_5` varchar( 255 ) not null,
			add `wr_6` varchar( 255 ) not null,
			add `wr_7` varchar( 255 ) not null,
			add `wr_8` varchar( 255 ) not null,
			add `wr_9` varchar( 255 ) not null,
			add `wr_10` varchar( 255 ) not null
		";
		sql_query($sql, false);
	}
}

/**
 * EyoomBuilder_1.1.1
 */
if ($eb_version >= $eb->version_score('1.1.1')) {
	// 이윰 짧은주소 테이블 생성
	$sql = "
		create table if not exists `{$g5['eyoom_link']}` (
		  `s_no` int(11) unsigned not null auto_increment,
		  `bo_table` varchar(20) collate utf8_unicode_ci not null,
		  `wr_id` int(11) unsigned not null default '0',
		  `theme` varchar(40) collate utf8_unicode_ci not null,
		  primary key  (`s_no`)
		) engine=myisam default charset=utf8
	";
	sql_query($sql, false);
}

/**
 * EyoomBuilder_1.1.2
 */
if ($eb_version >= $eb->version_score('1.1.2')) {
	// 이윰메뉴에 사이드 레이아웃 표시 여부 필드 추가
	if(!sql_query(" select me_side from {$g5['eyoom_menu']} limit 1 ", false)) {
		$sql = "alter table `{$g5['eyoom_menu']}` add `me_side` enum('y', 'n') not null default 'y' after `me_order`";
		sql_query($sql, true);
	}

	// 이윰 기본설정 파일에 필드추가
	$config_basic = G5_DATA_PATH.'/eyoom.config.php';
	unset($eyoom, $_eyoom);
	include($config_basic);
	if(is_array($eyoom)) {
		foreach($eyoom as $key => $val) {
			$_eyoom[$key] = $val;
			if($key == 'use_switcher' && !isset($eyoom['use_main_side_layout'])) {
				$_eyoom['use_main_side_layout'] = 'y';
				$_eyoom['use_sub_side_layout'] = 'y';
				$_eyoom['pos_side_layout'] = 'right';
			}
		}
		$qfile->save_file('eyoom',$config_basic,$_eyoom);
	}
}

/**
 * EyoomBuilder_1.1.3
 */
if ($eb_version >= $eb->version_score('1.1.3')) {
	// 이윰보드에 무한스크롤 사용여부 필드 추가
	if(!sql_query(" select bo_use_infinite_scroll from {$g5['eyoom_board']} limit 1 ", false)) {
		$sql = "alter table `{$g5['eyoom_board']}` add `bo_use_infinite_scroll` char(1) not null default '2' after `bo_use_anonymous`";
		sql_query($sql, true);
	}
}

/**
 * EyoomBuilder_1.1.7
 */
if ($eb_version >= $eb->version_score('1.1.7')) {
	// shop_theme 기본 설정 추가
	unset($eyoom, $_eyoom);
	$config_file = array();
	$tmp = dir(G5_DATA_PATH);
	while ($entry = $tmp->read()) {
		if (preg_match("/^eyoom[a-z_\.]*\.config\.php$/i", $entry))
			$config_file[] = $entry;
	}

	// 구매한 shop테마가 있는지 체크
	foreach($config_file as $file) {
		if(preg_match("/shop/",$file)) {
			unset($eyoom);
			include_once(G5_DATA_PATH.'/'.$file);

			// 있다면 해당 테마를
			$shop_theme = $eyoom['theme'];
		}
	}

	// 샵테마 설정 필드 & 쇼핑몰 메뉴 선택설정 필드 추가
	if(!empty($config_file) && is_array($config_file)) {

		foreach($config_file as $file) {
			unset($_eyoom,$eyoom);
			$cfg_file = G5_DATA_PATH.'/'.$file;

			include($cfg_file);
			if(is_array($eyoom)) {
				foreach($eyoom as $key => $val) {
					$_eyoom[$key] = $val;
					if($key == 'theme' && !isset($eyoom['shop_theme']) && $file == 'eyoom.config.php') {
						$_eyoom['shop_theme'] = $shop_theme;
					}
					if($key == 'use_eyoom_menu' && !isset($eyoom['use_eyoom_shopmenu'])) {
						$_eyoom['use_eyoom_shopmenu'] = 'n';
					}
				}
				$qfile->save_file('eyoom',$cfg_file,$_eyoom);
			}
		}
	}

	// 이윰보드에 댓글적립포인트 적용대상 필드 추가 : 게시판 레이아웃을 커뮤니티/쇼핑몰 선택할 수 있도록 필드 추가
	if(!sql_query(" select `bo_cmtpoint_target` from {$g5['eyoom_board']} limit 1 ", false)) {
		$sql = "
			alter table `{$g5['eyoom_board']}`
				add `bo_cmtpoint_target` char(1) not null default '1' after `bo_use_point_explain`,
				add `use_shop_skin` enum('y', 'n') not null default 'n' after `use_gnu_skin`
		";
		sql_query($sql, true);
	}

	// 이윰메뉴 테이블에 쇼핑몰메뉴인지 커뮤니티 메뉴인지 구분할 필드 추가
	if(!sql_query(" select `me_shop` from {$g5['eyoom_menu']} limit 1 ", false)) {
		$sql = "alter table `{$g5['eyoom_menu']}` add `me_shop` char(1) not null default '2' after `me_icon`";
		sql_query($sql, true);
	}
}

/**
 * EyoomBuilder_1.1.10
 */
if ($eb_version >= $eb->version_score('1.1.10')) {
	// 이윰보드에 동영상 목록이미지의 사용여부 필드 추가
	if(!sql_query(" select `bo_use_video_photo` from {$g5['eyoom_board']} limit 1 ", false)) {
		$sql = "
			alter table `{$g5['eyoom_board']}`
				add `bo_use_video_photo` char(1) not null default '2' after `bo_use_point_explain`,
				add `bo_use_list_image` char(1) not null default '1' after `bo_use_video_photo`,
				add `download_fee_ratio` tinyint(2) not null default '0' after `bo_lucky_point_ratio`,
				add `bo_use_yellow_card` tinyint(2) not null default '0' after `bo_use_list_image`,
				add `bo_blind_limit` tinyint(2) not null default '5' after `bo_use_yellow_card`,
				add `bo_blind_view` tinyint(2) not null default '10' after `bo_blind_limit`,
				add `bo_blind_direct` tinyint(2) not null default '10' after `bo_blind_view`
		";
		sql_query($sql, true);
	}

	// 이윰메뉴 구성시, 접근 허용 회원레벨 결정 필드 추가
	if(!sql_query(" select `me_permit_level` from {$g5['eyoom_menu']} limit 1 ", false)) {
		$sql = "alter table `{$g5['eyoom_menu']}` add `me_permit_level` tinyint(4) not null default '1' after `me_order` ";
		sql_query($sql, true);
	}

	// 게시물 신고 테이블 생성
	$yellow_card_table = G5_TABLE_PREFIX . 'eyoom_yellowcard';
	$yellow_card_sql = "
		CREATE TABLE IF NOT EXISTS `" . $yellow_card_table . "` (
		  `yc_id` int(11) unsigned NOT NULL auto_increment,
		  `bo_table` varchar(20) NOT NULL default '',
		  `wr_id` int(11) NOT NULL default '0',
		  `mb_id` varchar(20) NOT NULL default '',
		  `yc_reason` char(1) NOT NULL,
		  `yc_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
		  PRIMARY KEY  (`yc_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	";
	sql_query($yellow_card_sql, true);
}

/**
 * EyoomBuilder_1.1.11
 */
if ($eb_version >= $eb->version_score('1.1.11')) {
	// 게시물 신고 테이블에 댓글 pr_id 필드 추가
	if(!sql_query(" select `pr_id` from {$g5['eyoom_yellowcard']} limit 1 ", false)) {
		$sql = "alter table `{$g5['eyoom_yellowcard']}` add `pr_id` int(11) not null after `wr_id` ";
		sql_query($sql, true);
	}

	// 이윰보드에 EXIF 사용여부 필드 추가
	if(!sql_query(" select `bo_use_exif` from {$g5['eyoom_board']} limit 1 ", false)) {
		$sql = "alter table `{$g5['eyoom_board']}` add `bo_use_exif` tinyint(2) NOT NULL default '0' after `bo_use_yellow_card` ";
		sql_query($sql, true);
	}
}

/**
 * EyoomBuilder_1.1.12
 */
{
	// 이윰보드에 EXIF 상세설정 필드 추가
	if(!sql_query(" select `bo_exif_detail` from {$g5['eyoom_board']} limit 1 ", false)) {
		$sql = "alter table `{$g5['eyoom_board']}` add `bo_exif_detail` text NOT NULL after `bo_use_exif` ";
		sql_query($sql, true);
	}
}

/**
 * EyoomBuilder_1.1.13
 */
if ($eb_version >= $eb->version_score('1.1.13')) {
	// 이윰 기본설정 파일에 필드추가
	$config_basic = G5_DATA_PATH.'/eyoom.config.php';
	unset($eyoom, $_eyoom);
	include($config_basic);
	if(is_array($eyoom)) {
		foreach($eyoom as $key => $val) {
			$_eyoom[$key] = $val;
			if($key == 'cover_height' && !isset($eyoom['countdown'])) {
				$_eyoom['countdown'] 		= 'n';
				$_eyoom['countdown_skin'] 	= '';
				$_eyoom['countdown_date'] 	= '';
			}
		}
		$qfile->save_file('eyoom',$config_basic,$_eyoom);
	}
}

/**
 * EyoomBuilder_1.1.14
 */
if ($eb_version >= $eb->version_score('1.1.14')) {
	// 이윰 기본설정 파일에 필드추가
	$config_basic = G5_DATA_PATH.'/eyoom.config.php';
	unset($eyoom, $_eyoom);
	include($config_basic);
	if(is_array($eyoom)) {
		foreach($eyoom as $key => $val) {
			$_eyoom[$key] = $val;
			if($key == 'use_sub_side_layout' && !isset($eyoom['use_shop_mobile'])) {
				$_eyoom['use_shop_mobile'] = 'n';
			}
		}
		$qfile->save_file('eyoom',$config_basic,$_eyoom);
	}
}

/**
 * EyoomBuilder_1.1.15
 */
if ($eb_version >= $eb->version_score('1.1.15')) {
	// 이윰보드에 게시판 별점기능 사용여부 필드 추가
	if(!sql_query(" select `bo_use_rating` from {$g5['eyoom_board']} limit 1 ", false)) {
		$sql = "
			alter table `{$g5['eyoom_board']}` 
				add `bo_use_rating` char(1) not null default '2' after `bo_use_exif`,
				add `bo_use_rating_list` char(1) not null default '1' after `bo_use_rating`,
				add `bo_use_summernote_mo` char(1) not null default '1' after `bo_use_rating_list`,
				add `bo_use_addon_emoticon` char(1) not null default '1' after `bo_use_summernote_mo`,
				add `bo_use_addon_video` char(1) not null default '1' after `bo_use_addon_emoticon`,
				add `bo_use_addon_coding` char(1) not null default '0' after `bo_use_addon_video`,
				add `bo_use_addon_soundcloud` char(1) not null default '0' after `bo_use_addon_coding`,
				add `bo_use_addon_map` char(1) not null default '0' after `bo_use_addon_soundcloud`,
				add `bo_use_addon_cmtimg` char(1) not null default '1' after `bo_use_addon_map`
		";
		sql_query($sql, true);
		
		// 필드 속성 변경
		$sql = "alter table `{$g5['eyoom_board']}` change `bo_use_yellow_card`  `bo_use_yellow_card` char( 1 ) not null default '0' ";
		sql_query($sql, true);
		$sql = "alter table `{$g5['eyoom_board']}` change `bo_use_exif`  `bo_use_exif` char( 1 ) not null default '0' ";
		sql_query($sql, true);
	}

	// 게시물 별점 테이블 생성
	$eyoom_rating_table = G5_TABLE_PREFIX . 'eyoom_rating';
	$rating_sql = "
		CREATE TABLE IF NOT EXISTS `" . $eyoom_rating_table . "` (
		  `rt_id` int(11) unsigned NOT NULL auto_increment,
		  `bo_table` varchar(20) NOT NULL default '',
		  `wr_id` int(11) NOT NULL default '0',
		  `mb_id` varchar(20) NOT NULL default '',
		  `rating` smallint(2) NOT NULL default '0',
		  `rt_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
		  PRIMARY KEY  (`rt_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	";
	sql_query($rating_sql, true);
}

/**
 * EyoomBuilder_1.2.0
 */
if ($eb_version >= $eb->version_score('1.2.0')) {
	// 댓글에 무한스크롤 기능 및 베스트댓글 기능 추가 
	if(!sql_query(" select `bo_use_cmt_infinite` from {$g5['eyoom_board']} limit 1 ", false)) {
		$sql = "
			alter table `{$g5['eyoom_board']}` 
				add `bo_use_cmt_infinite` char(1) not null default '0' after `bo_use_infinite_scroll`,
				add `bo_use_cmt_best` char(1) not null default '0' after `bo_use_cmt_infinite`,
				add `bo_cmt_best_min` tinyint(2) not null default '10' after `bo_use_addon_cmtimg`,
				add `bo_cmt_best_limit` tinyint(2) not null default '5' after `bo_cmt_best_min`
		";
		sql_query($sql, true);
	}
}

/**
 * EyoomBuilder_1.2.1
 */
if ($eb_version >= $eb->version_score('1.2.1')) {
	// 태그 기능을 위한 테이블 추가
	$eyoom_tag_table = G5_TABLE_PREFIX . 'eyoom_tag';
	$eyoom_tag_sql = "
		CREATE TABLE IF NOT EXISTS `" . $eyoom_tag_table . "` (
		  `tg_id` int(11) NOT NULL auto_increment,
		  `tg_theme` varchar(40) NOT null default 'basic',
		  `tg_word` varchar(20) NOT NULL,
		  `tg_regcnt` int(11) unsigned NOT NULL default '0',
		  `tg_dpmenu` enum('y','n') NOT NULL default 'n',
		  `tg_scnt` int(11) NOT NULL default '0',
		  `tg_score` int(11) NOT NULL default '0',
		  `tg_recommdt` datetime NOT NULL default '0000-00-00 00:00:00',
		  `tg_regdt` datetime NOT NULL default '0000-00-00 00:00:00',
		  PRIMARY KEY  (`tg_id`),
		  KEY `tg_word` (`tg_word`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	";
	sql_query($eyoom_tag_sql, true);
	
	$eyoom_tag_write_table = G5_TABLE_PREFIX . 'eyoom_tag_write';
	$eyoom_tag_write_sql = "
		CREATE TABLE IF NOT EXISTS `" . $eyoom_tag_write_table . "` (
		  `tw_id` int(11) NOT NULL auto_increment,
		  `tw_theme` varchar(40) NOT NULL,
		  `bo_table` varchar(20) NOT NULL default '',
		  `wr_id` int(11) NOT NULL default '0',
		  `wr_subject` varchar(255) NOT NULL,
		  `wr_option` set('html1','html2','secret','mail') NOT NULL,
		  `wr_content` text NOT NULL,
		  `wr_tag` text NOT NULL,
		  `wr_image` text NOT NULL,
		  `wr_hit` int(11) NOT NULL default '0',
		  `mb_id` varchar(20) NOT NULL default '',
		  `mb_name` varchar(50) NOT NULL,
		  `mb_nick` varchar(50) NOT NULL,
		  `mb_level` varchar(255) NOT NULL,
		  `tw_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
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
		  PRIMARY KEY  (`tw_id`),
		  KEY `mb_id` (`mb_id`),
		  KEY `wr_hit` (`wr_hit`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	";
	sql_query($eyoom_tag_write_sql, true);
	
	// 이윰 기본설정 파일에 태그 관련 필드 추가
	$config_basic = G5_DATA_PATH.'/eyoom.config.php';
	unset($eyoom, $_eyoom);
	include($config_basic);
	if(is_array($eyoom)) {
		foreach($eyoom as $key => $val) {
			$_eyoom[$key] = $val;
			if ($key == 'emoticon_skin' && !isset($eyoom['tag_skin'])) {
				$_eyoom['tag_skin'] = 'basic';
			} elseif ($key == 'use_shop_mobile' && !isset($eyoom['use_tag'])) {
				$_eyoom['use_tag'] = 'n';
				$_eyoom['tag_dpmenu_count'] = '20';
				$_eyoom['tag_dpmenu_sort'] = 'regdt';
				$_eyoom['tag_recommend_count'] = '5';
			}
		}
		$qfile->save_file('eyoom',$config_basic,$_eyoom);
	}
	
	// 이윰 게시판에 태그 관련 필드 추가
	if(!sql_query(" select bo_use_tag from {$g5['eyoom_board']} limit 1 ", false)) {
		$sql = "
			alter table `{$g5['eyoom_board']}` 
				add `bo_use_tag` char(1) not null default '0' after `bo_use_rating_list`,
				add `bo_tag_level` tinyint(4) not null default '2' after `bo_cmt_best_limit`,
				add `bo_tag_limit` tinyint(4) not null default '10' after `bo_tag_level`
		";
		sql_query($sql, true);
	}
}

/**
 * EyoomBuilder_1.2.2
 */
if ($eb_version >= $eb->version_score('1.2.2')) {
	// 이윰 배너 테이블에 회원레벨 필드 추가
	if(!sql_query(" select bn_view_level from {$g5['eyoom_banner']} limit 1 ", false)) {
		$sql = "
			alter table `{$g5['eyoom_banner']}` add `bn_view_level` tinyint(4) not null default '1' after `bn_clicked`
		";
		sql_query($sql, true);
	}
}

/**
 * EyoomBuilder_1.2.3
 */
if ($eb_version >= $eb->version_score('1.2.3')) {
	// 이윰 메뉴 테이블에 분류명칭 필드 추가
	if(!sql_query(" select me_sca from {$g5['eyoom_menu']} limit 1 ", false)) {
		$sql = "
			alter table `{$g5['eyoom_menu']}` add `me_sca` varchar(50) not null default '' after `me_pid`
		";
		sql_query($sql, true);
	}
}

/**
 * EyoomBuilder_1.2.4
 */
if ($eb_version >= $eb->version_score('1.2.4')) {
	// 게시물 자동 이동/복사 기능을 위한 필드 추가
	if(!sql_query(" select bo_automove from {$g5['eyoom_board']} limit 1 ", false)) {
		$sql = "
			alter table `{$g5['eyoom_board']}` 
				add `bo_use_automove` char(1) not null default '0' after `bo_use_tag`,
				add `bo_automove` varchar(255) not null after `bo_tag_limit`
		";
		sql_query($sql, true);
	}
}

/**
 * EyoomBuilder_1.2.5
 */
if ($eb_version >= $eb->version_score('1.2.5')) {
	// 외부이미지 자동으로 가져오기 기능을 위한 필드 추가
	if(!sql_query(" select bo_use_extimg from {$g5['eyoom_board']} limit 1 ", false)) {
		$sql = "
			alter table `{$g5['eyoom_board']}` add `bo_use_extimg` char(1) not null default '0' after `bo_use_addon_cmtimg`
		";
		sql_query($sql, true);
	}
	
	// 이윰 기본설정 파일에 게시판 컨트롤 판넬 및 테마정보 판넬 관련 변수 추가
	$config_basic = G5_DATA_PATH.'/eyoom.config.php';
	unset($eyoom, $_eyoom);
	include($config_basic);
	if(is_array($eyoom)) {
		foreach($eyoom as $key => $val) {
			$_eyoom[$key] = $val;
			if ($key == 'use_tag' && !isset($eyoom['use_board_control'])) {
				$_eyoom['use_board_control'] = 'n';
				$_eyoom['use_theme_info'] = 'n';
				$_eyoom['board_control_position'] = 'left';
				$_eyoom['theme_info_position'] = 'bottom';
			}
		}
		$qfile->save_file('eyoom',$config_basic,$_eyoom);
	}
}

?>