<?php
$sub_menu = '800100';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');
auth_check($auth[$sub_menu], "r");

$cd_date = $eb->mktime_countdown_date($eyoom_basic['countdown_date']);

$g5['title'] = '공사중 설정';

include_once(G5_PATH.'/head.sub.php');
?>
<link rel="stylesheet" href="./css/calendar.css">
<script src="./js/calendar.js"></script>
<div id="wrapper" style="min-width:100%;">

    <div id="container" style="min-width:100%;">

        <h2>공사중 설정하기</h2>
		<link rel="stylesheet" href="./css/eyoom_admin.css">
		<form name="fcountdown" action="./countdown_form_update.php" onsubmit="return fcountdown_check(this)" method="post">
		<section id="anc_scf_info">
		<div class="tbl_frm01 tbl_wrap">
			<table>
			<caption>공사중 설정</caption>
			<colgroup>
				<col class="grid_3">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<th scope="row"><label for="countdown">공사중 사용여부</label></th>
				<td>
					<label for="countdown_use"><input type="checkbox" name="countdown_use" value="y" id="countdown_use" <?php if($eyoom_basic['countdown'] == 'y') echo 'checked';?>> 사용</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="countdown">스킨선택</label></th>
				<td>
					<select name="cd_skin" id="cd_skin">
						<option value="">::스킨선택::</option>
						<?php
						$arr = get_skin_dir('countdown',EYOOM_THEME_PATH);
						for ($i=0; $i<count($arr); $i++) {
						?>
						<option value="<?php echo $arr[$i];?>" <?php if($arr[$i] == $eyoom_basic['countdown_skin']) echo 'selected'; ?>><?php echo $arr[$i];?></option>
						<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="clone_config">정상 오픈일시</label></th>
				<td>
					<input type="text" name="cd_date" id="cd_date" class="frm_input" size="15" value="<?php if($cd_date) echo date('Ymd',$cd_date['mktime']);?>" onclick="calendar(event);">
					<select name="cd_hour" id="cd_hour">
						<option value="">::선택::</option>
						<?php
						for ($i=0; $i<24; $i++) {
							if($i<10) $k = '0'.$i; else $k=$i;
						?>
						<option value="<?php echo $k;?>" <?php if($k == $cd_date['hour']) echo 'selected';?>><?php echo $k;?></option>
						<?php
						}
						?>
					</select>
					시
					<select name="cd_time" id="cd_time">
						<option value="">::선택::</option>
						<?php
						for ($i=0; $i<6; $i++) {
							$k = $i*10;
							if($k==0) $k='00';
						?>
						<option value="<?php echo $k;?>" <?php if($k == $cd_date['minute']) echo 'selected';?>><?php echo $k;?></option>
						<?php
						}
						?>
					</select>
					분
				</td>
			</tr>
			</table>

		</section>

		<div class="btn_confirm01 btn_confirm">
			<input type="submit" value="공사중 설정적용" class="btn_submit" accesskey="s">
			<a href="javascript:self.close();">창닫기</a>
		</div>
		</form>
	</div>
</div>
<script>
function fcountdown_check(f) {
	if($("input:checkbox[id='countdown_use']").is(":checked") == true) {
		if($("#cd_skin option:selected").val() == '') {
			alert('공사중 스킨을 선택해 주세요.');
			f.cd_skin.focus();
			return false;
		}
		if($("#cd_date").val().length != '8') {
			alert('날짜를 정확히 입력해 주세요.');
			f.cd_date.focus();
			return false;
		}
		if($("#cd_hour option:selected").val() == '') {
			alert('시간을 선택해 주세요.');
			f.cd_hour.focus();
			return false;
		}
		if($("#cd_time option:selected").val() == '') {
			alert('분을 선택해 주세요.');
			f.cd_time.focus();
			return false;
		}
	}
}
</script>
</body>
</html>
