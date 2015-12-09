<?php
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], "r");

$eyoom_config_file = '../../data/eyoom.config.php';
include($eyoom_config_file);
if(preg_match('/mlang/',$_POST['theme'])) $eyoom['theme_lang_type'] = 'm';

// 쇼핑몰 홈테마 변경시, 커뮤니티홈테마는 그대로 
$eyoom_config = array();
if($_POST['theme_target'] != 'shop') $eyoom_config['theme'] = $_POST['theme'];

// 지정한 테마에 쇼핑몰 스킨이 존재하는지 체크
if(preg_match('/pc_/',$_POST['shop_theme'])) {
	$device = 'pc';
} else {
	$device = 'bs';
}

// 쇼핑몰 스킨이 있는 테마인지 구별
if($_POST['theme_target'] == 'shop') {
	$shop_dir = G5_PATH.'/eyoom/theme/'.$_POST['shop_theme'].'/skin_'.$device.'/shop/';
	if(!is_dir($shop_dir) && $_POST['shop_theme']) alert("선택한 테마는 쇼핑몰 테마가 아닙니다.");
}

// 설정 저장
$eyoom_config['shop_theme'] = $_POST['shop_theme'];
$eyoom_config = $eyoom_config + $eyoom;
$qfile->save_file('eyoom',$eyoom_config_file,$eyoom_config);
if($unique_theme_id = get_cookie('unique_theme_id')) {
	$file = $qfile->tmp_path . '/' . $_SERVER['REMOTE_ADDR'] . '.' . $unique_theme_id . '.php';
	$qfile->del_file($file);
}
goto_url('./'.$_POST['ref']);

?>