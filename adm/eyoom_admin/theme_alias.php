<?php
$sub_menu = '800100';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(EYOOM_PATH.'/common.php');
auth_check($auth[$sub_menu], "r");

$g5['title'] = '테마별칭설정';

include_once(G5_PATH.'/head.sub.php');
$hostname = $eb->eyoom_host();
if($_GET['thema'] != 'basic') {
	$row = sql_fetch("select * from {$g5['eyoom_theme']} where tm_name='{$_GET['thema']}' and tm_host='{$hostname['host']}'",false);
} else {
	$row = sql_fetch("select * from {$g5['eyoom_theme']} where tm_name='basic'",false);
}
?>

<div id="wrapper" style="min-width:100%;">

    <div id="container" style="min-width:100%;">

        <h2>[<b style="color:#f30;"><?php echo $_GET['thema']?></b>] 테마 별칭설정</h2>
		<link rel="stylesheet" href="./css/eyoom_admin.css">
		<form name="ftheme" action="./theme_alias_update.php" onsubmit="return ftheme_check(this)" method="post">
		<input type="hidden" name="tm_theme" id="tm_theme" value="<?php echo $_GET['thema']?>">
		<section id="anc_scf_info">
		<div class="tbl_frm01 tbl_wrap">
			<table>
			<caption>테마설정</caption>
			<colgroup>
				<col class="grid_3">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<th scope="row"><label for="theme">별칭입력</label></th>
				<td><?php echo G5_URL?>/?theme=<input type="text" name="tm_alias" value="<?php echo $row['tm_alias']?>" id="tm_alias" class="frm_input" size="15"></td>
			</tr>
			<tr>
				<td colspan="2" style="line-height:20px;">
					테마에 다른 별칭을 만들 수 있습니다.<br><br>
					웹사이트를 다국어 버전으로 제작할 경우 유용하며 <br>1차메뉴들을 각각 다른 테마로 구성할 수도 있습니다.<br><br>
					별칭 설정 예) korean, english 설정할 경우<br>
					<ul>
						<li>국문 홈페이지 주소 : <?php echo G5_URL?>/?theme=korean</li>
						<li>영문 홈페이지 주소 : <?php echo G5_URL?>/?theme=english</li>
					</ul>
				</td>
			</tr>

			</table>

		</section>

		<div class="btn_confirm01 btn_confirm">
			<input type="submit" value="적용하기" class="btn_submit" accesskey="s">
			<a href="javascript:self.close();">창닫기</a>
		</div>
		</form>
	</div>
</div>
<script>
function ftheme_check(f) {
	return true;
}
</script>
</body>
</html>
