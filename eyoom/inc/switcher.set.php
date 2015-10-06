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

	$sw_file = $switcher_path.'/'.$member['mb_id'].'.config.php';
	
	$switcher = set_switcher();
	$switcher_config = is_array($_switcher) ? array_merge($_switcher,$switcher) : $switcher;

	if($is_admin) {
		$eyoom_config = config_file;
		$eyoom['use_switcher'] = $_switcher[$theme]['sw_use'];
		$qfile->save_file('eyoom', $eyoom_config, $eyoom);
	}
	unset($_switcher[$theme]['sw_use']);

	function set_switcher() {
		global $theme;
		foreach($_POST as $key => $val) {
			$switcher[$theme][$key] = $val;
		}
		return $switcher;
	}

	$qfile->save_file('_switcher', $sw_file, $switcher_config);
	echo "
		<script>parent.switcher_hide();</script>
	";
?>