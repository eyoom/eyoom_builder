<?php
$sub_menu = "800500";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');


if(!is_array($chk)) {
	alert("선택한 자료가 없습니다.");
	echo "
	<script>document.history.go(-1);</script>
	";
} else {
	foreach($chk as $bn_no) {
		sql_query("update {$g5['eyoom_banner']} set bn_state='".$bn_state[$bn_no]."' where bn_no = '$bn_no'");
	}
}
goto_url('./banner_list.php?'.$qstr, false);

?>
