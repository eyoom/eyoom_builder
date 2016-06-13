<?php
$sub_menu = '800100';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(EYOOM_PATH.'/common.php');
auth_check($auth[$sub_menu], "r");

$g5['title'] = '이윰환경설정';
include_once (G5_ADMIN_PATH.'/admin.head.php');

include './eyoom_theme.php';

$frm_submit = '
<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s">
    <a href="'.G5_URL.'">메인으로</a>
</div>
';
?>
<link rel="stylesheet" href="./css/eyoom_admin.css">
<form name="ftheme" action="./config_form_update.php" onsubmit="return ftheme_check(this)" method="post" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="mode" id="mode" value="skin">
<input type="hidden" name="theme" id="theme" value="<?php if($_theme) echo $_theme; else echo $theme;?>">
<input type="hidden" name="ref" id="ref" value="config_form.php">

<?php include './theme_manager.php'; // 테마 메니저 ?>

<?php if($_theme) {?>
<?php if($_eyoom['theme_lang_type'] == 'm') {?>
<section id="anc_scf_info">
    <h2 class="h2_frm">[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] 언어설정 <span class='exp'>테마의 언어를 변경하실 수 있습니다.</span></h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>언어설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="theme">테마 언어설정</label></th>
            <td>
				<select name="language" id="language" required class="required">
					<option value="">:: select language :: </option>
					<option value="kr" <?php if($_eyoom['language']=='kr' || !$_eyoom['language']) echo 'selected';?>>한국어</option>
					<option value="en"<?php if($_eyoom['language']=='en') echo 'selected';?>>English</option>
					<option value="cn"<?php if($_eyoom['language']=='cn') echo 'selected';?>>中文</option>
					<option value="jp"<?php if($_eyoom['language']=='jp') echo 'selected';?>>わご</option>
				</select>
            </td>
        </tr>
		</tbody>
		</table>
	</div>
</section>
<?php }?>
<section id="anc_scf_info">
    <h2 class="h2_frm">[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] 스킨설정 <span class='exp'>그누보드 스킨 선택시 그누보드 환경설정에서 스킨을 선택하거나 스킨파일에서 직접 설정하셔야 합니다.</span></h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>스킨설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="outlogin_skin">아웃로그인 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('outlogin',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				$checked['use_gnu_outlogin1'] = $_eyoom['use_gnu_outlogin'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_outlogin2'] = $_eyoom['use_gnu_outlogin'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="outlogin_skin" id="outlogin_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['outlogin_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_outlogin1"><input type="radio" name="use_gnu_outlogin" id="use_gnu_outlogin1" value="n" '.$checked['use_gnu_outlogin1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_outlogin2"><input type="radio" name="use_gnu_outlogin" id="use_gnu_outlogin2" value="y" '.$checked['use_gnu_outlogin2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 아웃로그인 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
            <th scope="row"><label for="connect_skin">현재접속자 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('connect',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				$checked['use_gnu_connect1'] = $_eyoom['use_gnu_connect'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_connect2'] = $_eyoom['use_gnu_connect'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="connect_skin" id="connect_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['connect_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_connect1"><input type="radio" name="use_gnu_connect" id="use_gnu_connect1" value="n" '.$checked['use_gnu_connect1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_connect2"><input type="radio" name="use_gnu_connect" id="use_gnu_connect2" value="y" '.$checked['use_gnu_connect2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 현재접속자 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
        </tr>
		<tr>
			<th scope="row"><label for="popular_skin">인기검색어 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('popular',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				$checked['use_gnu_popular1'] = $_eyoom['use_gnu_popular'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_popular2'] = $_eyoom['use_gnu_popular'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="popular_skin" id="popular_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['popular_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_popular1"><input type="radio" name="use_gnu_popular" id="use_gnu_popular1" value="n" '.$checked['use_gnu_popular1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_popular2"><input type="radio" name="use_gnu_popular" id="use_gnu_popular2" value="y" '.$checked['use_gnu_popular2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 인기검색어 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
            <th scope="row"><label for="poll_skin">설문조사 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('poll',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				$checked['use_gnu_poll1'] = $_eyoom['use_gnu_poll'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_poll2'] = $_eyoom['use_gnu_poll'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="poll_skin" id="poll_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['poll_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_poll1"><input type="radio" name="use_gnu_poll" id="use_gnu_poll1" value="n" '.$checked['use_gnu_poll1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_poll2"><input type="radio" name="use_gnu_poll" id="use_gnu_poll2" value="y" '.$checked['use_gnu_poll2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 설문조사 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="visit_skin">방문자통계 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('visit',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				$checked['use_gnu_visit1'] = $_eyoom['use_gnu_visit'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_visit2'] = $_eyoom['use_gnu_visit'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="visit_skin" id="visit_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['visit_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_visit1"><input type="radio" name="use_gnu_visit" id="use_gnu_visit1" value="n" '.$checked['use_gnu_visit1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_visit2"><input type="radio" name="use_gnu_visit" id="use_gnu_visit2" value="y" '.$checked['use_gnu_visit2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 방문자통계 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
			<th scope="row"><label for="new_skin">새글 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('new',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				$checked['use_gnu_new1'] = $_eyoom['use_gnu_new'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_new2'] = $_eyoom['use_gnu_new'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="new_skin" id="new_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['new_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_new1"><input type="radio" name="use_gnu_new" id="use_gnu_new1" value="n" '.$checked['use_gnu_new1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_new2"><input type="radio" name="use_gnu_new" id="use_gnu_new2" value="y" '.$checked['use_gnu_new2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 새글 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="member_skin">멤버쉽(회원) 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('member',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				$checked['use_gnu_member1'] = $_eyoom['use_gnu_member'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_member2'] = $_eyoom['use_gnu_member'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="member_skin" id="member_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['member_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_member1"><input type="radio" name="use_gnu_member" id="use_gnu_member1" value="n" '.$checked['use_gnu_member1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_member2"><input type="radio" name="use_gnu_member" id="use_gnu_member2" value="y" '.$checked['use_gnu_member2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 멤버쉽(회원) 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
            <th scope="row"><label for="faq_skin">FAQ 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('faq',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				$checked['use_gnu_faq1'] = $_eyoom['use_gnu_faq'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_faq2'] = $_eyoom['use_gnu_faq'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="faq_skin" id="faq_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['faq_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_faq1"><input type="radio" name="use_gnu_faq" id="use_gnu_faq1" value="n" '.$checked['use_gnu_faq1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_faq2"><input type="radio" name="use_gnu_faq" id="use_gnu_faq2" value="y" '.$checked['use_gnu_faq2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 FAQ 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
        </tr>
		<tr>
			<th scope="row"><label for="qa_skin">1:1문의 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('qa',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				$checked['use_gnu_qa1'] = $_eyoom['use_gnu_qa'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_qa2'] = $_eyoom['use_gnu_qa'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="qa_skin" id="qa_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['qa_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_qa1"><input type="radio" name="use_gnu_qa" id="use_gnu_qa1" value="n" '.$checked['use_gnu_qa1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_qa2"><input type="radio" name="use_gnu_qa" id="use_gnu_qa2" value="y" '.$checked['use_gnu_qa2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 1:1문의 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
            <th scope="row"><label for="search_skin">검색 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('search',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				$checked['use_gnu_search1'] = $_eyoom['use_gnu_search'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_search2'] = $_eyoom['use_gnu_search'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="search_skin" id="search_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['search_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_search1"><input type="radio" name="use_gnu_search" id="use_gnu_search1" value="n" '.$checked['use_gnu_search1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_search2"><input type="radio" name="use_gnu_search" id="use_gnu_search2" value="y" '.$checked['use_gnu_search2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 검색 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
        </tr>
		<tr>
			<th scope="row"><label for="shop_skin">쇼핑몰 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('shop',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				$checked['use_gnu_shop1'] = $_eyoom['use_gnu_shop'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_shop2'] = $_eyoom['use_gnu_shop'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="shop_skin" id="shop_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['shop_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_shop1"><input type="radio" name="use_gnu_shop" id="use_gnu_shop1" value="n" '.$checked['use_gnu_shop1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_shop2"><input type="radio" name="use_gnu_shop" id="use_gnu_shop2" value="y" '.$checked['use_gnu_shop2'].'> 영카트 스킨</label>';
				} else {
					echo "현재 테마에는 쇼핑몰 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>

            </td>
			<th scope="row"><label for="newwin_skin">팝업 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('newwin',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				$checked['use_gnu_newwin1'] = $_eyoom['use_gnu_newwin'] == 'n' ? 'checked="checked"':'';
				$checked['use_gnu_newwin2'] = $_eyoom['use_gnu_newwin'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="newwin_skin" id="newwin_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['newwin_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_newwin1"><input type="radio" name="use_gnu_newwin" id="use_gnu_newwin1" value="n" '.$checked['use_gnu_newwin1'].'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_newwin2"><input type="radio" name="use_gnu_newwin" id="use_gnu_newwin2" value="y" '.$checked['use_gnu_newwin2'].'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 팝업 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="mypage_skin">마이페이지 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('mypage',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				if($arr) {
					echo '<select name="mypage_skin" id="mypage_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['mypage_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
				} else {
					echo "현재 테마에는 마이페이지 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
            <th scope="row"><label for="signature_skin">서명 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('signature',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				if($arr) {
					echo '<select name="signature_skin" id="signature_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['signature_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
				} else {
					echo "현재 테마에는 서명 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="respond_skin">내글반응 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('respond',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				if($arr) {
					echo '<select name="respond_skin" id="respond_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['respond_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
				} else {
					echo "현재 테마에는 내글반응 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
			<th scope="row"><label for="push_skin">푸시알림 스킨</label></th>
            <td>
                <?php
                $arr = $eb->get_skin_dir('push',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				if($arr) {
					echo '<select name="push_skin" id="push_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['push_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
				} else {
					echo "현재 테마에는 푸시알림 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
		</tr>
		<tr>
            <th scope="row"><label for="respond_skin">태그 스킨</label></th>
            <td colspan="3">
                <?php
                $arr = $eb->get_skin_dir('tag',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				if($arr) {
					echo '<select name="tag_skin" id="tag_skin" required class="required">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['tag_skin'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
				} else {
					echo "현재 테마에는 태그 스킨이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
            </td>
		</tr>
        </tbody>
        </table>
    </div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_scf_info">
    <h2 class="h2_frm">[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] 기타설정 </h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>스킨설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
	    <tr>
            <th scope="row"><label for="bootstrap">반응형/비반응형 설정</label></th>
            <td>
                <label for="bootstrap1"><input type="radio" name="bootstrap" id="bootstrap1" value="1" <?php if($_eyoom['bootstrap'] == '1') echo "checked";?>> 반응형 테마</label>
				<label for="bootstrap2"><input type="radio" name="bootstrap" id="bootstrap2" value="0" <?php if($_eyoom['bootstrap'] == '0') echo "checked";?>> 비반응형 테마</label>
				<span class="exp">주의 : 테마의 속성에 맞게 설정해야만 에러를 출력하지 않습니다.</span>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="use_eyoom_menu">커뮤니티 메뉴</label></th>
            <td>
                <label for="use_eyoom_menu1"><input type="radio" name="use_eyoom_menu" id="use_eyoom_menu1" value="y" <?php if($_eyoom['use_eyoom_menu'] == 'y') echo "checked";?>> 이윰메뉴 사용</label>
				<label for="use_eyoom_menu2"><input type="radio" name="use_eyoom_menu" id="use_eyoom_menu2" value="n" <?php if($_eyoom['use_eyoom_menu'] == 'n') echo "checked";?>> 그누메뉴 사용</label>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="use_eyoom_shopmenu">쇼핑몰 메뉴</label></th>
            <td>
                <label for="use_eyoom_shopmenu1"><input type="radio" name="use_eyoom_shopmenu" id="use_eyoom_shopmenu1" value="y" <?php if($_eyoom['use_eyoom_shopmenu'] == 'y') echo "checked";?>> 이윰메뉴 사용</label>
				<label for="use_eyoom_shopmenu2"><input type="radio" name="use_eyoom_shopmenu" id="use_eyoom_shopmenu2" value="n" <?php if($_eyoom['use_eyoom_shopmenu'] == 'n') echo "checked";?>> 그누메뉴 사용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="use_shop_mobile">쇼핑몰 모바일스킨 사용설정</label></th>
            <td>
                <label for="use_shop_mobile1"><input type="radio" name="use_shop_mobile" id="use_shop_mobile1" value="y" <?php if($_eyoom['use_shop_mobile'] == 'y') echo "checked";?>> 모바일 전용스킨 사용</label>
				<label for="use_shop_mobile2"><input type="radio" name="use_shop_mobile" id="use_shop_mobile2" value="n" <?php if($_eyoom['use_shop_mobile'] == 'n' || !$_eyoom['use_shop_mobile']) echo "checked";?>> 반응형 스킨 사용</label>
				<span class="exp">주의 : 반응형 스킨을 사용할 경우, 모바일 결제 기능이 지원되지 않습니다. [모바일 전용 스킨은 아직 준비중입니다.]</span>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="level_icon_gnu">그누레벨 아이콘 </label></th>
            <td>
                <?php
				if($_eyoom['use_level_icon_gnu'] == 'y') $checked['use_level_icon_gnu'] = 'checked="checked"';
                $arr = $eb->get_skin_dir('gnuboard',EYOOM_THEME_PATH.'/'.$_theme.'/image/level_icon');
				if($arr) {
					echo '<select name="level_icon_gnu" id="level_icon_gnu">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['level_icon_gnu'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_level_icon_gnu"><input type="checkbox" name="use_level_icon_gnu" value="y" id="use_level_icon_gnu" '.$checked['use_level_icon_gnu'].'> 사용</label>';
				} else {
					echo "현재 테마에는 그누레벨 아이콘이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
			</td>
		</tr>
		<tr>
            <th scope="row"><label for="level_icon_eyoom">이윰레벨 아이콘 </label></th>
            <td>
                <?php
				if($_eyoom['use_level_icon_eyoom'] == 'y') $checked['use_level_icon_eyoom'] = 'checked="checked"';
                $arr = $eb->get_skin_dir('eyoom',EYOOM_THEME_PATH.'/'.$_theme.'/image/level_icon');
				if($arr) {
					echo '<select name="level_icon_eyoom" id="level_icon_eyoom">';
					for ($i=0; $i<count($arr); $i++) {
						if ($i == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$i]."\"".get_selected($_eyoom['level_icon_eyoom'], $arr[$i]).">".$arr[$i]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_level_icon_eyoom"><input type="checkbox" name="use_level_icon_eyoom" value="y" id="use_level_icon_gnu" '.$checked['use_level_icon_eyoom'].'> 사용</label>';
				} else {
					echo "현재 테마에는 이윰레벨 아이콘이 존재하지 않습니다.";
				}
				unset($arr);
                ?>
			</td>
		</tr>
		<tr>
            <th scope="row"><label for="use_sideview">회원 사이드뷰</label></th>
            <td>
                <label for="use_sideview1"><input type="radio" name="use_sideview" id="use_sideview1" value="y" <?php if($_eyoom['use_sideview'] == 'y') echo "checked";?>> 사용</label>
				<label for="use_sideview2"><input type="radio" name="use_sideview" id="use_sideview2" value="n" <?php if($_eyoom['use_sideview'] == 'n') echo "checked";?>> 사용하지 않음</label>
            </td>
        </tr>
		<tr>
            <th scope="row"><label for="push_reaction">푸시알람 </label></th>
            <td>
				<label for="push_reaction1"><input type="radio" name="push_reaction" id="push_reaction1" value="y" <?php if($_eyoom['push_reaction'] == 'y') echo "checked";?>> 사용</label>
				<label for="push_reaction2"><input type="radio" name="push_reaction" id="push_reaction2" value="n" <?php if($_eyoom['push_reaction'] == 'n') echo "checked";?>> 사용하지 않음</label>
			</td>
		</tr>
		<tr>
            <th scope="row"><label for="push_time">푸시체크 반복시간 </label></th>
            <td>
				<input type="text" name="push_time" value="<?php echo $_eyoom['push_time'];?>" id="push_time" style="width:80px;" class="frm_input"> <span class="exp">1000 -> 1초</span>
			</td>
		</tr>
		<tr>
            <th scope="row"><label for="photo_width">프로필 사진 사이즈 </label></th>
            <td>
				가로 : <input type="text" name="photo_width" value="<?php echo $_eyoom['photo_width'];?>" id="photo_width" class="frm_input" style="width:80px;">px, 세로 : <input type="text" name="photo_height" value="<?php echo $_eyoom['photo_height'];?>" id="photo_height" class="frm_input" style="width:80px;">px  <span class="exp">자동으로 이미지 사이즈를 지정한 사이즈로 썸네일화 합니다.</span>
			</td>
		</tr>
		<tr>
            <th scope="row"><label for="cover_width">마이홈 커버사진 가로사이즈 </label></th>
            <td>
				<input type="text" name="cover_width" value="<?php echo $_eyoom['cover_width'];?>" id="cover_width" class="frm_input" style="width:80px;">px
			</td>
		</tr>
		<tr>
            <th scope="row"><label for="use_main_side_layout">사이드 레이아웃[메인] </label></th>
            <td>
                <label for="use_main_side_layout1"><input type="radio" name="use_main_side_layout" id="use_main_side_layout1" value="y" <?php if($_eyoom['use_main_side_layout'] == 'y') echo "checked";?>> 사용</label>
				<label for="use_main_side_layout2"><input type="radio" name="use_main_side_layout" id="use_main_side_layout2" value="n" <?php if($_eyoom['use_main_side_layout'] == 'n') echo "checked";?>> 사용하지 않음</label>
            </td>
		</tr>
		<tr>
            <th scope="row"><label for="use_sub_side_layout">사이드 레이아웃[서브] </label></th>
            <td>
                <label for="use_sub_side_layout1"><input type="radio" name="use_sub_side_layout" id="use_sub_side_layout1" value="y" <?php if($_eyoom['use_sub_side_layout'] == 'y') echo "checked";?>> 사용</label>
				<label for="use_sub_side_layout2"><input type="radio" name="use_sub_side_layout" id="use_sub_side_layout2" value="n" <?php if($_eyoom['use_sub_side_layout'] == 'n') echo "checked";?>> 사용하지 않음</label>
            </td>
		</tr>
		<tr>
            <th scope="row"><label for="pos_side_layout">사이드 레이아웃 위치 </label></th>
            <td>
                <label for="pos_side_layout1"><input type="radio" name="pos_side_layout" id="pos_side_layout1" value="left" <?php if($_eyoom['pos_side_layout'] == 'left') echo "checked";?>> 왼쪽</label>
				<label for="pos_side_layout2"><input type="radio" name="pos_side_layout" id="pos_side_layout2" value="right" <?php if($_eyoom['pos_side_layout'] == 'right') echo "checked";?>> 오른쪽</label>
            </td>
		</tr>
		</table>
		<input type="hidden" name="push_sound" id="push_sound" value="push_sound_01.mp3">
	</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_scf_info">
    <h2 class="h2_frm">[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] 관리 패널 설정 </h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>관리 패널</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
		<tr>
            <th scope="row"><label for="use_board_control">관리자용 게시판 패널 </label></th>
            <td>
                <label for="use_board_control1"><input type="radio" name="use_board_control" id="use_board_control1" value="y" <?php if($_eyoom['use_board_control'] == 'y') echo "checked";?>> 사용</label>
				<label for="use_board_control2"><input type="radio" name="use_board_control" id="use_board_control2" value="n" <?php if($_eyoom['use_board_control'] == 'n') echo "checked";?>> 사용하지 않음</label>
            </td>
		</tr>
		<tr>
            <th scope="row"><label for="board_control_position">관리자용 게시판 패널 위치 </label></th>
            <td>
                <label for="board_control_position1"><input type="radio" name="board_control_position" id="board_control_position1" value="left" <?php if($_eyoom['board_control_position'] == 'left') echo "checked";?>> 왼쪽</label>
				<label for="board_control_position2"><input type="radio" name="board_control_position" id="board_control_position2" value="right" <?php if($_eyoom['board_control_position'] == 'right') echo "checked";?>> 오른쪽</label>
            </td>
		</tr>
		<tr>
            <th scope="row"><label for="use_theme_info">관리자용 테마정보 패널 </label></th>
            <td>
                <label for="use_theme_info1"><input type="radio" name="use_theme_info" id="use_theme_info1" value="y" <?php if($_eyoom['use_theme_info'] == 'y') echo "checked";?>> 사용</label>
				<label for="use_theme_info2"><input type="radio" name="use_theme_info" id="use_theme_info2" value="n" <?php if($_eyoom['use_theme_info'] == 'n') echo "checked";?>> 사용하지 않음</label>
            </td>
		</tr>
		<tr>
            <th scope="row"><label for="theme_info_position">관리자용 테마정보 패널 위치 </label></th>
            <td>
                <label for="theme_info_position1"><input type="radio" name="theme_info_position" id="theme_info_position1" value="top" <?php if($_eyoom['theme_info_position'] == 'top') echo "checked";?>> 상단</label>
				<label for="theme_info_position2"><input type="radio" name="theme_info_position" id="theme_info_position2" value="bottom" <?php if($_eyoom['theme_info_position'] == 'bottom') echo "checked";?>> 하단</label>
            </td>
		</tr>
		</table>
		<input type="hidden" name="push_sound" id="push_sound" value="push_sound_01.mp3">
	</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_scf_info">
    <h2 class="h2_frm">[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] 태그기능 설정</h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>태그기능</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
		<tr>
            <th scope="row"><label for="use_tag">태그기능 사용</label></th>
            <td>
                <label for="use_tag_yes"><input type="radio" name="use_tag" id="use_tag_yes" value="y" <?php if($_eyoom['use_tag'] == 'y') echo "checked";?>> 사용</label>
				<label for="use_tag_no"><input type="radio" name="use_tag" id="use_tag_no" value="n" <?php if($_eyoom['use_tag'] == 'n') echo "checked";?>> 사용하지 않음</label>
            </td>
		</tr>
		<tr>
            <th scope="row"><label for="tag_dpmenu_count">태그메뉴 출력 태그수</label></th>
            <td>
                <input type="text" name="tag_dpmenu_count" value="<?php echo $_eyoom['tag_dpmenu_count'];?>" id="tag_dpmenu_count" class="frm_input" style="width:80px;"> 건
            </td>
		</tr>
		<tr>
            <th scope="row"><label for="tag_dpmenu_sort">태그메뉴 출력방식</label></th>
            <td>
                <label for="tag_dpmenu_sort_regdt"><input type="radio" name="tag_dpmenu_sort" id="tag_dpmenu_sort_regdt" value="regdt" <?php if($_eyoom['tag_dpmenu_sort'] == 'regdt') echo "checked";?>> 최근 등록순</label>
				<label for="tag_dpmenu_sort_score"><input type="radio" name="tag_dpmenu_sort" id="tag_dpmenu_sort_score" value="score" <?php if($_eyoom['tag_dpmenu_sort'] == 'score') echo "checked";?>> 노출점수순</label>
				<label for="tag_dpmenu_sort_regcnt"><input type="radio" name="tag_dpmenu_sort" id="tag_dpmenu_sort_regcnt" value="regcnt" <?php if($_eyoom['tag_dpmenu_sort'] == 'regcnt') echo "checked";?>> 등록건수순</label>
				<label for="tag_dpmenu_sort_scnt"><input type="radio" name="tag_dpmenu_sort" id="tag_dpmenu_sort_scnt" value="scnt" <?php if($_eyoom['tag_dpmenu_sort'] == 'scnt') echo "checked";?>> 클릭검색순</label>
				<label for="tag_dpmenu_sort_random"><input type="radio" name="tag_dpmenu_sort" id="tag_dpmenu_sort_random" value="random" <?php if($_eyoom['tag_dpmenu_sort'] == 'random') echo "checked";?>> 랜덤출력</label>
            </td>
		</tr>
		<tr>
            <th scope="row"><label for="tag_recommend_count">추천태그 출력 태그수</label></th>
            <td>
                <input type="text" name="tag_recommend_count" value="<?php echo $_eyoom['tag_recommend_count'];?>" id="tag_recommend_count" class="frm_input" style="width:80px;"> 건
            </td>
		</tr>
		</table>
	</div>
</section>

<?php echo $frm_submit; ?>

<?php } ?>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
