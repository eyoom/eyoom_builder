<?php 
	if (!defined('_GNUBOARD_')) exit;

	$nick = get_sideview($mb['mb_id'], $mb['mb_nick'], $mb['mb_email'], $mb['mb_homepage']);
	if($kind == "recv") {
		$kind_str = "보낸";
		$kind_date = "받은";
	}
	else {
		$kind_str = "받는";
		$kind_date = "보낸";
	}

	// 사용자 프로그램
	@include_once(EYOOM_USER_PATH.'/member/memo_view.skin.php');

	// Template define
	$tpl->define_template('member',$eyoom['member_skin'],'memo_view.skin.html');

	$tpl->assign('mb',$mb);

	// Template assign
	@include EYOOM_INC_PATH.'/tpl.assign.php';
	$tpl->print_($tpl_name);

?>