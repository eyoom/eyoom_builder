<?php
$sub_menu = '800100';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], "r");

$eyoom_config_file = '../../data/eyoom.config.php';
include($eyoom_config_file);

if($_POST['countdown_use'] != 'y') {
	$set_default = true;
} else {
	if(!$_POST['cd_skin'] || !$_POST['cd_date'] || ! $_POST['cd_hour'] || !$_POST['cd_time']) {
		$set_default = true;
	}
}

$eyoom_config = array();
if(isset($set_default) && $set_default) {
	$eyoom_config['countdown'] = 'n';
	$eyoom_config['countdown_skin'] = '';
	$eyoom_config['countdown_date'] = '';
} else {
	$eyoom_config['countdown'] = 'y';
	$eyoom_config['countdown_skin'] = $_POST['cd_skin'];
	$eyoom_config['countdown_date'] = $_POST['cd_date'] . $_POST['cd_hour'] . $_POST['cd_time'];
}
// 설정 저장
$eyoom_config = $eyoom_config + $eyoom;
$qfile->save_file('eyoom',$eyoom_config_file,$eyoom_config);

?>
<script>
alert("공사중 설정을 적용하였습니다.");
window.opener.location.reload();
window.close();
</script>
