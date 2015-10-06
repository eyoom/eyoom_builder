<?php
	if (!defined('_GNUBOARD_')) exit;

	// 그누 헤더정보 출력
	@include_once(G5_PATH.'/head.sub.php');

	// 쇼핑몰 레이아웃을 커뮤니티에 적용하기
	if((isset($shop_layout_use) && $shop_layout_use)) {
		@include_once(EYOOM_CORE_PATH.'/shop/shop.head.php');
		return;
	}

	if(!defined('_EYOOM_COMMON_') || $qaconfig) @include EYOOM_PATH.'/common.php';

	// 메인인지 마이홈인지 GET변수의 초기key 값으로 구분하기
	if(isset($_GET) && is_array($_GET)) {
		foreach($_GET as $k => $v) { $dummy = $k; break; }
		if($member['mb_id'] == $dummy) $is_myhome = true;
	}

	if($is_member) {
		// 읽지 않은 쪽지
		$memo_not_read = $eb->get_memo($member['mb_id']);

		// 내글 반응
		$respond = $eyoomer['respond'];
		if($respond < 0) {
			$respond = 0;
			sql_query("update {$g5['eyoom_member']} set respond = 0 where mb_id='{$member['mb_id']}'");
		}
	}

	// 메뉴설정
	if($eyoom['use_eyoom_menu'] == 'y') $menu_flag = 'eyoom';
	$menu = $thema->menu_create($menu_flag);

	// 서브페이지 메뉴정보, 타이틀 및 Path 정보
	if(!defined('_INDEX_'))	{
		$subinfo = $thema->subpage_info($menu);
		if($subinfo['registed'] == 'y') $sidemenu = $thema->submenu_create($menu_flag);
	} else {
		// 팝업창  
		if($eyoom['use_gnu_newwin'] == 'n') {
			@include_once(EYOOM_CORE_PATH.'/newwin/newwin.inc.php');
		} else {
			@include_once(G5_BBS_PATH.'/newwin.inc.php');
		}
	}

	// 접속자 정보
	$connect = $eb->get_connect();

	// 사용자 프로그램
	@include_once(EYOOM_USER_PATH.'/head.php');

	// 템플릿에 변수 할당
	@include EYOOM_INC_PATH.'/tpl.assign.php';

	// 템플릿 출력
	$tpl_head = 'head_' . $tpl_name;
	$tpl->print_($tpl_head);

?>