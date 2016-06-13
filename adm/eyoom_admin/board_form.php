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
if($eyoom_board['bo_use_cmt_infinite'] == '1') $checked['bo_use_cmt_infinite'] = true; else $checked['bo_use_cmt_infinite'] = false;
if($eyoom_board['bo_use_cmt_best'] == '1') $checked['bo_use_cmt_best'] = true; else $checked['bo_use_cmt_best'] = false;
if($eyoom_board['bo_use_point_explain'] == '1') $checked['bo_use_point_explain'] = true; else $checked['bo_use_point_explain'] = false;
if($eyoom_board['bo_use_list_image'] == '1') $checked['bo_use_list_image'] = true; else $checked['bo_use_list_image'] = false;
if($eyoom_board['bo_use_video_photo'] == '1') $checked['bo_use_video_photo'] = true; else $checked['bo_use_video_photo'] = false;
if($eyoom_board['bo_use_yellow_card'] == '1') $checked['bo_use_yellow_card'] = true; else $checked['bo_use_yellow_card'] = false;
if($eyoom_board['bo_use_exif'] == '1') $checked['bo_use_exif'] = true; else $checked['bo_use_exif'] = false;
if($eyoom_board['bo_use_rating'] == '1') $checked['bo_use_rating'] = true; else $checked['bo_use_rating'] = false;
if($eyoom_board['bo_use_rating_list'] == '1') $checked['bo_use_rating_list'] = true; else $checked['bo_use_rating_list'] = false;
if($eyoom_board['bo_use_tag'] == '1') $checked['bo_use_tag'] = true; else $checked['bo_use_tag'] = false;
if($eyoom_board['bo_use_summernote_mo'] == '1') $checked['bo_use_summernote_mo'] = true; else $checked['bo_use_summernote_mo'] = false;
if($eyoom_board['bo_use_automove'] == '1') $checked['bo_use_automove'] = true; else $checked['bo_use_automove'] = false;
if($eyoom_board['bo_use_addon_emoticon'] == '1') $checked['bo_use_addon_emoticon'] = true; else $checked['bo_use_addon_emoticon'] = false;
if($eyoom_board['bo_use_addon_video'] == '1') $checked['bo_use_addon_video'] = true; else $checked['bo_use_addon_video'] = false;
if($eyoom_board['bo_use_addon_coding'] == '1') $checked['bo_use_addon_coding'] = true; else $checked['bo_use_addon_coding'] = false;
if($eyoom_board['bo_use_addon_soundcloud'] == '1') $checked['bo_use_addon_soundcloud'] = true; else $checked['bo_use_addon_soundcloud'] = false;
if($eyoom_board['bo_use_addon_map'] == '1') $checked['bo_use_addon_map'] = true; else $checked['bo_use_addon_map'] = false;
if($eyoom_board['bo_use_addon_cmtimg'] == '1') $checked['bo_use_addon_cmtimg'] = true; else $checked['bo_use_addon_cmtimg'] = false;
if($eyoom_board['bo_use_extimg'] == '1') $checked['bo_use_extimg'] = true; else $checked['bo_use_extimg'] = false;
if($eyoom_board['bo_cmtpoint_target'] == '2') $checked['bo_cmtpoint_target2'] = true; else $checked['bo_cmtpoint_target1'] = true;

// EXIF 상세설정값
if(!$eyoom_board['bo_exif_detail']) {
	$exif_detail = $exif->get_exif_default();
} else {
	$exif_detail = unserialize(stripslashes($eyoom_board['bo_exif_detail']));
}

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
    <h2 class="h2_frm color-green">공통 설정</h2>

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
        <tr>
            <th scope="row"><label for="bo_use_cmt_infinite">댓글에서 무한스크롤</label></th>
            <td>
				<label for="bo_use_cmt_infinite"><input type="checkbox" name="bo_use_cmt_infinite" value="1" id="bo_use_cmt_infinite" <?php echo $checked['bo_use_cmt_infinite']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_cmt_infinite" value="1" id="chk_grp_cmt_infinite">
                <label for="chk_grp_cmt_infinite">그룹적용</label>
                <input type="checkbox" name="chk_all_cmt_infinite" value="1" id="chk_all_cmt_infinite">
                <label for="chk_all_cmt_infinite">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_use_list_image">목록에서 이미지 사용</label></th>
            <td>
				<label for="bo_use_list_image"><input type="checkbox" name="bo_use_list_image" value="1" id="bo_use_list_image" <?php echo $checked['bo_use_list_image']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_list_image" value="1" id="chk_grp_list_image">
                <label for="chk_grp_list_image">그룹적용</label>
                <input type="checkbox" name="chk_all_list_image" value="1" id="chk_all_list_image">
                <label for="chk_all_list_image">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_use_video_photo">목록에서 동영상이미지 사용</label></th>
            <td>
				<label for="bo_use_video_photo"><input type="checkbox" name="bo_use_video_photo" value="1" id="bo_use_video_photo" <?php echo $checked['bo_use_video_photo']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_video_photo" value="1" id="chk_grp_video_photo">
                <label for="chk_grp_video_photo">그룹적용</label>
                <input type="checkbox" name="chk_all_video_photo" value="1" id="chk_all_video_photo">
                <label for="chk_all_video_photo">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_use_extimg">외부이미지 썸네일기능 사용</label></th>
            <td>
				<label for="bo_use_extimg"><input type="checkbox" name="bo_use_extimg" value="1" id="bo_use_extimg" <?php echo $checked['bo_use_extimg']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_extimg" value="1" id="chk_grp_extimg">
                <label for="chk_grp_extimg">그룹적용</label>
                <input type="checkbox" name="chk_all_extimg" value="1" id="chk_all_extimg">
                <label for="chk_all_extimg">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_use_summernote_mo">모바일에서 Summernote 사용</label></th>
            <td>
				<label for="bo_use_summernote_mo"><input type="checkbox" name="bo_use_summernote_mo" value="1" id="bo_use_summernote_mo" <?php echo $checked['bo_use_summernote_mo']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_summernote_mo" value="1" id="chk_grp_summernote_mo">
                <label for="chk_grp_summernote_mo">그룹적용</label>
                <input type="checkbox" name="chk_all_summernote_mo" value="1" id="chk_all_summernote_mo">
                <label for="chk_all_summernote_mo">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="download_fee_ratio">파일다운로드 수수료율</label></th>
            <td>
				<input type="text" name="download_fee_ratio" value="<?php echo $eyoom_board['download_fee_ratio'] ?>" id="download_fee_ratio" class="frm_input" size="5"> % <span class="exp">공유자료실 스킨 (share)의 다운로드 수수료율 지정</span>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_download_ratio" value="1" id="chk_grp_download_ratio">
                <label for="chk_grp_download_ratio">그룹적용</label>
                <input type="checkbox" name="chk_all_download_ratio" value="1" id="chk_all_download_ratio">
                <label for="chk_all_download_ratio">전체적용</label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_bo_basic">
    <h2 class="h2_frm color-green">게시물 신고/블라인드 기능</h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>게시물 신고/블라인드 기능</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="bo_use_video_photo">게시물 신고/블라인드 기능 사용</label></th>
            <td>
				<label for="bo_use_yellow_card"><input type="checkbox" name="bo_use_yellow_card" value="1" id="bo_use_yellow_card" <?php echo $checked['bo_use_yellow_card']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_yellow_card" value="1" id="chk_grp_yellow_card">
                <label for="chk_grp_yellow_card">그룹적용</label>
                <input type="checkbox" name="chk_all_yellow_card" value="1" id="chk_all_yellow_card">
                <label for="chk_all_yellow_card">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_blind_limit">게시물 블라인드</label></th>
            <td>
				<input type="text" name="bo_blind_limit" value="<?php echo $eyoom_board['bo_blind_limit'] ?>" id="bo_blind_limit" class="frm_input" size="5"> 명 이상 신고된 게시물을 자동으로 블라인드 처리하기
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_blind_limit" value="1" id="chk_grp_blind_limit">
                <label for="chk_grp_blind_limit">그룹적용</label>
                <input type="checkbox" name="chk_all_blind_limit" value="1" id="chk_all_blind_limit">
                <label for="chk_all_blind_limit">전체적용</label>
            </td>
        </tr>
        <tr>
			<th scope="row"><label for="bo_blind_view">블라인드된 게시물 보기권한</label></th>
			<td>
				<select name="bo_blind_view" id="bo_blind_view">
					<option value="">선택</option>
					<?php 
					  for($i=1;$i<=10;$i++) {
					?>
					<option value="<?php echo $i;?>" <?php if($eyoom_board['bo_blind_view'] == $i) echo "selected";?>><?php echo $i;?></option>
					<?php
						}
					?>
				</select> 레벨
				<span class="exp">선택한 그누레벨 이상의 회원은 블라인드된 게시물을 볼 수 있습니다.</span>
			</td>
			<td class="td_grpset">
				<input type="checkbox" name="chk_grp_blind_view" value="1" id="chk_grp_blind_view">
				<label for="chk_grp_blind_view">그룹적용</label>
				<input type="checkbox" name="chk_all_blind_view" value="1" id="chk_all_blind_view">
				<label for="chk_all_blind_view">전체적용</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="bo_blind_direct">게시물 블라인드 설정권한</label></th>
			<td>
				<select name="bo_blind_direct" id="bo_blind_direct">
					<option value="">선택</option>
					<?php 
					  for($i=1;$i<=10;$i++) {
					?>
					<option value="<?php echo $i;?>" <?php if($eyoom_board['bo_blind_direct'] == $i) echo "selected";?>><?php echo $i;?></option>
					<?php
						}
					?>
				</select> 레벨
				<span class="exp">선택한 그누레벨 이상의 회원은 게시물을 바로 블라인드 처리할 수 있습니다.(아직 미적용 기능입니다.)</span>
			</td>
			<td class="td_grpset">
				<input type="checkbox" name="chk_grp_blind_direct" value="1" id="chk_grp_blind_direct">
				<label for="chk_grp_blind_direct">그룹적용</label>
				<input type="checkbox" name="chk_all_blind_direct" value="1" id="chk_all_blind_direct">
				<label for="chk_all_blind_direct">전체적용</label>
			</td>
		</tr>
        </tbody>
        </table>
    </div>
</section>

<section id="anc_bo_basic">
    <h2 class="h2_frm color-green">게시물 별점 기능</h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>게시물 별점 기능</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="bo_use_rating">게시물 별점 기능 사용</label></th>
            <td>
				<label for="bo_use_rating"><input type="checkbox" name="bo_use_rating" value="1" id="bo_use_rating" <?php echo $checked['bo_use_rating']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_rating" value="1" id="chk_grp_rating">
                <label for="chk_grp_rating">그룹적용</label>
                <input type="checkbox" name="chk_all_rating" value="1" id="chk_all_rating">
                <label for="chk_all_rating">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_use_rating_list">목록에서 별점 표시 사용</label></th>
            <td>
				<label for="bo_use_rating_list"><input type="checkbox" name="bo_use_rating_list" value="1" id="bo_use_rating_list" <?php echo $checked['bo_use_rating_list']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_rating_list" value="1" id="chk_grp_rating_list">
                <label for="chk_grp_rating_list">그룹적용</label>
                <input type="checkbox" name="chk_all_rating_list" value="1" id="chk_all_rating_list">
                <label for="chk_all_rating_list">전체적용</label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>

<section id="anc_bo_basic">
    <h2 class="h2_frm color-green">게시물 태그 기능</h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>게시물 태그 기능</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="bo_use_tag">게시물 태그 기능 사용</label></th>
            <td>
				<label for="bo_use_tag"><input type="checkbox" name="bo_use_tag" value="1" id="bo_use_tag" <?php echo $checked['bo_use_tag']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_use_tag" value="1" id="chk_grp_use_tag">
                <label for="chk_grp_use_tag">그룹적용</label>
                <input type="checkbox" name="chk_all_use_tag" value="1" id="chk_all_use_tag">
                <label for="chk_all_use_tag">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_tag_level">글쓰기시 태그 작성 권한</label></th>
            <td>
				<?php
					if(!isset($eyoom_board['bo_tag_level']) || $eyoom_board['bo_tag_level'] < $board['bo_write_level']) $eyoom_board['bo_tag_level'] = $board['bo_write_level'];
					echo get_member_level_select('bo_tag_level', 1, 10, $eyoom_board['bo_tag_level']);
				?>
				<span class="exp">게시판 글쓰기 권한과 같거나 높아야 합니다.</span>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_tag_level" value="1" id="chk_grp_tag_level">
                <label for="chk_grp_tag_level">그룹적용</label>
                <input type="checkbox" name="chk_all_tag_level" value="1" id="chk_all_tag_level">
                <label for="chk_all_tag_level">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_tag_limit">글쓰기시 입력가능한 태그수</label></th>
            <td>
				<input type="text" name="bo_tag_limit" id="bo_tag_limit" class="frm_input" value="<?php echo $eyoom_board['bo_tag_limit'];?>" size="5">
				<span class="exp">지정한 숫자를 초과하여 태그를 입력할 수 없습니다. [관리자는 제한없음]</span>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_tag_limit" value="1" id="chk_grp_tag_limit">
                <label for="chk_grp_tag_limit">그룹적용</label>
                <input type="checkbox" name="chk_all_tag_limit" value="1" id="chk_all_tag_limit">
                <label for="chk_all_tag_limit">전체적용</label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>

<section id="anc_bo_basic">
    <h2 class="h2_frm color-green">게시물 자동 이동/복사 기능</h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>기능설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="bo_use_tag">게시물 자동 이동/복사 기능 사용</label></th>
            <td>
				<label for="bo_use_automove"><input type="checkbox" name="bo_use_automove" value="1" id="bo_use_automove" <?php echo $checked['bo_use_automove']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_use_automove" value="1" id="chk_grp_use_automove">
                <label for="chk_grp_use_automove">그룹적용</label>
                <input type="checkbox" name="chk_all_use_automove" value="1" id="chk_all_use_automove">
                <label for="chk_all_use_automove">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_automove_cfg">이동/복사 조건</label></th>
            <td>
				<select name="bo_automove[type]" id="bo_automove_type">
					<option value="hit" <?php if($bo_automove['type'] == 'hit') echo "selected";?>>조회수</option>
					<option value="good" <?php if($bo_automove['type'] == 'good') echo "selected";?>>추천수</option>
					<option value="nogood" <?php if($bo_automove['type'] == 'nogood') echo "selected";?>>비추천수</option>
				</select>의 숫자가 
				<input type="text" name="bo_automove[count]" class="frm_input" value="<?php if($bo_automove['count']) echo $bo_automove['count']; else echo 100;?>" size="5"> 이상이면 
				<select name="bo_automove[target]" id="bo_automove_target">
					<option value="">::게시판선택::</option>
					<?php foreach($binfo as $bo) {?>
					<option value="<?php echo $bo['bo_table']?>" <?php if($bo_automove['target'] == $bo['bo_table']) echo "selected";?>><?php echo $bo['bo_subject'] . ' [' . $bo['bo_table'] . ']';?></option>
					<?php }?>
				</select> 게시판으로 
				<select name="bo_automove[action]" id="bo_automove_action">
					<option value="move" <?php if($bo_automove['action'] == 'move') echo "selected";?>>이동</option>
					<option value="copy" <?php if($bo_automove['action'] == 'copy') echo "selected";?>>복사</option>
				</select>합니다.
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_automove" value="1" id="chk_grp_automove">
                <label for="chk_grp_automove">그룹적용</label>
                <input type="checkbox" name="chk_all_automove" value="1" id="chk_all_automove">
                <label for="chk_all_automove">전체적용</label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_bo_basic">
    <h2 class="h2_frm color-green">애드온 기능</h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>글쓰기 애드온 기능</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="bo_use_addon_emoticon">이모티콘 추가 기능</label></th>
            <td>
				<label for="bo_use_addon_emoticon"><input type="checkbox" name="bo_use_addon_emoticon" value="1" id="bo_use_addon_emoticon" <?php echo $checked['bo_use_addon_emoticon']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_addon_emoticon" value="1" id="chk_grp_addon_emoticon">
                <label for="chk_grp_addon_emoticon">그룹적용</label>
                <input type="checkbox" name="chk_all_addon_emoticon" value="1" id="chk_all_addon_emoticon">
                <label for="chk_all_addon_emoticon">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_use_addon_video">동영상 추가 기능</label></th>
            <td>
				<label for="bo_use_addon_video"><input type="checkbox" name="bo_use_addon_video" value="1" id="bo_use_addon_video" <?php echo $checked['bo_use_addon_video']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_addon_video" value="1" id="chk_grp_addon_video">
                <label for="chk_grp_addon_video">그룹적용</label>
                <input type="checkbox" name="chk_all_addon_video" value="1" id="chk_all_addon_video">
                <label for="chk_all_addon_video">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_use_addon_coding">코드 표시 기능</label></th>
            <td>
				<label for="bo_use_addon_coding"><input type="checkbox" name="bo_use_addon_coding" value="1" id="bo_use_addon_coding" <?php echo $checked['bo_use_addon_coding']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_addon_coding" value="1" id="chk_grp_addon_coding">
                <label for="chk_grp_addon_coding">그룹적용</label>
                <input type="checkbox" name="chk_all_addon_coding" value="1" id="chk_all_addon_coding">
                <label for="chk_all_addon_coding">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_use_addon_soundcloud">사운드클라우드 추가 기능</label></th>
            <td>
				<label for="bo_use_addon_soundcloud"><input type="checkbox" name="bo_use_addon_soundcloud" value="1" id="bo_use_addon_soundcloud" <?php echo $checked['bo_use_addon_soundcloud']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_addon_soundcloud" value="1" id="chk_grp_addon_soundcloud">
                <label for="chk_grp_addon_soundcloud">그룹적용</label>
                <input type="checkbox" name="chk_all_addon_soundcloud" value="1" id="chk_all_addon_soundcloud">
                <label for="chk_all_addon_soundcloud">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_use_addon_map">지도 추가 기능</label></th>
            <td>
				<label for="bo_use_addon_map"><input type="checkbox" name="bo_use_addon_map" value="1" id="bo_use_addon_map" <?php echo $checked['bo_use_addon_map']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_addon_map" value="1" id="chk_grp_addon_map">
                <label for="chk_grp_addon_map">그룹적용</label>
                <input type="checkbox" name="chk_all_addon_map" value="1" id="chk_all_addon_map">
                <label for="chk_all_addon_map">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_use_addon_cmtimg">댓글에서 이미지 추가 기능</label></th>
            <td>
				<label for="bo_use_addon_cmtimg"><input type="checkbox" name="bo_use_addon_cmtimg" value="1" id="bo_use_addon_cmtimg" <?php echo $checked['bo_use_addon_cmtimg']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_addon_cmtimg" value="1" id="chk_grp_addon_cmtimg">
                <label for="chk_grp_addon_cmtimg">그룹적용</label>
                <input type="checkbox" name="chk_all_addon_cmtimg" value="1" id="chk_all_addon_cmtimg">
                <label for="chk_all_addon_cmtimg">전체적용</label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>

<section id="anc_bo_basic">
    <h2 class="h2_frm color-green">댓글 베스트 기능</h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>댓글 베스트 기능</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="bo_use_cmt_best">댓글 베스트 기능 사용</label></th>
            <td>
				<label for="bo_use_cmt_best"><input type="checkbox" name="bo_use_cmt_best" value="1" id="bo_use_cmt_best" <?php echo $checked['bo_use_cmt_best']?'checked':''; ?>> 사용</label> <span class="exp">대댓글은 베스트 댓글의 대상이 아닙니다.</span>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_cmt_best" value="1" id="chk_grp_cmt_best">
                <label for="chk_grp_cmt_best">그룹적용</label>
                <input type="checkbox" name="chk_all_cmt_best" value="1" id="chk_all_cmt_best">
                <label for="chk_all_cmt_best">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_cmt_best_min">베스트글 최소 추천수</label></th>
            <td>
				<input type="text" name="bo_cmt_best_min" value="<?php echo $eyoom_board['bo_cmt_best_min'] ?>" id="bo_cmt_best_min" class="frm_input" size="5"> 명 이상일 때, 베스트 댓글이 될 수 있습니다.
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_cmtbest_min" value="1" id="chk_grp_cmtbest_min">
                <label for="chk_grp_cmtbest_min">그룹적용</label>
                <input type="checkbox" name="chk_all_cmtbest_min" value="1" id="chk_all_cmtbest_min">
                <label for="chk_all_cmtbest_min">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_cmt_best_limit">노출 베스트 댓글수</label></th>
            <td>
				<input type="text" name="bo_cmt_best_limit" value="<?php echo $eyoom_board['bo_cmt_best_limit'] ?>" id="bo_cmt_best_limit" class="frm_input" size="5"> 순위까지 베스트 댓글로 표시합니다.
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_cmtbest_limit" value="1" id="chk_grp_cmtbest_limit">
                <label for="chk_grp_cmtbest_limit">그룹적용</label>
                <input type="checkbox" name="chk_all_cmtbest_limit" value="1" id="chk_all_cmtbest_limit">
                <label for="chk_all_cmtbest_limit">전체적용</label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_bo_basic">
    <h2 class="h2_frm color-green">포토이미지 EXIF 정보</h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>포토이미지 EXIF 정보</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="bo_use_exif">포토이미지 EXIF 정보보기 사용</label></th>
            <td>
				<label for="bo_use_exif"><input type="checkbox" name="bo_use_exif" value="1" id="bo_use_exif" <?php echo $checked['bo_use_exif']?'checked':''; ?>> 사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_exif" value="1" id="chk_grp_exif">
                <label for="chk_grp_exif">그룹적용</label>
                <input type="checkbox" name="chk_all_exif" value="1" id="chk_all_exif">
                <label for="chk_all_exif">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_exif_detail">EXIF 정보보기 상세설정</label></th>
            <td>
            <?php
	            foreach($exif_item as $key => $val) {
		    ?>
		    	<div style="margin:5px;"><span style="display:inline-block;width:100px;"><?php echo $val; ?></span> <input type="text" name="bo_exif_detail[<?php echo $key;?>][item]" value="<?php echo $exif_detail[$key]['item']; ?>" id="bo_exif_detail_<?php echo $key;?>_item" class="frm_input" size="20"> <label for="bo_exif_detail_<?php echo $key;?>_use"><input type="checkbox" name="bo_exif_detail[<?php echo $key;?>][use]" value="1" id="bo_exif_detail_<?php echo $key;?>_use" <?php echo $exif_detail[$key]['use']==1?'checked':''; ?>> 보이기</label></div>
		    <?php 
	            }
            ?>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_exif_detail" value="1" id="chk_grp_exif_detail">
                <label for="chk_grp_exif_detail">그룹적용</label>
                <input type="checkbox" name="chk_all_exif_detail" value="1" id="chk_all_exif_detail">
                <label for="chk_all_exif_detail">전체적용</label>
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
