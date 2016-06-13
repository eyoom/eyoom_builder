<?php
	if (!defined('_EYOOM_')) exit;

	// Eyoom Builder
	define('_EYOOM_COMMON_',true);

	// Version
	define('_EYOOM_VESION_','EyoomBuilder_1.2.5');

	// GNUBOARD5 Library
	include_once(G5_LIB_PATH.'/common.lib.php');
	include_once(G5_LIB_PATH.'/latest.lib.php');
	include_once(G5_LIB_PATH.'/outlogin.lib.php');
	include_once(G5_LIB_PATH.'/poll.lib.php');
	include_once(G5_LIB_PATH.'/visit.lib.php');
	include_once(G5_LIB_PATH.'/connect.lib.php');
	include_once(G5_LIB_PATH.'/popular.lib.php');
	include_once(G5_LIB_PATH.'/thumbnail.lib.php');

	// Eyoom Member
	$eyoomer = array();
	if($member['mb_id']) {
		$eyoomer = $eb->get_user_info($member['mb_id']);
		if(!$eyoomer['following']) $eyoomer['following'] = array();
		if(!$eyoomer['follower']) $eyoomer['follower'] = array();
		
		// 관심게시판
		$favorite = unserialize($eyoomer['favorite']);
		if($favorite) $my_favorite = implode(',', $favorite);

		// 그누레벨 자동조정
		if(!$is_admin && $member['mb_level'] <= $levelset['max_use_gnu_level']) $eb->set_gnu_level($eyoomer['level']);

		// 오늘 처음 로그인 이라면 로그인 레벨포인트 적용
		if (substr($member['mb_today_login'], 0, 10) != G5_TIME_YMD) {
			// 첫 로그인 레벨포인트 지급
			$eb->level_point($levelset['login']);
		}
	}

	// Eyoom Board 설정
	if($bo_table) {

		// $eyoom_board 설정값 가져오기
		$eyoom_board = $eb->eyoom_board_info($bo_table, $theme);
		if(!$eyoom_board) {
			// DB에 입력된 정보가 없을 때, 기본값 가져오기
			$eyoom_board = $eb->eyoom_board_default($bo_table);
		}
		
		// 게시물 자동 이동/복사를 위한 변수
		(array)$bo_automove = unserialize($eyoom_board['bo_automove']);
		
		// 사이트 내 게시판 정보 일괄 가져오기
		$sql = "select bo_table, bo_subject from {$g5['board_table']} where 1 order by bo_subject asc";
		$result = sql_query($sql);
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$binfo[$i] = $row;
		}

		// EXIF정보보기 사용시
		if($eyoom_board['bo_use_exif'] || $is_admin == 'super') {
			$exif_item = array(
				'maker'		=> '제조사',
				'model'		=> '모델',
				'datetime'	=> '촬영일',
				'size'		=> '사이즈',
				'exprogram'	=> '촬영모드',
				'metermode'	=> '측광모드',
				'focallen'	=> '초점거리',
				'focal35mm'	=> '35mm환산거리',
				'fnumber'	=> '조리개',
				'iso'		=> 'ISO',
				'whitebal'	=> '화이트밸러스',
				'exptime'	=> '노출시간',
				'expbias'	=> '노출보정(EV)',
				'ccd'		=> 'CCD',
				'flash'		=> 'Flash'
			);

			// EXIF Class Object
			include_once(EYOOM_CLASS_PATH . '/exif.class.php');
			$exif = new exif;
		}

		// 익명글쓰기 체크
		$is_anonymous = $eyoom_board['bo_use_anonymous'] == 1 ? true:false;

		// 무한스크롤 기능을 사용하면 wmode를 활성화
		if($eyoom_board['bo_use_infinite_scroll'] == 1) {
			$user_agent = $eb->user_agent();
			if($user_agent != 'ios') {
				$_wmode = true;
				if($wmode) define('_WMODE_',true);
			} else {
				$eyoom_board['bo_use_infinite_scroll'] = 2;
			}
		}
	}

	// switcher 설정
	if($theme!='basic') {
		$switcher_path = G5_DATA_PATH.'/member/switcher';
		$switcher_admin = G5_DATA_PATH.'/member/switcher/'.$config['cf_admin'].'.config.php';
		if(file_exists($switcher_admin)) {
			if($eyoom['use_switcher'] == 'on') {
				if($is_member) {
					$switcher_member = $switcher_path.'/'.$member['mb_id'].'.config.php';
					if(file_exists($switcher_member)) @include $switcher_member;
					else @include $switcher_admin;
				} else {
					@include $switcher_admin;
				}
			} else @include $switcher_admin;
		}
		if(!$_switcher[$theme]) {
			if(preg_match('/community/',$theme)) {
				$_switcher[$theme] = array(
					"sw_color"	=> "default",
					"sw_fixed"	=> "",
					"sw_boxed"	=> "",
					"usemember" => "",
					"sw_use"	=> ""
				);
			}
			if(preg_match('/dynamic/',$theme)) {
				$_switcher[$theme] = array(
					"sw_color"		=> "default",
					"sw_fixed"		=> "fixed",
					"sw_boxed"		=> "boxed",
					"sw_sideopen"	=> "closed",
					"sw_sidebar"	=> "default",
					"sw_sidemenu"	=> "accordion",
					"sw_sidepos"	=> "left",
					"sw_footer"		=> "default",
					"usemember"		=> "on",
					"sw_use"		=> "off"
				);
			}
		}
		$switcher = $_switcher[$theme];
	}

	// SNS용 이미지/제목/내용 추가 메타태그
	if(($bo_table && $wr_id) || $it_id) {
		if($bo_table && $wr_id) {
			$head_title = strip_tags(conv_subject($write['wr_subject'], 255)) . ' > ' . $board['bo_subject'] . ' | ' . $config['cf_title'];
			$first_image = get_list_thumbnail($bo_table, $wr_id, 600, 0);
			$sns_image = $first_image['src'];
			$target_url = G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;wr_id='.$wr_id;
			$contents = cut_str(trim(str_replace(array("\r\n","\r","\n"),'',strip_tags(preg_replace("/\?/","",$write['wr_content'])))),200, '…');
		}
		if($it_id) {
			$sitem = sql_fetch("select * from {$g5['g5_shop_item_table']} where it_id = '".$it_id."'");
			$head_title = strip_tags(conv_subject($sitem['it_name'], 255)) . ' | ' . $config['cf_title'];
			$sns_image = G5_DATA_URL . '/item/'.$sitem['it_img1'];
			$target_url = G5_SHOP_URL.'/item.php?it_id='.$it_id;
			$contents = cut_str(trim(str_replace(array("\r\n","\r","\n"),'',strip_tags(preg_replace("/\?/","",$sitem['it_explan'])))),200, '…');
		}
		$config['cf_add_meta'] .= '
			<meta property="og:id" content="'.G5_URL.'" />
			<meta property="og:url" content="'.$target_url.'" />
			<meta property="og:type" content="article" />
			<meta property="og:title" content="'.preg_replace('/"/','',$head_title).'" />
			<meta property="og:site_name" content="'.$config['cf_title'].'" />
			<meta property="og:description" content="'.$contents.'"/>
			<meta property="og:image" content="'.$sns_image.'" />
		';
	}

	// Eyoom Core Path
	$board_skin_path    = EYOOM_CORE_PATH.'/board';
	$member_skin_path   = EYOOM_CORE_PATH.'/member';
	$new_skin_path		= EYOOM_CORE_PATH.'/new';
	$search_skin_path	= EYOOM_CORE_PATH.'/search';
	$connect_skin_path	= EYOOM_CORE_PATH.'/connect';
	$faq_skin_path		= EYOOM_CORE_PATH.'/faq';
	$qa_skin_path		= EYOOM_CORE_PATH.'/qa';
	$poll_skin_path		= EYOOM_CORE_PATH.'/poll';
	$respond_skin_path	= EYOOM_CORE_PATH.'/respond';
	$mypage_skin_path	= EYOOM_CORE_PATH.'/mypage';
	$page_skin_path		= EYOOM_CORE_PATH.'/page';
	$tag_skin_path		= EYOOM_CORE_PATH.'/tag';

	// GNUBOARD Skin 사용여부 체크
	if($eyoom_board['use_gnu_skin'] == 'y') { // 이윰보드 설정에서 그누보드 사용여부 체크
		if(G5_IS_MOBILE) {
			$board_skin_path    = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/board/'.$board['bo_mobile_skin'];
			$board_skin_url     = G5_MOBILE_URL .'/'.G5_SKIN_DIR.'/board/'.$board['bo_mobile_skin'];
		} else {
			$board_skin_path    = G5_SKIN_PATH.'/board/'.$board['bo_skin'];
			$board_skin_url     = G5_SKIN_URL .'/board/'.$board['bo_skin'];
		}
	}
	if($eyoom['use_gnu_member'] == 'y') {
		if(G5_IS_MOBILE) {
			$member_skin_path   = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/member/'.$config['cf_mobile_member_skin'];
			$member_skin_url    = G5_MOBILE_URL .'/'.G5_SKIN_DIR.'/member/'.$config['cf_mobile_member_skin'];
		} else {
			$member_skin_path   = G5_SKIN_PATH.'/member/'.$config['cf_member_skin'];
			$member_skin_url    = G5_SKIN_URL .'/member/'.$config['cf_member_skin'];
		}
	}
	if($eyoom['use_gnu_new'] == 'y') {
		if(G5_IS_MOBILE) {
			$new_skin_path      = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/new/'.$config['cf_mobile_new_skin'];
			$new_skin_url       = G5_MOBILE_URL .'/'.G5_SKIN_DIR.'/new/'.$config['cf_mobile_new_skin'];
		} else {
			$new_skin_path      = G5_SKIN_PATH.'/new/'.$config['cf_new_skin'];
			$new_skin_url       = G5_SKIN_URL .'/new/'.$config['cf_new_skin'];
		}
	}
	if($eyoom['use_gnu_search'] == 'y') {
		if(G5_IS_MOBILE) {
			$search_skin_path   = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/search/'.$config['cf_mobile_search_skin'];
			$search_skin_url    = G5_MOBILE_URL .'/'.G5_SKIN_DIR.'/search/'.$config['cf_mobile_search_skin'];
		} else {
			$search_skin_path   = G5_SKIN_PATH.'/search/'.$config['cf_search_skin'];
			$search_skin_url    = G5_SKIN_URL .'/search/'.$config['cf_search_skin'];
		}
	}
	if($eyoom['use_gnu_connect'] == 'y') {
		if(G5_IS_MOBILE) {
			$connect_skin_path  = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/connect/'.$config['cf_mobile_connect_skin'];
			$connect_skin_url   = G5_MOBILE_URL .'/'.G5_SKIN_DIR.'/connect/'.$config['cf_mobile_connect_skin'];
		} else {
			$connect_skin_path  = G5_SKIN_PATH.'/connect/'.$config['cf_connect_skin'];
			$connect_skin_url   = G5_SKIN_URL .'/connect/'.$config['cf_connect_skin'];
		}
	}
	if($eyoom['use_gnu_faq'] == 'y') {
		if(G5_IS_MOBILE) {
			$faq_skin_path      = G5_MOBILE_PATH .'/'.G5_SKIN_DIR.'/faq/'.$config['cf_mobile_faq_skin'];
			$faq_skin_url       = G5_MOBILE_URL .'/'.G5_SKIN_DIR.'/faq/'.$config['cf_mobile_faq_skin'];
		} else {
			$faq_skin_path      = G5_SKIN_PATH.'/faq/'.$config['cf_faq_skin'];
			$faq_skin_url       = G5_SKIN_URL.'/faq/'.$config['cf_faq_skin'];
		}
	}
	if($eyoom['use_gnu_qa'] == 'y') {
		if(G5_IS_MOBILE) {
			$qa_skin_path      = G5_MOBILE_PATH .'/'.G5_SKIN_DIR.'/qa/'.$qaconfig['qa_skin'];
			$qa_skin_url       = G5_MOBILE_URL .'/'.G5_SKIN_DIR.'/qa/'.$qaconfig['qa_skin'];
		} else {
			$qa_skin_path      = G5_SKIN_PATH.'/qa/'.$qaconfig['qa_skin'];
			$qa_skin_url       = G5_SKIN_URL.'/qa/'.$qaconfig['qa_skin'];
		}
	}

	// 일정 기간이 지난 DB 데이터 삭제 및 최적화
	include_once(EYOOM_INC_PATH.'/db_table.optimize.php');
		
	// common.php 파일을 수정할 필요가 없도록 확장
	$extend_file = array();
	$tmp = dir(EYOOM_EXTEND_PATH);
	while ($entry = $tmp->read()) {
	    // php 파일만 include 함
	    if (preg_match("/(\.php)$/i", $entry))
	        $extend_file[] = $entry;
	}
	
	if(!empty($extend_file) && is_array($extend_file)) {
	    natsort($extend_file);
	
	    foreach($extend_file as $exfile) {
	        include_once(EYOOM_EXTEND_PATH.'/'.$exfile);
	    }
	}
	unset($extend_file);

?>
