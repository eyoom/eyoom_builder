<?php
	if (!defined('_EYOOM_')) exit;

	// Eyoom Builder
	define('_EYOOM_COMMON_',true);

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

		// 오늘 처음 로그인 이라면 로그인 레벨포인트 적용
		if (substr($member['mb_today_login'], 0, 10) != G5_TIME_YMD) {
			// 첫 로그인 레벨포인트 지급
			$eb->level_point($levelset['login']);
		}
	}

	// Eyoom Board 설정
	if($board['bo_table']) {
		$eyoom_board = $eb->eyoom_board_info($board['bo_table'], $theme);
		if(!$eyoom_board) {
			$eyoom_board['bo_table']				= $board['bo_table'];
			$eyoom_board['bo_theme']				= $theme;
			$eyoom_board['bo_skin']					= 'basic';
			$eyoom_board['use_gnu_skin']			= 'n';
			$eyoom_board['bo_use_profile_photo']	= 1;
			$eyoom_board['bo_sel_date_type']		= 1;
			$eyoom_board['bo_use_hotgul']			= 1;
		}
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

?>