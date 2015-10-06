<?php
	if (!defined('_GNUBOARD_')) exit;

	if(!$pid) alert('잘못된 접근입니다.');
	else {
		$pid = str_replace("|","/",$pid);
		$page_file = $pid.'.html';
		$file_path = EYOOM_THEME_PATH.'/'.$theme.'/page/'.$page_file;
		if(!file_exists($file_path)) {
			alert('해당 스킨파일이 존재하지 않습니다.');
			exit;
		} else {
			// 사용자 프로그램
			@include_once(EYOOM_USER_PATH.'/page/'.$pid.'.php');

			$tpl->define(array(
				'pc' => 'page/' . $page_file,
				'mo' => 'page/' . $page_file,
				'bs' => 'page/' . $page_file,
			));
			$tpl->assign(array(
				//'page' => $page,
			));
			@include EYOOM_INC_PATH.'/tpl.assign.php';
			$tpl->print_($tpl_name);
		}
	}

?>