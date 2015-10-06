<?php
$sub_menu = "800500";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

if($_GET['bn_no']) {
	sql_query("delete from {$g5['eyoom_banner']} where bn_no = '{$_GET['bn_no']}'");
} else {
	if(!is_array($chk)) {
		alert("선택한 배너가 없습니다.");
		echo "
		<script>document.history.go(-1);</script>
		";
	} else {
		foreach($chk as $bn_no) {
			sql_query("delete from {$g5['eyoom_banner']} where bn_no = '$bn_no'");
		}
	}
}
goto_url('./banner_list.php?'.$qstr.'&amp;thema='.$_GET['thema'], false);

?>
