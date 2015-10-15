<?php
	include(eyoom_config);
	$theme = $eyoom['theme'];
	$shop_theme = $eyoom['shop_theme'];
	$_theme = $_GET['thema'];
	if(!$_theme) $_theme = $theme;
	$config_file = G5_DATA_PATH.'/eyoom.'.$theme.'.config.php';
	if($theme != 'basic') {
		if(!file_exists($config_file)) {
			$theme = $eyoom['theme'] = 'basic';
			$qfile->save_file('eyoom',eyoom_config,$eyoom);
		} else {
			include($config_file);
		}
	}
	$_config_file = $_theme == 'basic' || !$_theme ? G5_DATA_PATH.'/eyoom.config.php':G5_DATA_PATH.'/eyoom.'.$_theme.'.config.php';
	@include($_config_file);
	foreach($eyoom_basic as $key => $val) {
		$_eyoom[$key] = $eyoom[$key] ? $eyoom[$key]: $val;
	}
	$_eyoom['theme'] = $eyoom['theme'];
	$_eyoom['bootstrap'] = $eyoom['bootstrap'];
	$_tpl_name = $eyoom['bootstrap'] ? 'bs':'pc';

	if(preg_match('/mlang/',$_theme)) {
		$_eyoom['theme_lang_type'] = 'm';
	} else {
		unset($_eyoom['theme_lang_type']);
	}
	unset($eyoom);

	// 게시판 설정 다시 가져오기
	if($bo_table) $eyoom_board = $eb->eyoom_board_info($bo_table, $_theme);
	if(!$eyoom_board) {
		$eyoom_board = $eb->eyoom_board_default($bo_table);
	}
?>