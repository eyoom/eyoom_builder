<?php
$sub_menu = '800100';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], "r");

$hostname = $eb->eyoom_host();

$tm_theme = $_POST['tm_theme'];
$tm_alias = $_POST['tm_alias'];

if($tm_alias) {
	$row = sql_fetch("select count(*) as cnt from {$g5['eyoom_theme']} where tm_alias='{$tm_alias}'",false);
	$cnt = $row['cnt'];
} else $cnt = 0;

if($cnt>0) {
	alert("이미 사용중인 별칭입니다. 다른 별칭을 입력해 주세요.");
} else {
	// 테마 테이블에 테마정보가 없다면 입력
	$row2 = sql_fetch("select count(*) as cnt from {$g5['eyoom_theme']} where tm_name='{$tm_theme}'");

	if(!$row2['cnt']) {
		$sql = "insert into {$g5['eyoom_theme']} set tm_name='{$tm_theme}', tm_alias='{$tm_alias}', tm_host='{$hostname['host']}', tm_time='".time()."'";
	} else {
		$sql = "update {$g5['eyoom_theme']} set tm_alias='{$tm_alias}',tm_host='{$hostname['host']}' where tm_name='{$tm_theme}'";
	}
	sql_query($sql,false);
}
?>
<script>
alert('정상적으로 테마의 별칭을 설정하였습니다.');
window.opener.location.reload();
window.close();
</script>
