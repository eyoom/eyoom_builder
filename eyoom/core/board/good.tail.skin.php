<?php 
	if (!defined('_GNUBOARD_')) exit;

	// 추천 비추천 내글반응 적용하기
	if ($count || $href) {
		$respond = array();
		$respond['type']		= $good;
		$respond['bo_table']	= $bo_table;
		$respond['wr_id']		= $wr_id;
		$respond['wr_subject']	= $write['wr_subject'];
		$respond['wr_mb_id']	= $write['mb_id'];

		$eb->respond($respond);
	}

	// 나의 활동
	$act_contents = array();
	$act_contents['bo_table'] = $bo_table;
	$act_contents['bo_name'] = $board['bo_subject'];
	$act_contents['wr_id'] = $wr_id;
	$eb->insert_activity($member['mb_id'],$good,$act_contents);
	switch($good) {
		case 'good' : $eb->level_point($levelset['good'],$write['mb_id'],$levelset['regood']); break;
		case 'nogood' : $eb->level_point($levelset['nogood'],$write['mb_id'],$levelset['renogood']); break;
	}
	
	// 사용자 프로그램
	@include_once(EYOOM_USER_PATH.'/board/good.tail.skin.php');

?>