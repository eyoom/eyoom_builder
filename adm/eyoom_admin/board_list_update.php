<?php
$sub_menu = '800200';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], "r");

switch($mode) {
	case 'theme':
		include './theme_update.php';
		break;

	case 'board':
		$board_table = $_POST['board_table'];
		if(!$board_table) $board_table = array();
		$query='';
		foreach($board_table as $key => $val) {
			$bo_table = $val;
			$bo_skin = $_POST["bo_skin_{$key}"];
			$use_gnu_skin = $_POST["use_gnu_skin_{$key}"];
			$query = "update {$g5['eyoom_board']} set bo_skin='{$bo_skin}', use_gnu_skin='{$use_gnu_skin}' where bo_table='{$bo_table}' and bo_theme='{$_POST['theme']}'";
			sql_query($query);
		}
		alert('게시판설정을 적용하였습니다.','./'.$_POST['ref'].'?thema='.$_POST['theme']);
		break;
}

?>

