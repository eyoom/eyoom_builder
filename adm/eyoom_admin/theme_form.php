<?php
$sub_menu = '800100';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(EYOOM_PATH.'/common.php');
auth_check($auth[$sub_menu], "r");

$g5['title'] = '테마설치';

include_once(G5_PATH.'/head.sub.php');
$hostname = $eb->eyoom_host();
if(preg_match('/basic/',$_GET['thema'])) {
	$ord_title = 'Eyoom 회원비밀번호';
} else {
	$ord_title = '테마 주문번호';
}
?>

<div id="wrapper" style="min-width:100%;">

    <div id="container" style="min-width:100%;">

        <h2>[<?php echo $_GET['thema']?>] 테마설치</h2>
		<link rel="stylesheet" href="./css/eyoom_admin.css">
		<form name="ftheme" action="./theme_form_update.php" onsubmit="return ftheme_check(this)" method="post">
		<input type="hidden" name="theme_key" id="theme_key" value="">
		<input type="hidden" name="tm_code" id="tm_code" value="">
		<input type="hidden" name="tm_time" id="tm_time" value="">
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
				<th scope="row"><label for="theme">테마명</label></th>
				<td><input type="text" name="tm_theme" value="<?php echo $_GET['thema'] ?>" id="tm_theme" required readonly class="required frm_input" size="40"></td>
			</tr>
			<tr>
				<th scope="row"><label for="theme">홈페이지 주소</label></th>
				<td><input type="text" name="tm_host" value="<?php echo $hostname['host'] ?>" id="tm_host" required readonly class="required frm_input" size="40"></td>
			</tr>
			<tr>
				<th scope="row"><label for="theme">Eyoom 회원아이디</label></th>
				<td><input type="text" name="tm_mb_id" value="" id="tm_mb_id" required class="required frm_input" size="40"></td>
			</tr>
			<tr>
				<th scope="row"><label for="theme"><?php echo $ord_title;?></label></th>
				<td><input type="text" name="tm_ordno" value="" id="tm_ordno" required class="required frm_input" size="40"></td>
			</tr>
			</table>

		</section>

		<div class="btn_confirm01 btn_confirm">
			<input type="submit" value="테마설치하기" class="btn_submit" accesskey="s">
			<a href="javascript:self.close();">창닫기</a>
		</div>
		</form>
	</div>
</div>
<script>
function ftheme_check(f) {
	if(f.tm_theme.value == '') {
		alert('테마명은 필수항목입니다.');
		f.tm_theme.focus();
		f.tm_theme.select();
		return false;
	}
	if(f.tm_host.value == '') {
		alert('홈페이지 주소는 필수항목입니다.');
		f.tm_theme.focus();
		f.tm_theme.select();
		return false;
	}
	if(f.tm_mb_id.value == '') {
		alert('Eyoom 회원아이디는 필수항목입니다.');
		f.tm_mb_id.focus();
		f.tm_mb_id.select();
		return false;
	}
	if(f.tm_ordno.value == '') {
		alert('<?php echo $ord_title;?>는 필수항목입니다.');
		f.tm_ordno.focus();
		f.tm_ordno.select();
		return false;
	}
	
	var type = 'install';
	var theme = f.tm_theme.value;
	var host = f.tm_host.value;
	var user = f.tm_mb_id.value;
	var ordno = f.tm_ordno.value;

	$.ajax({
		url: '<?php echo EYOOM_AJAX_URL;?>',
		data: {type: type, theme: theme, host: host, user: user, ordno: ordno},
		dataType: 'jsonp',
		jsonp: 'callback',
		jsonpCallback: 'setCode',
		success: function(){}
	});
	return false;
}
function setCode(data) {
	var f = document.ftheme;
	if(data.tm_key && data.code && data.time) {
		f.theme_key.value = data.tm_key;
		f.tm_code.value = data.code;
		f.tm_time.value = data.time;
		f.submit();
	} else {
		alert(data.msg);
	}
}
</script>
</body>
</html>
