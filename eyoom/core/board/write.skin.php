<?php
 	if (!defined('_GNUBOARD_')) exit;

	$uid = get_uniqid();

	// wr_1에 작성자의 레벨정보 입력
	if($is_member) {
		if($w==''||$w=='r') {
			$wr_1 = $member['mb_level']."|".$eyoomer['level'];
		} else if($w=='u' && $wr_1 && $is_anonymous) {
			list($gnu_level,$eyoom_level,$anonymous) = explode('|',$wr_1);
			$wr_1 = $gnu_level."|".$eyoom_level;
			if($anonymous == 'y') {
				$anonymous_checked = 'checked="checked"';
			}
		}
	}

	for ($i=1; $is_link && $i<=G5_LINK_COUNT; $i++) {
		$wr_link[$i]['link_val'] = $write['wr_link'.$i]; 
	}

	$wr_file = array();
	if($file == "sms5.extend.php" || $file == "xeyoom.extend.php") unset($file);
	for ($i=0; $is_file && $i<$file_count; $i++) {
		$wr_file[$i]['file'] = $file[$i]['file'];
		$wr_file[$i]['size'] = $file[$i]['size'];
		$wr_file[$i]['source'] = $file[$i]['source'];
		$wr_file[$i]['bf_content'] = $file[$i]['bf_content'];
	}

	// 사용자 프로그램
	@include_once(EYOOM_USER_PATH.'/board/write.skin.php');

	// Template define
	$tpl->define_template('board',$eyoom_board['bo_skin'],'write.skin.html');

	// Template assign
	@include EYOOM_INC_PATH.'/tpl.assign.php';
	$tpl->print_($tpl_name);

?>