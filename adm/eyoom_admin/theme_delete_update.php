<?php
$sub_menu = '800100';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], "r");

$tm_theme = $_POST['tm_theme'];
$theme_path = EYOOM_PATH."/theme/";

// 웹서버의 쓰기권한이 있어야 함
if(!is_writable($theme_path)) {
	alert('['.$theme_path.'] 폴더의 퍼미션 문제가 있어 테마를 삭제하실 수 없습니다.');
	exit;
}

if($tm_theme == 'basic') {
	alert('Basic 테마는 삭제하실 수 없습니다.');
	exit;
} else {
	// 파일 및 폴더 삭제
	if(is_dir($theme_path.$tm_theme)) $qfile->del_all_file($theme_path.$tm_theme);

	// 이윰환결설정 삭제
	$config_file = G5_DATA_PATH.'/eyoom.'.$tm_theme.'.config.php';
	$qfile->del_file($config_file);

	// 게시판설정 삭제
	$sql = "delete from {$g5['eyoom_board']} where bo_theme = '{$tm_theme}'";
	sql_query($sql,false);

	// 이윰테마정보 삭제
	$sql = "delete from {$g5['eyoom_theme']} where tm_name = '{$tm_theme}'";
	sql_query($sql,false);

	// 이윰메뉴정보 삭제
	$sql = "delete from {$g5['eyoom_menu']} where me_theme = '{$tm_theme}'";
	sql_query($sql,false);

	// 배너/광고파일 삭제
	$banner_path = G5_DATA_PATH.'/banner';
	$banner_config = $banner_path.'/banner.'.$tm_theme.'.config.php';
	$banner_file = $banner_path.'/'.$tm_theme;
	$qfile->del_file($banner_config);
	if(is_dir($banner_file)) $qfile->del_all_file($banner_file);

	$sql = "delete from {$g5['eyoom_banner']} where bn_theme = '{$tm_theme}'";
	sql_query($sql,false);
}
?>
<script>
alert('정상적으로 테마를 삭제하였습니다.');
window.opener.location.reload();
window.close();
</script>
