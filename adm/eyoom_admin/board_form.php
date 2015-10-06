<?php
$sub_menu = "800200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

include './eyoom_theme.php';

$g5['title'] = '게시판설정';
include_once('../admin.head.php');
if($eyoom_board['bo_use_profile_photo'] == '1') $checked['bo_use_profile_photo'] = true; else $checked['bo_use_profile_photo'] = false;
if($eyoom_board['bo_sel_date_type'] == '1') $checked['bo_sel_date_type1'] = true; else $checked['bo_sel_date_type2'] = true;
if($eyoom_board['bo_sel_nameview'] == '1') $checked['bo_sel_nameview1'] = true; else $checked['bo_sel_nameview2'] = true;
if($eyoom_board['bo_use_level_icon'] == '1') $checked['bo_use_level_icon'] = true; else $checked['bo_use_level_icon'] = false;
if($eyoom_board['bo_use_hotgul'] == '1') $checked['bo_use_hotgul'] = true; else $checked['bo_use_hotgul'] = false;

$frm_submit = '
<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s">
    <a href="./board_list.php">목록</a>
</div>
';
?>
<link rel="stylesheet" href="./css/eyoom_admin.css">
<form name="ftheme" id="ftheme" action="./board_list_update.php" onsubmit="return ftheme_submit(this);" method="post">
<input type="hidden" name="mode" id="mode" value="board">
<input type="hidden" name="theme" id="theme" value="<?php echo $_theme;?>">
<input type="hidden" name="ref" id="ref" value="board_list.php">

<?php include './theme_manager.php'; // 테마 메니저 ?>

</form>

<h2 class="h2_frm">[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] 게시판설정 <span class='exp' style="color:#f30;"><?php echo $eyoom_board['gr_subject'].' - '.$eyoom_board['bo_subject'];?></span></h2>
<form name="fboardform" id="fboardform" action="./board_form_update.php" onsubmit="return fboardform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="bo_table" id="bo_table" value="<?php echo $board['bo_table'];?>">
<input type="hidden" name="theme" id="theme" value="<?php echo $_theme;?>">
<input type="hidden" name="gr_id" id="gr_id" value="<?php echo $board['gr_id'];?>">
<section id="anc_bo_basic">
    <h2 class="h2_frm">공통 설정</h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>공통 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="bo_use_profile_photo">프로필사진</label></th>
            <td>
                <label for="bo_use_profile_photo"><input type="checkbox" name="bo_use_profile_photo" value="1" id="bo_use_profile_photo" <?php echo $checked['bo_use_profile_photo']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_profile_photo" value="1" id="chk_grp_profile_photo">
                <label for="chk_grp_user_photo">그룹적용</label>
                <input type="checkbox" name="chk_all_profile_photo" value="1" id="chk_all_profile_photo">
                <label for="chk_all_user_photo">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_sel_date_type">날짜표현</label></th>
            <td>
                <label for="bo_sel_date_type1"><input type="radio" name="bo_sel_date_type" value="1" id="bo_sel_date_type1" <?php echo $checked['bo_sel_date_type1']?'checked':''; ?>>
                초/분/시간 형식 <span class="exp">예) 10초전 or 2분전</span></label>
				<label for="bo_sel_date_type2"><input type="radio" name="bo_sel_date_type" value="2" id="bo_sel_date_type2" <?php echo $checked['bo_sel_date_type2']?'checked':''; ?>>
                날짜 형식 <span class="exp">예) YYYY-mm-dd HH:ii:ss</span></label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_date_type" value="1" id="chk_grp_date_type">
                <label for="chk_grp_date_type">그룹적용</label>
                <input type="checkbox" name="chk_all_date_type" value="1" id="chk_all_date_type">
                <label for="chk_all_date_type">전체적용</label>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="bo_use_hotgul">베스트 최근글</label></th>
            <td>
				<label for="bo_use_hotgul"><input type="checkbox" name="bo_use_hotgul" value="1" id="bo_use_hotgul" <?php echo $checked['bo_use_hotgul']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_hotgul" value="1" id="chk_grp_hotgul">
                <label for="chk_grp_hotgul">그룹적용</label>
                <input type="checkbox" name="chk_all_hotgul" value="1" id="chk_all_hotgul">
                <label for="chk_all_hotgul">전체적용</label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['PHP_SELF'].'?'.$qstr.'&amp;page='); ?>

<script>
function fboardform_submit(f)
{
    return true;
}
</script>

<?php
include_once('../admin.tail.php');
?>
