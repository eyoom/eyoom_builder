<?php
$sub_menu = '800100';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], "r");

if(!$_POST['tm_theme_add']) $_POST['tm_theme_add'] = 'c1';
$tm_theme = $_POST['tm_theme'] . "_" . $_POST['tm_theme_add'];

$theme_path = EYOOM_PATH."/theme/";

// 웹서버의 쓰기권한이 있어야 함
if(!is_writable($theme_path)) {
	alert('['.$theme_path.'] 폴더의 퍼미션 문제가 있어 테마를 복사하실 수 없습니다.');
	exit;
}

// 기존에 해당 테마가 있는지 체크
if( is_dir($theme_path.$tm_theme) ){
	alert($tm_theme . '와 동일한 테마가 이미 존재합니다.');
	exit;
}

// 파일 및 폴더 옮기기
$qfile->copy_dir($theme_path.$_POST['tm_theme'],$theme_path.$tm_theme);

// 이윰환결설정 복사
if($_POST['clone_config'] == 'y') {
	$config_file = G5_DATA_PATH.'/eyoom.'.$_POST['tm_theme'].'.config.php';
	if(file_exists($config_file)) include($config_file);
	unset($eyoom['theme_key']);
} else {
	include(eyoom_config);
}

// 게시판설정 복사
if($_POST['clone_board'] == 'y') {
	$sql = "select * from {$g5['eyoom_board']} where bo_theme = '{$_POST['tm_theme']}'";
	$res = sql_query($sql,false);
	for($i=0;$row=sql_fetch_array($res);$i++) {
		$sql = "insert into {$g5['eyoom_board']} set ";
		foreach($row as $key => $val) {
			if($key == "bo_id") continue;
			if($key == "bo_theme") $val = $tm_theme;
			$sql .= "$key = '{$val}',";
		}
		$sql = substr($sql,0,-1);
		sql_query($sql,false);
		unset($sql);
	}
}

// 메뉴설정 복사
if($_POST['clone_menu'] == 'y') {
	$sql = "select * from {$g5['eyoom_menu']} where me_theme = '{$_POST['tm_theme']}'";
	$res = sql_query($sql,false);
	for($i=0;$row=sql_fetch_array($res);$i++) {
		$sql = "insert into {$g5['eyoom_menu']} set ";
		foreach($row as $key => $val) {
			if($key == "me_id") continue;
			if($key == "me_theme") $val = $tm_theme;
			$sql .= "$key = '{$val}',";
		}
		$sql = substr($sql,0,-1);
		sql_query($sql,false);
		unset($sql);
	}
}

$where = "tm_name='{$tm_theme}' and tm_ordno='{$_POST['tm_ordno']}'";
$row = sql_fetch("select count(*) as cnt from {$g5['eyoom_theme']} where $where");
$set = "
	tm_name = '".$tm_theme."',
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
	if($i==0) $outarray[$key] = $tm_theme;
	else $outarray[$key] = $val;
	$i++;
}
$theme_config = '../../data/eyoom.'.$tm_theme.'.config.php';
$qfile->save_file('eyoom',$theme_config,$outarray,false);

?>
<script>
alert('정상적으로 테마를 복사하였습니다.');
window.opener.location.reload();
window.close();
</script>
