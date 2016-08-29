<?php
$sub_menu = '800100';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(EYOOM_PATH.'/common.php');
auth_check($auth[$sub_menu], "r");

$g5['title'] = '테마설치';

include_once(G5_PATH.'/head.sub.php');
$hostname = $eb->eyoom_host();
if($_GET['thema'] != 'basic') {
	$row = sql_fetch("select * from {$g5['eyoom_theme']} where tm_name='{$_GET['thema']}' and tm_host='{$hostname['host']}'",false);
}
?>

<div id="wrapper" style="min-width:100%;">

    <div id="container" style="min-width:100%;">

        <h2>[<?php echo $_GET['thema']?>] 테마 복사하기</h2>
		<link rel="stylesheet" href="./css/eyoom_admin.css">
		<form name="ftheme" action="./theme_clone_update.php" onsubmit="return ftheme_check(this)" method="post">
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
				<td colspan="2" style="text-align:center;">
					복사를 위해 <strong>/eyoom/theme</strong> 폴더, 모든 하위폴더 및 파일에 웹서버의 쓰기권한이 있어야 합니다.<br>
					<div style="padding:10px;border:2px solid #f30;margin:5px;">
					chmod -R 707 /eyoom/theme
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="theme">새로운 테마명</label></th>
				<td>
					<input type="text" name="tm_theme" value="<?php echo $_GET['thema'] ?>" id="tm_theme" required readonly class="required frm_input" size="20">_<input type="text" name="tm_theme_add" value="" id="tm_theme_add" required class="frm_input required" size="12"><span class="exp">공백없이 영문,숫자 (4자이내)</span>
					<input type="hidden" name="tm_host" value="<?php echo $hostname['host'] ?>" id="tm_host">
					<input type="hidden" name="tm_ordno" value="<?php echo $row['tm_ordno'] ?>" id="tm_ordno">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="theme">Eyoom 회원아이디</label></th>
				<td><input type="text" name="tm_mb_id" value="" id="tm_mb_id" required class="required frm_input" size="34"></td>
			</tr>
			<tr>
				<th scope="row"><label for="clone_config">기본설정 복사여부</label></th>
				<td>
					<label for="clone_config1"><input type="radio" name="clone_config" id="clone_config1" value="y" checked> 기본설정 복사</label>
					<label for="clone_config2"><input type="radio" name="clone_config" id="clone_config2" value="n"> 복사하지 않음</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="clone_board">게시판 복사여부</label></th>
				<td>
					<label for="clone_board1"><input type="radio" name="clone_board" id="clone_board1" value="y" checked> 게시판 설정 복사</label>
					<label for="clone_board2"><input type="radio" name="clone_board" id="clone_board2" value="n"> 복사하지 않음</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="clone_menu">이윰메뉴 복사여부</label></th>
				<td>
					<label for="clone_menu1"><input type="radio" name="clone_menu" id="clone_menu1" value="y" checked> 메뉴 설정 복사</label>
					<label for="clone_menu2"><input type="radio" name="clone_menu" id="clone_menu2" value="n"> 복사하지 않음</label>
				</td>
			</tr>
			</table>

		</section>

		<div class="btn_confirm01 btn_confirm">
			<input type="submit" value="복사하기" class="btn_submit" accesskey="s">
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
	if(f.tm_theme_add.value == '') {
		alert('테마명에 접미사를 입력해 주세요.');
		f.tm_theme_add.focus();
		f.tm_theme_add.select();
		return false;
	}
	if(f.tm_host.value == '') {
		alert('홈페이지 주소는 필수항목입니다.');
		f.tm_host.focus();
		f.tm_host.select();
		return false;
	}
	if(f.tm_mb_id.value == '') {
		alert('Eyoom 회원아이디는 필수항목입니다.');
		f.tm_mb_id.focus();
		f.tm_mb_id.select();
		return false;
	}
	if(f.tm_ordno.value == '' && f.tm_theme.value!='basic' && f.tm_theme.value!='basic2' && f.tm_theme.value!='pc_basic2') {
		alert('테마 주문번호는 필수항목입니다.');
		f.tm_ordno.focus();
		f.tm_ordno.select();
		return false;
	}
	
	var type = 'clone';
	var theme = f.tm_theme.value;
	var add = f.tm_theme_add.value;
	var host = f.tm_host.value;
	var user = f.tm_mb_id.value;
	var ordno = f.tm_ordno.value;
	$.ajax({
		url: '<?php echo EYOOM_AJAX_URL;?>',
		data: {type: type, theme: theme, add: add, host: host, user: user, ordno: ordno},
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
