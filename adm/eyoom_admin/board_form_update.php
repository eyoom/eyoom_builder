<?php
$sub_menu = '800200';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], "r");

$bo_table = $_POST['bo_table'];
$theme = $_POST['theme'];

$where = "bo_table='{$bo_table}' and bo_theme='{$theme}'";
$set = "
	bo_use_profile_photo	= '{$_POST['bo_use_profile_photo']}',
	bo_sel_date_type		= '{$_POST['bo_sel_date_type']}',
	bo_use_hotgul			= '{$_POST['bo_use_hotgul']}',
	bo_use_anonymous		= '{$_POST['bo_use_anonymous']}'
";

$sql = "update {$g5['eyoom_board']} set $set where $where";
sql_query($sql);

// 같은 그룹내 게시판 동일 옵션 적용
$grp_fields = '';
if (is_checked('chk_grp_profile_photo'))	$grp_fields .= " , bo_use_profile_photo = '{$_POST['bo_use_profile_photo']}' ";
if (is_checked('chk_grp_date_type'))		$grp_fields .= " , bo_sel_date_type = '{$_POST['bo_sel_date_type']}' ";
if (is_checked('chk_grp_hotgul'))			$grp_fields .= " , bo_use_hotgul = '{$_POST['bo_use_hotgul']}' ";
if (is_checked('chk_grp_anonymous'))		$grp_fields .= " , bo_use_anonymous = '{$_POST['bo_use_anonymous']}' ";

if ($grp_fields) {
    sql_query(" update {$g5['eyoom_board']} set bo_table = bo_table {$grp_fields} where gr_id = '{$_POST['gr_id']}' and bo_theme='{$theme}' ");
}

// 같은 그룹내 게시판 동일 옵션 적용
$all_fields = '';
if (is_checked('chk_all_profile_photo'))	$all_fields .= " , bo_use_profile_photo = '{$_POST['bo_use_profile_photo']}' ";
if (is_checked('chk_all_date_type'))		$all_fields .= " , bo_sel_date_type = '{$_POST['bo_sel_date_type']}' ";
if (is_checked('chk_all_hotgul'))			$all_fields .= " , bo_use_hotgul = '{$_POST['bo_use_hotgul']}' ";
if (is_checked('chk_all_anonymous'))		$all_fields .= " , bo_use_anonymous = '{$_POST['bo_use_anonymous']}' ";

if ($all_fields) {
    sql_query(" update {$g5['eyoom_board']} set bo_table = bo_table {$all_fields} where bo_theme='{$theme}' ");
}

alert("정상적으로 적용하였습니다.","./board_form.php?bo_table={$bo_table}&thema={$theme}");

?>