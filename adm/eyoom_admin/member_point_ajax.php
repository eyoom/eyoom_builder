<?php
$sub_menu = "800700";
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], 'r');

$mb_id  = trim($_POST['mb_id']);
$po_calc  = trim($_POST['po_calc']);
$po_point  = trim($_POST['po_point']);
if(!$mb_id) exit;
if(!$po_calc) exit;
if(!$po_point) exit;

$sql = "select * from {$g5['eyoom_member']} as em left join {$g5['member_table']} as gm on em.mb_id=gm.mb_id where em.mb_id='{$mb_id}' limit 1";
$eyoomer = sql_fetch($sql, false);

$output['mb_id'] = $mb_id;
$output['eyoom_point'] = $eyoomer['level_point'];
switch($po_calc) {
	case 'plus': $output['clac_point'] = $eyoomer['level_point'] + $po_point; break;
	case 'minus': $output['clac_point'] = $eyoomer['level_point'] - $po_point; break;
}

include_once EYOOM_CLASS_PATH."/json.class.php";

$json = new Services_JSON();
$data = $json->encode($output);
echo $data;
exit;
?>