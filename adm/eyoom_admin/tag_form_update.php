<?php
$sub_menu = "800600";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

check_token();

if ($w == '')
{
	$tg_word = get_text(trim($_POST['tg_word']));
	$tg_theme = get_text(trim($_POST['tg_theme']));
	if($tag != '전체') {
		$cnt = sql_fetch(" select count(tg_id) as cnt from {$g5['eyoom_tag']} where tg_word='{$tg_word}' and tg_theme='{$tg_theme}' ");
		if(!$cnt['cnt']) {
			$sql = " insert into {$g5['eyoom_tag']} set tg_word='{$tg_word}', tg_theme='{$tg_theme}', tg_regcnt='0', tg_regdt = '".G5_TIME_YMDHIS."' ";
			sql_query($sql, false);
		}
    }
    $go_url = './tag_list.php?';

} else if ($w == 'd') {
	$tg_id = (int)$_GET['tg_id'];
	$sql = " delete from {$g5['eyoom_tag']} where tg_id = '{$tg_id}' ";
    sql_query($sql, false);
    $go_url = './tag_list.php?';
    
} else if ($w == 'u') {
	if(!$_POST['tg_id']) alert('잘못된 접근입니다.');
	$tg_id = (int)$_POST['tg_id'];
	$tg_word = get_text(trim($_POST['tg_word']));
	$tg_theme = get_text(trim($_POST['tg_theme']));
	$tg_dpmenu = get_text(trim($_POST['tg_dpmenu']));
	
	$set = "
		tg_word = '" . $tg_word . "', 
		tg_theme = '" . $tg_theme . "', 
		tg_scnt = '" . (int)$_POST['tg_scnt'] . "', 
		tg_regcnt = '" . (int)$_POST['tg_regcnt'] . "', 
		tg_score = '" . (int)$_POST['tg_score'] . "', 
		tg_dpmenu = '" . $tg_dpmenu . "', 
	";
	
	$set .= "tg_regdt = tg_regdt";
	
	$sql = "update {$g5['eyoom_tag']} set {$set} where tg_id='{$tg_id}' ";
	sql_query($sql, false);
	$go_url = './tag_form.php?thema=' . $tg_theme . 'tg_id=' . $tg_id;
}

goto_url($go_url . $qstr);
?>
