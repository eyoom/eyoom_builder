<?php
$sub_menu = '800100';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], "r");

switch($mode) {
	case 'theme':
		include './theme_update.php';
		break;

	case 'skin':
		$eyoom_config_file = $_POST['theme'] == 'basic' || !$_POST['theme'] ? '../../data/eyoom.config.php':'../../data/eyoom.'.$_POST['theme'].'.config.php';
		include($eyoom_config_file);

		foreach($eyoom_basic as $key => $val) {
			if($key == 'bootstrap') {
				$val = !$_POST[$key] ? '1':$_POST[$key];
			}
			if(preg_match('/_skin/i',$key) || $key == 'theme') {
				$val = !$_POST[$key] ? 'basic' : $_POST[$key];
			}
			if(preg_match('/use_gnu_/i',$key)) {
				$val = !$_POST[$key] ? 'y' : $_POST[$key];
			}
			if($key == 'level_icon_gnu') {
				$val = !$_POST[$key] ? '':$_POST[$key];
			}
			if($key == 'use_level_icon_gnu') {
				$val = !$_POST[$key] ? 'n':$_POST[$key];
			}
			if($key == 'level_icon_eyoom') {
				$val = !$_POST[$key] ? '':$_POST[$key];
			}
			if($key == 'use_level_icon_eyoom') {
				$val = !$_POST[$key] ? 'n':$_POST[$key];
			}
			if($key == 'use_eyoom_menu') {
				$val = !$_POST[$key] ? 'y':$_POST[$key];
			}
			if($key == 'use_eyoom_shopmenu') {
				$val = !$_POST[$key] ? 'n':$_POST[$key];
			}
			if($key == 'use_sideview') {
				$val = !$_POST[$key] ? 'y':$_POST[$key];
			}
			if($key == 'use_main_side_layout') {
				$val = !$_POST[$key] ? 'y':$_POST[$key];
			}
			if($key == 'use_sub_side_layout') {
				$val = !$_POST[$key] ? 'y':$_POST[$key];
			}
			if($key == 'pos_side_layout') {
				$val = !$_POST[$key] ? 'right':$_POST[$key];
			}
			if($key == 'push_reaction') {
				$val = !$_POST[$key] ? 'y':$_POST[$key];
			}
			if($key == 'push_time') {
				$val = !$_POST[$key] ? '1000':$_POST[$key];
			}
			if($key == 'photo_width') {
				$val = !$_POST[$key] ? '150':$_POST[$key];
			}
			if($key == 'photo_height') {
				$val = !$_POST[$key] ? '150':$_POST[$key];
			}
			if($key == 'cover_width') {
				$val = !$_POST[$key] ? '845':$_POST[$key];
			}
			if($key == 'language') {
				$val = !$_POST[$key] ? 'kr':$_POST[$key];
			}
			if($key == 'theme') {
				$val = $eyoom[$key];
			}
			$eyoom_config[$key] = $val ? $val : $eyoom[$key];
		}
		if(!$eyoom_config['language']) $eyoom_config['language'] = $_POST['language'];
		if($eyoom['theme'] != 'basic') {
			$eyoom_config['theme_key'] = $eyoom['theme_key'];
			$eyoom_config['bootstrap'] = $eyoom['bootstrap'];
		}
		if(preg_match('/mlang/',$eyoom['theme'])) $eyoom_config['theme_lang_type'] = 'm';

		$qfile->save_file('eyoom',$eyoom_config_file,$eyoom_config);
		$msg = "설정을 사용테마에 적용하였습니다.";
		alert($msg,'./config_form.php?thema='.$_POST['theme']);
		break;
}

?>