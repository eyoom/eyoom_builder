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
		unset($eyoom);
		include($eyoom_config_file);

		foreach($eyoom_basic as $key => $val) {
			if($key == 'bootstrap') {
				$val = !isset($_POST[$key]) ? '1':$_POST[$key];
			} else if(preg_match('/_skin/i',$key) || $key == 'theme') {
				$val = !$_POST[$key] ? 'basic' : $_POST[$key];
			} else if(preg_match('/use_gnu_/i',$key)) {
				$val = !$_POST[$key] ? 'n' : $_POST[$key];
			} else if($key == 'level_icon_gnu') {
				$val = !$_POST[$key] ? '':$_POST[$key];
			} else if($key == 'use_level_icon_gnu') {
				$val = !$_POST[$key] ? 'n':$_POST[$key];
			} else if($key == 'level_icon_eyoom') {
				$val = !$_POST[$key] ? '':$_POST[$key];
			} else if($key == 'use_level_icon_eyoom') {
				$val = !$_POST[$key] ? 'n':$_POST[$key];
			} else if($key == 'use_eyoom_menu') {
				$val = !$_POST[$key] ? 'y':$_POST[$key];
			} else if($key == 'use_eyoom_shopmenu') {
				$val = !$_POST[$key] ? 'n':$_POST[$key];
			} else if($key == 'use_sideview') {
				$val = !$_POST[$key] ? 'y':$_POST[$key];
			} else if($key == 'use_main_side_layout') {
				$val = !$_POST[$key] ? 'y':$_POST[$key];
			} else if($key == 'use_sub_side_layout') {
				$val = !$_POST[$key] ? 'y':$_POST[$key];
			} else if($key == 'use_shop_mobile') {
				$val = !$_POST[$key] ? 'n':$_POST[$key];
			} else if($key == 'use_tag') {
				$val = !$_POST[$key] ? 'n':$_POST[$key];
			} else if($key == 'use_board_control') {
				$val = !$_POST[$key] ? 'n':$_POST[$key];
			} else if($key == 'use_theme_info') {
				$val = !$_POST[$key] ? 'n':$_POST[$key];
			} else if($key == 'board_control_position') {
				$val = !$_POST[$key] ? 'left':$_POST[$key];
			} else if($key == 'theme_info_position') {
				$val = !$_POST[$key] ? 'bottom':$_POST[$key];
			} else if($key == 'tag_dpmenu_count') {
				$val = !$_POST[$key] ? '20':$_POST[$key];
			} else if($key == 'tag_dpmenu_sort') {
				$val = !$_POST[$key] ? 'regdt':$_POST[$key];
			} else if($key == 'tag_recommend_count') {
				$val = !$_POST[$key] ? '5':$_POST[$key];
			} else if($key == 'pos_side_layout') {
				$val = !$_POST[$key] ? 'right':$_POST[$key];
			} else if($key == 'push_reaction') {
				$val = !$_POST[$key] ? 'y':$_POST[$key];
			} else if($key == 'push_time') {
				$val = !$_POST[$key] ? '1000':$_POST[$key];
			} else if($key == 'photo_width') {
				$val = !$_POST[$key] ? '150':$_POST[$key];
			} else if($key == 'photo_height') {
				$val = !$_POST[$key] ? '150':$_POST[$key];
			} else if($key == 'cover_width') {
				$val = !$_POST[$key] ? '845':$_POST[$key];
			} else if($key == 'language') {
				$val = !$_POST[$key] ? 'kr':$_POST[$key];
			} else if($key == 'theme') {
				$val = $eyoom[$key];
			}
			$eyoom_config[$key] = isset($val) ? $val : $eyoom[$key];
		}
		
		if(isset($_POST['language'])) {
			$eyoom_config['language'] = $_POST['language'];
		} else {
			$eyoom_config['language'] = 'kr';
		}
		if($eyoom['theme'] != 'basic') {
			$eyoom_config['theme_key'] = $eyoom['theme_key'];
			if(!isset($eyoom_config['bootstrap'])) {
				$eyoom_config['bootstrap'] = $eyoom['bootstrap'];
			}
		}
		if(preg_match('/mlang/',$eyoom['theme'])) {
			$eyoom_config['theme_lang_type'] = 'm';
		} else {
			unset($eyoom_config['theme_lang_type']);
		}

		$qfile->save_file('eyoom',$eyoom_config_file,$eyoom_config);
		$msg = "설정을 사용테마에 적용하였습니다.";
		alert($msg,'./config_form.php?thema='.$_POST['theme']);
		break;
}

?>