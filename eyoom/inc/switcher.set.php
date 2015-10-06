<?php
	$g5_path = '../..';
	include_once ($g5_path.'/common.php');
	include_once(EYOOM_PATH.'/common.php');

	if(!$is_member) exit;

	// switcher 설정정보 정장 경로
	$switcher_path = G5_DATA_PATH.'/member/switcher';
	if(!@is_dir($switcher_path)) {
		@mkdir($switcher_path, G5_DIR_PERMISSION);
		@chmod($switcher_path, G5_DIR_PERMISSION);
	}
	
	foreach($_POST as $key => $val) {
		$switcher[$key] = $val;
	}
	if($is_admin) {
		$eyoom_config = config_file;
		$eyoom['use_switcher'] = $switcher['sw_use'];
		$qfile->save_file('eyoom', $eyoom_config, $eyoom);
	}
	unset($switcher['sw_use']);

	$sw_file = $switcher_path.'/'.$member['mb_id'].'.config.php';

	$qfile->save_file('switcher', $sw_file, $switcher);
	echo "
		<script>parent.switcher_hide();</script>
	";
?>