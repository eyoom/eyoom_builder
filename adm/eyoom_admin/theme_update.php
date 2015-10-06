<?php
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], "r");

$eyoom_config_file = '../../data/eyoom.config.php';
include($eyoom_config_file);

$eyoom_config = array();
$eyoom_config['theme'] = $_POST['theme'];
$eyoom_config = $eyoom_config + $eyoom;
$qfile->save_file('eyoom',$eyoom_config_file,$eyoom_config);
if($unique_theme_id = get_cookie('unique_theme_id')) {
	$file = $qfile->tmp_path . '/' . $_SERVER['REMOTE_ADDR'] . '.' . $unique_theme_id . '.php';
	$qfile->del_file($file);
}
goto_url('./'.$_POST['ref']);

?>

