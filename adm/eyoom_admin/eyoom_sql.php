<?php
if (!defined('_GNUBOARD_')) exit;

if($is_admin != 'super') alert('최고관리자로 로그인 후 실행해 주십시오.', G5_URL);

/** ############# EyoomBuilder_1.0.3 ############# */
{ 
	// 장바구니 상품 주문폼 등록시간 기록 필드 추가
	if(!sql_query(" select bo_use_anonymous from {$g5['eyoom_board']} limit 1 ", false)) {
		sql_query(" alter table `{$g5['eyoom_board']}` add `bo_use_anonymous` char(1) not null default '2' after `bo_use_hotgul` ", true);
	}
}
/** ############# EyoomBuilder_1.0.3 ############# */

/** ############# EyoomBuilder_1.0.5 ############# */
{ 
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
/** ############# EyoomBuilder_1.0.5 ############# */

/** ############# EyoomBuilder_1.0.6 ############# */
{ 
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
/** ############# EyoomBuilder_1.0.6 ############# */

/** ############# EyoomBuilder_1.1.0 ############# */
{ 
	// 이윰메뉴의 me_type 속성 변경
	$sql = "ALTER TABLE `g5_eyoom_menu` CHANGE `me_type` `me_type` VARCHAR(30) NOT NULL;";
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
			alter table  `g5_eyoom_new` 
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
/** ############# EyoomBuilder_1.1.0 ############# */

/** ############# EyoomBuilder_1.1.1 ############# */
{ 
	// 이윰 짧은주소 테이블 생성
	$sql = "
		create table if not exists `g5_eyoom_link` (
		  `s_no` int(11) unsigned not null auto_increment,
		  `bo_table` varchar(20) collate utf8_unicode_ci not null,
		  `wr_id` int(11) unsigned not null default '0',
		  `theme` varchar(40) collate utf8_unicode_ci not null,
		  primary key  (`s_no`)
		) engine=myisam default charset=utf8
	";
	sql_query($sql, false);
}
/** ############# EyoomBuilder_1.1.1 ############# */

/** ############# EyoomBuilder_1.1.2 ############# */
{
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
/** ############# EyoomBuilder_1.1.2 ############# */

/** ############# EyoomBuilder_1.1.3 ############# */
{
	// 이윰보드에 무한스크롤 사용여부 필드 추가
	if(!sql_query(" select bo_use_infinite_scroll from {$g5['eyoom_board']} limit 1 ", false)) {
		$sql = "alter table `{$g5['eyoom_board']}` add `bo_use_infinite_scroll` char(1) not null default '2' after `bo_use_anonymous`";
		sql_query($sql, true);
	}
}
/** ############# EyoomBuilder_1.1.3 ############# */

/** ############# EyoomBuilder_1.1.7 ############# */
{ 
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
/** ############# EyoomBuilder_1.1.7 ############# */

?>