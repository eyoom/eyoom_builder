<?php
$sub_menu = '800300';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');
auth_check($auth[$sub_menu], "r");

$theme = $_POST['theme'];
$type = $_POST['type'];
if(!$theme) exit;
if(!$type) exit;

switch($type) {
	case 'group':
		$sql = "select gr_id, gr_subject from {$g5['group_table']} where (1) order by gr_subject asc";
		$res = sql_query($sql,false);
		for($i=0;$row=sql_fetch_array($res);$i++) {
			$pid[$i] = $row['gr_id'];
			$name[$i] = $row['gr_subject'];
		}
		break;

	case 'board':
		$sql = "select a.bo_table, a.bo_subject, b.gr_subject from {$g5['board_table']} as a left join {$g5['group_table']} as b on a.gr_id=b.gr_id where 1 order by b.gr_id asc";
		$res = sql_query($sql,false);
		for($i=0;$row=sql_fetch_array($res);$i++) {
			$pid[$i] = $row['bo_table'];
			$name[$i] = $row['gr_subject'].' > '.$row['bo_subject'];
		}
		break;

	case 'page':
		$sql = "select co_id, co_subject from {$g5['content_table']} where (1) order by co_subject asc";
		$res = sql_query($sql,false);
		for($i=0;$row=sql_fetch_array($res);$i++) {
			$pid[$i] = $row['co_id'];
			$name[$i] = $row['co_subject'];
		}
		break;
}

$_value_array = array();
$_value_array['pid'] = implode('|',$pid);
$_value_array['name'] = implode('|',$name);

include_once EYOOM_CLASS_PATH."/json.class.php";

$json = new Services_JSON();
$output = $json->encode($_value_array);

echo $output;

?>