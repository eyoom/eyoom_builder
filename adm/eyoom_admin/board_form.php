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
if($eyoom_board['bo_use_anonymous'] == '1') $checked['bo_use_anonymous'] = true; else $checked['bo_use_anonymous'] = false;
if($eyoom_board['bo_use_infinite_scroll'] == '1') $checked['bo_use_infinite_scroll'] = true; else $checked['bo_use_infinite_scroll'] = false;
if($eyoom_board['bo_use_point_explain'] == '1') $checked['bo_use_point_explain'] = true; else $checked['bo_use_point_explain'] = false;
if($eyoom_board['bo_cmtpoint_target'] == '2') $checked['bo_cmtpoint_target2'] = true; else $checked['bo_cmtpoint_target1'] = true;

// 쇼핑몰 테마인가?
if(preg_match('/pc_/',$theme)) {
	$device = 'pc';
} else {
	$device = 'bs';
}
$shop_dir = G5_PATH.'/eyoom/theme/'.$theme.'/skin_'.$device.'/shop/';
$is_shop_theme = is_dir($shop_dir) ? true : false;

$frm_submit = '
<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s">
    <a href="./board_list.php?thema='.$_theme.'">목록</a>
	<a href="'.G5_BBS_URL.'/board.php?bo_table='.$board['bo_table'].'" class="btn_frmline">게시판 바로가기</a>
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
		<?php if($is_shop_theme) {?>
		<tr>
            <th scope="row"><label for="bo_use_profile_photo">레이아웃 디자인</label></th>
            <td>
                <label for="use_shop_skin_1">
					<input type="radio" name="use_shop_skin" id="use_shop_skin_1" value="n" <?php echo $eyoom_board['use_shop_skin']=='n'?'checked':''; ?>> 커뮤니티 디자인 레이아웃 적용
				</label>
				<label for="use_shop_skin_2">
					<input type="radio" name="use_shop_skin" id="use_shop_skin_2" value="y" <?php echo $eyoom_board['use_shop_skin']=='y'?'checked':''; ?>> 쇼핑몰 디자인 레이아웃 적용 [<b style='color:#f30;'><?php echo $theme;?></b> 테마의 쇼핑몰 스킨 레이아웃]
				</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_shop_skin" value="1" id="chk_grp_shop_skin">
                <label for="chk_grp_shop_skin">그룹적용</label>
                <input type="checkbox" name="chk_all_shop_skin" value="1" id="chk_all_shop_skin">
                <label for="chk_all_shop_skin">전체적용</label>
            </td>
        </tr>
		<?php }?>
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
		<tr>
            <th scope="row"><label for="bo_use_anonymous">익명글쓰기</label></th>
            <td>
				<label for="bo_use_anonymous"><input type="checkbox" name="bo_use_anonymous" value="1" id="bo_use_anonymous" <?php echo $checked['bo_use_anonymous']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_anonymous" value="1" id="chk_grp_anonymous">
                <label for="chk_grp_anonymous">그룹적용</label>
                <input type="checkbox" name="chk_all_anonymous" value="1" id="chk_all_anonymous">
                <label for="chk_all_anonymous">전체적용</label>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="bo_use_infinite_scroll">목록에서 무한스크롤</label></th>
            <td>
				<label for="bo_use_infinite_scroll"><input type="checkbox" name="bo_use_infinite_scroll" value="1" id="bo_use_infinite_scroll" <?php echo $checked['bo_use_infinite_scroll']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_infinite_scroll" value="1" id="chk_grp_infinite_scroll">
                <label for="chk_grp_infinite_scroll">그룹적용</label>
                <input type="checkbox" name="chk_all_infinite_scroll" value="1" id="chk_all_infinite_scroll">
                <label for="chk_all_infinite_scroll">전체적용</label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>
<?php if(preg_match('/(community|dynamic)/',$_theme)) {?>
<section id="anc_bo_auth">
    <h2 class="h2_frm">[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] 댓글 <?php echo $levelset['gnu_name'];?> 설정 <span class="exp">이곳의 포인트는 이윰레벨과 무관한 그누보드 회원의 <?php echo $levelset['gnu_name'];?>에 해당하는 부분입니다.</span></h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption><?php echo $levelset['gnu_name'];?> 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
		<tr>
			<td colspan="3">
				<?php echo help('<strong>중요</strong> : 영카트5를 이용하시고 <a href="'.G5_URL.'/adm/config_form.php"><strong>포인트 사용</strong></a> 설정이 <strong>[사용]</strong>으로 체크되어 있을 경우, 상품구매를 위해 결제 수단으로 사용될 수 있는 포인트입니다.') ?>
			</td>
		</tr>
		<tr>
            <th scope="row"><label for="bo_use_point_explain">댓글포인트 설명</label></th>
            <td>
				<label for="bo_use_point_explain"><input type="checkbox" name="bo_use_point_explain" value="1" id="bo_use_point_explain" <?php echo $checked['bo_use_point_explain']?'checked':''; ?>> 보이기</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_point_explain" value="1" id="chk_grp_point_explain">
                <label for="chk_grp_point_explain">그룹적용</label>
                <input type="checkbox" name="chk_all_point_explain" value="1" id="chk_all_point_explain">
                <label for="chk_all_point_explain">전체적용</label>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="bo_use_point_explain">댓글포인트 적용대상</label></th>
            <td>
				<label for="bo_cmtpoint_target1">
					<input type="radio" name="bo_cmtpoint_target" value="1" id="bo_cmtpoint_target1" <?php echo $checked['bo_cmtpoint_target1']?'checked':''; ?>> 그누보드 포인트로 적립
				</label>
				<label for="bo_cmtpoint_target2">
					<input type="radio" name="bo_cmtpoint_target" value="2" id="bo_cmtpoint_target2" <?php echo $checked['bo_cmtpoint_target2']?'checked':''; ?>> 이윰레벨 포인트로 적립
				</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_cmtpoint_target" value="1" id="chk_grp_cmtpoint_target">
                <label for="chk_grp_cmtpoint_target">그룹적용</label>
                <input type="checkbox" name="chk_all_cmtpoint_target" value="1" id="chk_all_cmtpoint_target">
                <label for="chk_all_cmtpoint_target">전체적용</label>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="bo_firstcmt_point">첫 댓글 <?php echo $levelset['gnu_name'];?></label></th>
            <td>
				<?php echo help('게시물의 첫번째 댓글에 주는 '.$levelset['gnu_name'].'입니다. 0으로 설정하시면 기능이 작동하지 않습니다.') ?>
				<input type="text" name="bo_firstcmt_point" value="<?php echo $eyoom_board['bo_firstcmt_point'] ?>" id="bo_firstcmt_point" required class="required frm_input" size="5"> <?php echo $levelset['gnu_name'];?>를 
				<select name="bo_firstcmt_point_type" id="bo_firstcmt_point_type">
					<option value="1">최대로 하여 랜덤</option>
					<option value="2">고정</option>
				</select>으로 첫 댓글 작성자에게 지급합니다.
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_firstcmt_point" value="1" id="chk_grp_firstcmt_point">
                <label for="chk_grp_firstcmt_point">그룹적용</label>
                <input type="checkbox" name="chk_all_firstcmt_point" value="1" id="chk_all_firstcmt_point">
                <label for="chk_all_firstcmt_point">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_bomb_point">지뢰 <?php echo $levelset['gnu_name'];?></label></th>
            <td>
				<?php echo help('게시물이 작성이 되면 자동으로 포인트 지뢰가 심어집니다. 지뢰 폭탄을 터트릴 경우 주어지는 포인트입니다.') ?>
				<input type="text" name="bo_bomb_point" value="<?php echo $eyoom_board['bo_bomb_point'] ?>" id="bo_bomb_point" required class="required frm_input" size="5"> <?php echo $levelset['gnu_name'];?>를 
				<select name="bo_bomb_point_type" id="bo_bomb_point_type">
					<option value="1">최대로 하여 랜덤</option>
					<option value="2">고정</option>
				</select>으로
				<input type="text" name="bo_bomb_point_limit" value="<?php echo $eyoom_board['bo_bomb_point_limit'] ?>" id="bo_bomb_point_limit" required class="required frm_input" size="5">개 댓글 수 이내에 지뢰 포인트를 <input type="text" name="bo_bomb_point_cnt" value="<?php echo $eyoom_board['bo_bomb_point_cnt'] ?>" id="bo_bomb_point_cnt" required class="required frm_input" size="5">개 매설합니다.
			</td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_bomb_point" value="1" id="chk_grp_bomb_point">
                <label for="chk_grp_bomb_point">그룹적용</label>
                <input type="checkbox" name="chk_all_bomb_point" value="1" id="chk_all_bomb_point">
                <label for="chk_all_bomb_point">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_lucky_point">럭키 <?php echo $levelset['gnu_name'];?></label></th>
            <td>
				<?php echo help('댓글을 작성할 경우, 지정한 확률에 따라 자동으로 행운의 포인트를 지급합니다.') ?>
				<input type="text" name="bo_lucky_point" value="<?php echo $eyoom_board['bo_lucky_point'] ?>" id="bo_lucky_point" required class="required frm_input" size="5"> <?php echo $levelset['gnu_name'];?>를 
				<select name="bo_lucky_point_type" id="bo_lucky_point_type">
					<option value="1">최대로 하여 랜덤</option>
					<option value="2">고정</option>
				</select>으로 럭키포인트를 100회 중
				<input type="text" name="bo_lucky_point_ratio" value="<?php echo $eyoom_board['bo_lucky_point_ratio'] ?>" id="bo_lucky_point_ratio" required class="required frm_input" size="5">회의 확률로 지급합니다.
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_lucky_point" value="1" id="chk_grp_lucky_point">
                <label for="chk_grp_lucky_point">그룹적용</label>
                <input type="checkbox" name="chk_all_lucky_point" value="1" id="chk_all_lucky_point">
                <label for="chk_all_lucky_point">전체적용</label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>
<?php } ?>
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
