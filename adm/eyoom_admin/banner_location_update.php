<?php
$sub_menu = "800500";
include_once("./_common.php");

auth_check($auth[$sub_menu], 'w');

$bn_loccd = $_POST['bn_loccd'];
ksort($bn_loccd);

$theme = $_POST['theme'];
if(!$theme) alert("테마 선택에 문제가 있습니다.");

$banner_file = G5_DATA_PATH."/banner/banner.".$theme.".config.php";
@include ($banner_file);
if($_POST['banner_count'] != count($bn_loccd)) {
	for($i=1;$i<=$_POST['banner_count'];$i++) {
		$value = $_POST['bn_loccd'][$i];
		if(!$value) $value = '배너위치입력';
		$loccd[$i] = $value;
	}
	$bn_loccd = $loccd;
} else {
	$bn_loccd = $_POST['bn_loccd'];
}

$qfile->save_file('bn_loccd', $banner_file, $bn_loccd, true);

alert("배너위치정보를 수정하였습니다.", './banner_location.php?thema='.$theme);

?>