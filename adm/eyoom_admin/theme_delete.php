<?php
$sub_menu = '800100';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(EYOOM_PATH.'/common.php');
auth_check($auth[$sub_menu], "r");

$g5['title'] = '테마삭제';

include_once(G5_PATH.'/head.sub.php');
$hostname = $eb->eyoom_host();
if($_GET['thema'] != 'basic') {
	$row = sql_fetch("select * from {$g5['eyoom_theme']} where tm_name='{$_GET['thema']}' and tm_host='{$hostname['host']}'",false);
}
?>

<div id="wrapper" style="min-width:100%;">

    <div id="container" style="min-width:100%;">

        <h2>[<?php echo $_GET['thema']?>] 테마삭제</h2>
		<link rel="stylesheet" href="./css/eyoom_admin.css">
		<form name="ftheme" action="./theme_delete_update.php" onsubmit="return ftheme_check(this)" method="post">
		<input type="hidden" name="tm_mb_id" id="tm_mb_id" value="<?php echo $row['tm_mb_id'];?>">
		<input type="hidden" name="tm_theme" id="tm_theme" value="<?php echo $_GET['thema'] ?>">
		<input type="hidden" name="tm_host" id="tm_host" value="<?php echo $hostname['host']; ?>">
		<input type="hidden" name="tm_ordno" id="tm_ordno" value="<?php echo $row['tm_ordno'] ?>">
		<section id="anc_scf_info">
		<div class="tbl_frm01 tbl_wrap">
			<table>
			<caption>삭제안내</caption>
			<colgroup>
				<col class="grid_3">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<td style="line-height:20px;">
				<?php if($_GET['thema'] != 'basic') {?>
				테마를 삭제하실 경우,  <br><br>
				[<b style='color:#f30;'><?php echo $_GET['thema']?></b>] 테마의 스킨파일 및 기본설정 / 게시판설정 / 이윰메뉴설정 / 배너광고 파일 등 <br>
				테마와 관련된 모든 내용 및 자료가 함께 삭제됩니다.<br><br>
				신중히 검토하신 후 삭제해 주시기 바랍니다.<br><br>
				<span style="color:#f30;">테마를 삭제하고난 후, 삭제되지 않은 파일이 있을 수 있습니다.<br>FTP로 접속하여 잔여파일 및 [<b><?php echo $_GET['thema']?></b>] 테마폴더를 강제로 삭제해 주시기 바랍니다.</span><br>
				<?php } else {?>
				[<b style='color:#f30;'><?php echo $_GET['thema']?></b>] 테마는 삭제하실 수 없습니다.
				<?php }?>
				</td>
			</tr>
			</table>

		</section>

		<div class="btn_confirm01 btn_confirm">
			<input type="submit" value="삭제하기" class="btn_submit" accesskey="s">
			<a href="javascript:self.close();">창닫기</a>
		</div>
		</form>
	</div>
</div>
<script>
function ftheme_check(f) {
	if(f.tm_theme.value=='basic') {
		alert('Basic 테마는 삭제하실 수 없습니다.');
		return false;
	}
	var type = 'delete';
	var theme = f.tm_theme.value;
	var host = f.tm_host.value;
	var user = f.tm_mb_id.value;
	var ordno = f.tm_ordno.value;
	if(ordno) {
		$.ajax({
			url: '<?php echo EYOOM_AJAX_URL;?>',
			data: {type: type, theme: theme, host: host, user: user, ordno: ordno},
			dataType: 'jsonp',
			jsonp: 'callback',
			jsonpCallback: 'setCode',
			success: function(){}
		});
		return false;
	} else {
		var f = document.ftheme;
		f.submit();
	}
}
function setCode(data) {
	var f = document.ftheme;
	if(data.del == 'ok') {
		f.submit();
	} else {
		alert(data.msg);
	}
}
</script>
</body>
</html>
