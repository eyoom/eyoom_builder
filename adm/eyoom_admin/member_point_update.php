<?php
$sub_menu = "800500";
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], "r");

$mb_id  = trim($_POST['mb_id']);
$po_calc  = trim($_POST['po_calc']);
$po_point  = trim($_POST['po_point']);

$sql = "select * from {$g5['eyoom_member']} as em left join {$g5['member_table']} as gm on em.mb_id=gm.mb_id where em.mb_id='{$mb_id}' limit 1";
$eyoomer = sql_fetch($sql, false);

if (!$eyoomer['mb_id'])
    alert('존재하는 회원아이디가 아닙니다.', './member_list.php?'.$qstr);
if($po_calc == 'minus') {
	if (($po_point > $eyoomer['level_point']))
		alert($levelset['eyoom_name'].'를 깎는 경우 현재 '.$levelset['eyoom_name'].'보다 작으면 안됩니다.', './member_list.php?'.$qstr);
}

$eyoom_point = $eyoomer['level_point'];
switch($po_calc) {
	case 'plus': $calc_point = $eyoom_point + $po_point; break;
	case 'minus': $calc_point = $eyoom_point - $po_point; break;
}

sql_query("update {$g5['eyoom_member']} set level_point = '{$calc_point}' where mb_id = '{$mb_id}'");

goto_url('./member_list.php?'.$qstr);

?>