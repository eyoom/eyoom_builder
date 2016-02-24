<?php
$sub_menu = "800600";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$token = get_token();

include './eyoom_theme.php';

if(!$tg_id) alert('잘못된 접근입니다.');
$row = sql_fetch("select * from {$g5[eyoom_tag]} where tg_id = '".$tg_id."'");
foreach($row as $k => $v) {
	$tag[$k] = $v;
}

if (!$tag['tg_id']) alert('잘못된 접근입니다.');

$html_title = '수정';

$g5['title'] .= '태그 '.$html_title;
include_once('../admin.head.php');

?>
<link rel="stylesheet" href="./css/eyoom_admin.css">
<style>
	.frm_input {margin:2px 0;}
</style>
<form name="ftheme" onsubmit="return ftheme_check(this)" method="post">
<input type="hidden" name="mode" id="mode" value="skin">
<input type="hidden" name="theme" id="theme" value="<?php if($_theme) echo $_theme; else echo $theme;?>">
<input type="hidden" name="ref" id="ref" value="banner_list.php">

<?php include './theme_manager.php'; // 테마 메니저 ?>

<h2 class="h2_frm">[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] 태그 설정 <span class='exp'>테마별로 태그를 관리합니다.</span></h2>
</form>
<br>

<form name="ftag" id="ftag" action="./tag_form_update.php" onsubmit="return ftag_submit(this);" method="post">
<input type="hidden" name="tg_id" value="<?php echo $tg_id ?>">
<input type="hidden" name="tg_theme" id="tg_theme" value="<?php if($_theme) echo $_theme; else echo $theme;?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="w" value="u">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_3">
        <col>
        <col class="grid_3">
        <col>
    </colgroup>
    <tbody>
	<tr>
        <th scope="row"><label for="tg_word">태그명<?php echo $sound_only ?></label></th>
		<td>
			<input type="text" name="tg_word" id="tg_word" value="<?php echo $tag['tg_word']?>" class="frm_input" style="width:100px;" maxlength="10">
		</td>
		<th scope="row"><label for="tg_dpmenu">태그메뉴노출<?php echo $sound_only ?></label></th>
		<td>
			<input type="radio" name="tg_dpmenu" id="tg_dpmenu1" value="y" <?php if($tag['tg_dpmenu'] == 'y') echo 'checked';?>> <label for="tg_dpmenu1"> 사용</label> &nbsp; &nbsp;
			<input type="radio" name="tg_dpmenu" id="tg_dpmenu2" value="n" <?php if($tag['tg_dpmenu'] == 'n') echo 'checked';?>> <label for="tg_dpmenu2"> 미사용</label>
		</td>
	</tr>
	<tr>
        <th scope="row"><label for="tg_regcnt">등록수<?php echo $sound_only ?></label></th>
		<td>
			<input type="text" name="tg_regcnt" id="tg_regcnt" value="<?php echo $tag['tg_regcnt']?>" class="frm_input" style="width:100px;text-align: right;">
		</td>
		<th scope="row"><label for="tg_scnt">검색수<?php echo $sound_only ?></label></th>
		<td>
			<input type="text" name="tg_scnt" id="tg_scnt" value="<?php echo $tag['tg_scnt']?>" class="frm_input" style="width:100px;text-align: right;">
		</td>
	</tr>
	<tr>
        <th scope="row"><label for="tg_score">노출점수<?php echo $sound_only ?></label></th>
		<td>
			<input type="text" name="tg_score" id="tg_score" value="<?php echo $tag['tg_score']?>" class="frm_input" style="width:100px;text-align: right;">
		</td>
		<th scope="row"><label for="tg_regdt">등록일<?php echo $sound_only ?></label></th>
		<td>
			<?php echo $tag['tg_regdt'];?>
		</td>
	</tr>
    </tbody>
    </table>
</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey='s'>
    <a href="./tag_list.php?<?php echo $qstr ?>">목록</a>
</div>
</form>

<script>

function ftag_submit(f)
{
    return true;
}
</script>

<?php
include_once('../admin.tail.php');
?>
