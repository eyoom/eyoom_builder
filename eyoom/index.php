<?php
	if (!defined('_GNUBOARD_')) exit;

	// 그누 헤더정보 출력
	@include_once(G5_PATH.'/head.sub.php');

	// 팝업창  
	if($eyoom['use_gnu_newwin'] == 'n') {
		@include_once(EYOOM_CORE_PATH.'/newwin/newwin.inc.php');
	} else {
		@include_once(G5_BBS_PATH.'/newwin.inc.php');
	}

	// 이윰 헤더 디자인 출력
	@include_once(EYOOM_PATH.'/head.php');
	
	// 회원의 지정한 페이지 홈으로 보여주기
	$eb->print_page($eyoomer['main_index']);

	// 이윰 테일 디자인 출력
	@include_once(EYOOM_PATH.'/tail.php');
?>