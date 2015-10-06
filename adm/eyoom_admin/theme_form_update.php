<?php
$sub_menu = '800100';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], "r");

$where = "tm_name='{$_POST['tm_theme']}' and tm_ordno='{$_POST['tm_ordno']}'";
$row = sql_fetch("select count(*) as cnt from {$g5['eyoom_theme']} where $where");
$set = "
	tm_name = '".$_POST['tm_theme']."',
	tm_code = '".$_POST['tm_code']."',
	tm_host = '".$_POST['tm_host']."',
	tm_mb_id = '".$_POST['tm_mb_id']."',
	tm_ordno = '".$_POST['tm_ordno']."',
	tm_time = '".$_POST['tm_time']."'
";
if($row['cnt']>0) {
	sql_query("update {$g5['eyoom_theme']} set $set where $where");
} else {
	sql_query("insert into {$g5['eyoom_theme']} set $set");
}
$i=0;
foreach($eyoom as $key => $val) {
	if($i==1) $outarray['theme_key'] = $_POST['theme_key'];
	if($i==0) $outarray[$key] = $_POST['tm_theme'];
	else $outarray[$key] = $val;
	$i++;
}

// 다국어버전 테마 유무
if(preg_match('/mlang/',$_POST['tm_theme'])) {
	$outarray['theme_lang_type'] = 'm';
}

// PC/MO 테마 인지 반응형인지 구분
if(preg_match('/pc_/',$_POST['tm_theme'])) {
	$outarray['bootstrap'] = 0;
}
$theme_config = '../../data/eyoom.'.$_POST['tm_theme'].'.config.php';
$qfile->save_file('eyoom',$theme_config,$outarray,false);

?>
<script>
alert("테마설치를 완료하였습니다.");
window.opener.location.reload();
window.close();
</script>
