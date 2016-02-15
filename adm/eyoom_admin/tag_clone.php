<?php
$sub_menu = "800600";
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], 'r');

$theme = get_text($_POST['theme']);
$target = get_text($_POST['target']);
if(!$theme) exit;
if(!$target) exit;

$sql = "select * from {$g5['eyoom_tag']} where (1) and tg_theme = '{$target}' order by tg_regdt asc";
$res = sql_query($sql, false);
for($i=0; $row=sql_fetch_array($res); $i++) {
	$target_tag[$i] = $row['tg_word'];
}
if(!isset($target_tag)) $target_tag = array();
$sql = "select * from {$g5['eyoom_tag']} where (1) and tg_theme = '{$theme}' order by tg_regdt asc";
$res = sql_query($sql, false);
for($i=0; $row=sql_fetch_array($res); $i++) {
	if(!in_array($row['tg_word'], $target_tag)) {
		unset($set, $insert);
		$set = "
			tg_word = '" . $row['tg_word'] . "', 
			tg_theme = '" . $target . "', 
			tg_scnt = '" . $row['tg_scnt'] . "', 
			tg_regcnt = '" . $row['tg_regcnt'] . "', 
			tg_dpmenu = '" . $row['tg_dpmenu'] . "', 
			tg_score = '" . $row['tg_score'] . "', 
			tg_recommdt = '" . $row['tg_recommdt'] . "', 
			tg_regdt = '" . $row['tg_regdt'] . "'
		";
		
		$insert = "insert into {$g5['eyoom_tag']} set {$set}";
		sql_query($insert, false);
	}
}

$_value_array = array();
$_value_array['tag_clone'] = 'ok';

include_once EYOOM_CLASS_PATH."/json.class.php";

$json = new Services_JSON();
$output = $json->encode($_value_array);

echo $output;

?>