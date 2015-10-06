<?php 
	if (!defined('_GNUBOARD_')) exit;

	if(isset($member['mb_open_date'])) {
		$open_day = date("Y년 m월 j일", strtotime("{$member['mb_open_date']} 00:00:00")+$config['cf_open_modify']*86400);
	} else {
		$open_day = date("Y년 m월 j일", G5_SERVER_TIME+$config['cf_open_modify']*86400);
	}

	// 사용자 프로그램
	@include_once(EYOOM_USER_PATH.'/member/register_form.skin.php');

	// Template define
	$tpl->define_template('member',$eyoom['member_skin'],'register_form.skin.html');

	@include EYOOM_INC_PATH.'/tpl.assign.php';

	$tpl->print_($tpl_name);

?>