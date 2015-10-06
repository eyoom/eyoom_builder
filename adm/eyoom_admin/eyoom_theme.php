<?php
	include(eyoom_config);
	$theme = $eyoom['theme'];
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
	$_eyoom = $eyoom;
	unset($eyoom);
?>