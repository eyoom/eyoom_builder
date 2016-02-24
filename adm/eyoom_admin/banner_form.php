<?php
$sub_menu = "800500";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

include './eyoom_theme.php';

$banner_config = G5_DATA_PATH.'/banner/banner.'.$_theme.'.config.php';
if(file_exists($banner_config)) {
	@include_once($banner_config);
	if(is_array($bn_loccd)) ksort($bn_loccd);
} else {
	alert("배너위치를 지정해 주셔야 합니다.");
}

if ($w == '')
{
    $sound_only = '<strong class="sound_only">필수</strong>';
    $html_title = '추가';
}
else if ($w == 'u')
{
	$row = sql_fetch("select * from {$g5[eyoom_banner]} where bn_no = '{$bn_no}' and bn_theme='{$_theme}'");
	foreach($row as $k => $v) {
		$banner[$k] = $v;
	}
    if (!$banner['bn_no'])
        alert('존재하지 않는 배너광고입니다.');

    $html_title = '수정';

    $banner['bn_type'] = get_text($banner['bn_type']);
    $banner['bn_link'] = get_text($banner['bn_link']);
    $banner['bn_target'] = get_text($banner['bn_target']);
    $banner['bn_image'] = get_text($banner['bn_image']);
    $banner['bn_state'] = get_text($banner['bn_state']);
    $banner['bn_code'] = stripslashes($banner['bn_code']);
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');

$g5['title'] .= '배너광고 '.$html_title;
include_once('../admin.head.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
?>
<link rel="stylesheet" href="./css/calendar.css">
<script src="./js/calendar.js"></script>
<form name="fbanner" id="fbanner" action="./banner_form_update.php" onsubmit="return fbanner_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="bn_no" value="<?php echo $bn_no ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="theme" value="<?php echo $_theme ?>">

<h2 class="h2_frm">[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] 배너/광고 추가 </h2>

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="bn_location">배너위치설정<?php echo $sound_only ?></label></th>
        <td>
            <select name="bn_location" id="bn_location" required class="frm_input">
				<option value="">배너위치를 선택해 주세요.</option>
				<?php foreach($bn_loccd as $key => $val) {?>
				<option value="<?php echo $key;?>" <?php if($banner['bn_location'] == $key) echo "selected";?>><?php echo $key .'.'. $val;?></option>
				<?php }?>
			</select>
        </td>
		<th scope="row"><label for="bn_state">게시여부<?php echo $sound_only ?></label></th>
        <td>
            <select name="bn_state" id="bn_state" required class="frm_input">
				<option value="">선택</option>
				<option value="1" <?php if($banner['bn_state'] == '1') echo "selected";?>>배너 보이기</option>
				<option value="2" <?php if($banner['bn_state'] == '2') echo "selected";?>>배너 숨기기</option>
			</select>
        </td>
    </tr>
	<tr>
        <th scope="row"><label for="bn_start">기간<?php echo $sound_only ?></label></th>
        <td>
			<input type="radio" name="bn_period" id="bn_period_1" value="1" <?php if(!$banner['bn_period'] || $banner['bn_period'] == '1') echo "checked";?>> <label for="bn_period_1">무제한</label> &nbsp;&nbsp;
			<input type="radio" name="bn_period" id="bn_period_2" value="2" <?php if($banner['bn_period'] == '2') echo "checked";?>> <label for="bn_period_2">기간선택</label>
			<input type="text" name="bn_start" id="bn_start" class="frm_input" size="15" value="<?php echo $banner['bn_start']?>" onclick="calendar(event);"> - 
			<input type="text" name="bn_end" id="bn_end" class="frm_input" size="15" value="<?php echo $banner['bn_end']?>" onclick="calendar(event);">
        </td>
		<th scope="row"><label for="bn_type">배너종류<?php echo $sound_only ?></label></th>
        <td>
            <select name="bn_type" id="bn_type" required class="frm_input">
				<option value="">선택</option>
				<option value="intra" <?php if($banner['bn_type'] == 'intra') echo "selected";?>>내부광고/배너</option>
				<option value="extra" <?php if($banner['bn_type'] == 'extra') echo "selected";?>>외부배너[구글 애드센스등]</option>
			</select>
        </td>
    </tr>
	<tr>
        <th scope="row"><label for="bn_link">연결주소 [링크]<?php echo $sound_only ?></label></th>
		<td colspan="3">
			<input type="text" name="bn_link" id="bn_link" class="frm_input" size="80" value="<?php echo $banner['bn_link']?>">
			<select name="bn_target" class="frm_input">
			  <option value="">타겟을 선택하세요.</option>
			  <option value="_blank" <?php if($banner['bn_target']=='_blank') echo "selected";?>>새창</option>
			  <option value="_self" <?php if($banner['bn_target']=='_self') echo "selected";?>>현재창</option>
			</select>
		</td>
	</tr>
	<tr>
        <th scope="row"><label for="bn_link">권한설정<?php echo $sound_only ?></label></th>
		<td colspan="3">
			<?php echo get_member_level_select('bn_view_level', 1, 10, $banner['bn_view_level']) ?> 레벨(그누레벨) 이상 회원에게 배너/광고를 노출합니다.
		</td>
	</tr>
    <tr>
        <th scope="row"><label for="bn_img">배너이미지</label></th>
        <td colspan="3">
            <input type="file" name="bn_img" id="bn_img">
            <?php
            $bn_file = G5_DATA_PATH.'/banner/'.$row['bn_theme'].'/'.$banner['bn_img'];
            if (file_exists($bn_file) && $banner['bn_img']) {
                $bn_url = G5_DATA_URL.'/banner/'.$row['bn_theme'].'/'.$banner['bn_img'];
                echo '<img src="'.$bn_url.'" alt="" height=80> ';
                echo '<input type="checkbox" id="del_bn_url" name="del_bn_url" value="1"><input type="hidden" name="del_bn_url_name" id="del_bn_url_name" value="'.$banner['bn_img'].'">삭제';
            } else {
				echo '<input type="hidden" name="del_bn_url_name" id="del_bn_url_name" value="">';
			}
            ?>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="mg_index">외부배너 소스<br><span style="font-weight:normal;">(구글애드센스 코드 등)</span></label></th>
        <td colspan="3"><textarea  name="bn_code" id="bn_code" style="height:300px;"><?php echo $banner['bn_code'] ?></textarea></td>
    </tr>

    </tbody>
    </table>
</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey='s'>
    <a href="./banner_list.php?thema=<?php echo $_theme;?>&<?php echo $qstr ?>">목록</a>
</div>
</form>

<script>

function fbanner_submit(f)
{
	if($("#bn_location > option:selected").val() == '') {
		alert("배너위치를 선택해 주세요.");
		$("#bn_location").focus();
		return false;
	}
	if($("#bn_state > option:selected").val() == '') {
		alert("게시여부를 선택해 주세요.");
		$("#bn_state").focus();
		return false;
	}
	
	if($(":radio[name='bn_period']:checked").val() == '2') {
		if($("#bn_start").val() == '') {
			alert("시작일을 입력해 주세요.");
			$("#bn_start").focus();
			return false;
		}
		if($("#bn_end").val() == '') {
			alert("종료일을 입력해 주세요.");
			$("#bn_end").focus();
			return false;
		}
	}
	if($("#bn_type > option:selected").val() == '') {
		alert("배너종류를 선택해 주세요.");
		$("#bn_type").focus();
		return false;
	}
	if($("#bn_type > option:selected").val() == 'intra') {
		if($("#bn_img").val() == '' && $("#del_bn_url_name").val() == '') {
			alert("배너이미지를 등록해 주세요.");
			$("#bn_img").focus();
			return false;
		}
	}
	if($("#bn_type > option:selected").val() == 'extra') {
		if($("#bn_code").val() == '') {
			alert("배너코드를 입력해 주세요.");
			$("#bn_code").focus();
			return false;
		}
	}
	


    return true;
}
</script>

<?php
include_once('../admin.tail.php');
?>
