<?php
$sub_menu = '800200';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], "r");

$bo_table = $_POST['bo_table'];
$theme = $_POST['theme'];

$where = "bo_table='{$bo_table}' and bo_theme='{$theme}'";
$set = "
	use_shop_skin			= '{$_POST['use_shop_skin']}',
	bo_use_profile_photo	= '{$_POST['bo_use_profile_photo']}',
	bo_sel_date_type		= '{$_POST['bo_sel_date_type']}',
	bo_use_hotgul			= '{$_POST['bo_use_hotgul']}',
	bo_use_anonymous		= '{$_POST['bo_use_anonymous']}',
	bo_use_infinite_scroll	= '{$_POST['bo_use_infinite_scroll']}'
";
if(preg_match('/(community|dynamic)/',$theme)) {
$set .= ",
	bo_use_point_explain	= '{$_POST['bo_use_point_explain']}',
	bo_cmtpoint_target		= '{$_POST['bo_cmtpoint_target']}',
	bo_firstcmt_point		= '{$_POST['bo_firstcmt_point']}',
	bo_firstcmt_point_type	= '{$_POST['bo_firstcmt_point_type']}',
	bo_bomb_point			= '{$_POST['bo_bomb_point']}',
	bo_bomb_point_type		= '{$_POST['bo_bomb_point_type']}',
	bo_bomb_point_limit		= '{$_POST['bo_bomb_point_limit']}',
	bo_bomb_point_cnt		= '{$_POST['bo_bomb_point_cnt']}',
	bo_lucky_point			= '{$_POST['bo_lucky_point']}',
	bo_lucky_point_type		= '{$_POST['bo_lucky_point_type']}',
	bo_lucky_point_ratio	= '{$_POST['bo_lucky_point_ratio']}'
";
}

$sql = "update {$g5['eyoom_board']} set $set where $where";
sql_query($sql);

// 같은 그룹내 게시판 동일 옵션 적용
$grp_fields = '';
if (is_checked('chk_grp_shop_skin'))		$grp_fields .= " , use_shop_skin = '{$_POST['use_shop_skin']}' ";
if (is_checked('chk_grp_profile_photo'))	$grp_fields .= " , bo_use_profile_photo = '{$_POST['bo_use_profile_photo']}' ";
if (is_checked('chk_grp_date_type'))		$grp_fields .= " , bo_sel_date_type = '{$_POST['bo_sel_date_type']}' ";
if (is_checked('chk_grp_hotgul'))			$grp_fields .= " , bo_use_hotgul = '{$_POST['bo_use_hotgul']}' ";
if (is_checked('chk_grp_anonymous'))		$grp_fields .= " , bo_use_anonymous = '{$_POST['bo_use_anonymous']}' ";
if (is_checked('chk_grp_infinite_scroll'))	$grp_fields .= " , bo_use_infinite_scroll = '{$_POST['bo_use_infinite_scroll']}' ";
if (is_checked('chk_grp_point_explain'))	$grp_fields .= " , bo_use_point_explain = '{$_POST['bo_use_point_explain']}' ";
if (is_checked('chk_grp_cmtpoint_target'))	$grp_fields .= " , bo_cmtpoint_target = '{$_POST['bo_cmtpoint_target']}' ";
if (is_checked('chk_grp_firstcmt_point')) {
	$grp_fields .= " , bo_firstcmt_point		= '{$_POST['bo_firstcmt_point']}' ";
	$grp_fields .= " , bo_firstcmt_point_type	= '{$_POST['bo_firstcmt_point_type']}' ";
}
if (is_checked('chk_grp_bomb_point')) {
	$grp_fields .= " , bo_bomb_point			= '{$_POST['bo_bomb_point']}' ";
	$grp_fields .= " , bo_bomb_point_type		= '{$_POST['bo_bomb_point_type']}' ";
	$grp_fields .= " , bo_bomb_point_limit		= '{$_POST['bo_bomb_point_limit']}' ";
	$grp_fields .= " , bo_bomb_point_cnt		= '{$_POST['bo_bomb_point_cnt']}' ";
}
if (is_checked('chk_grp_lucky_point')) {
	$grp_fields .= " , bo_lucky_point			= '{$_POST['bo_lucky_point']}' ";
	$grp_fields .= " , bo_lucky_point_type		= '{$_POST['bo_lucky_point_type']}' ";
	$grp_fields .= " , bo_lucky_point_ratio		= '{$_POST['bo_lucky_point_ratio']}' ";
}

if ($grp_fields) {
    sql_query(" update {$g5['eyoom_board']} set bo_table = bo_table {$grp_fields} where gr_id = '{$_POST['gr_id']}' and bo_theme='{$theme}' ");
}

// 같은 그룹내 게시판 동일 옵션 적용
$all_fields = '';
if (is_checked('chk_all_shop_skin'))		$all_fields .= " , use_shop_skin = '{$_POST['use_shop_skin']}' ";
if (is_checked('chk_all_profile_photo'))	$all_fields .= " , bo_use_profile_photo = '{$_POST['bo_use_profile_photo']}' ";
if (is_checked('chk_all_date_type'))		$all_fields .= " , bo_sel_date_type = '{$_POST['bo_sel_date_type']}' ";
if (is_checked('chk_all_hotgul'))			$all_fields .= " , bo_use_hotgul = '{$_POST['bo_use_hotgul']}' ";
if (is_checked('chk_all_anonymous'))		$all_fields .= " , bo_use_anonymous = '{$_POST['bo_use_anonymous']}' ";
if (is_checked('chk_all_infinite_scroll'))	$all_fields .= " , bo_use_infinite_scroll = '{$_POST['bo_use_infinite_scroll']}' ";
if (is_checked('chk_all_point_explain'))	$all_fields .= " , bo_use_point_explain = '{$_POST['bo_use_point_explain']}' ";
if (is_checked('chk_all_cmtpoint_target'))	$all_fields .= " , bo_cmtpoint_target = '{$_POST['bo_cmtpoint_target']}' ";
if (is_checked('chk_all_firstcmt_point'))	{
	$all_fields .= " , bo_firstcmt_point		= '{$_POST['bo_firstcmt_point']}' ";
	$all_fields .= " , bo_firstcmt_point_type	= '{$_POST['bo_firstcmt_point_type']}' ";
}
if (is_checked('chk_all_bomb_point')) {
	$all_fields .= " , bo_bomb_point			= '{$_POST['bo_bomb_point']}' ";
	$all_fields .= " , bo_bomb_point_type		= '{$_POST['bo_bomb_point_type']}' ";
	$all_fields .= " , bo_bomb_point_limit		= '{$_POST['bo_bomb_point_limit']}' ";
	$all_fields .= " , bo_bomb_point_cnt		= '{$_POST['bo_bomb_point_cnt']}' ";
}
if (is_checked('chk_all_lucky_point')) {
	$all_fields .= " , bo_lucky_point			= '{$_POST['bo_lucky_point']}' ";
	$all_fields .= " , bo_lucky_point_type		= '{$_POST['bo_lucky_point_type']}' ";
	$all_fields .= " , bo_lucky_point_ratio		= '{$_POST['bo_lucky_point_ratio']}' ";
}

if ($all_fields) {
    sql_query(" update {$g5['eyoom_board']} set bo_table = bo_table {$all_fields} where bo_theme='{$theme}' ");
}

alert("정상적으로 적용하였습니다.","./board_form.php?bo_table={$bo_table}&thema={$theme}");

?>