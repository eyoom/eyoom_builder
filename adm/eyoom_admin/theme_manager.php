<?php
	$sub_key = substr($sub_menu,3,3);
	if(!$sub_key) exit;

	$sql="select * from {$g5['eyoom_theme']} where 1 ";
	$res=sql_query($sql,false);
	for($i=0;$row=sql_fetch_array($res);$i++) {
		$tminfo[$row['tm_name']] = $row;
	}
?>
<section id="anc_scf_info">
    <h2 class="h2_frm">테마 메니저<span class='exp'>각 테마별로 다양한 설정을 하실 수 있습니다.</span></h2>

    <div class="tbl_frm01 tbl_wrap">
        <table style="background:#f5f5f5;border:1px solid #eaeaea;">
        <caption>테마설정</caption>
        <tbody>
        <tr>
            <td>
			<div id="theme_select">
			<?php
			$arr = get_skin_dir('theme',EYOOM_PATH);
			for ($i=0; $i<count($arr); $i++) {
				$config_file = $arr[$i] == 'basic' ? eyoom_config:G5_DATA_PATH.'/eyoom.'.$arr[$i].'.config.php';
				if(file_exists($config_file)) {
					include($config_file);
			?>
			<div class="themes">
				<div class="theme_name"><?php echo $arr[$i];?> <span style='font-weight:normal;'><?php if($tminfo[$arr[$i]]['tm_alias']) echo "(".$tminfo[$arr[$i]]['tm_alias'].")";?></span></div>
				<div><a href="javascript:;" onclick="set_theme('<?php echo $arr[$i];?>');" <?php if($theme == $arr[$i]) echo "class='active'";?>>홈테마로사용</a></div>
				<div><a href="./config_form.php?thema=<?php echo $arr[$i];?>" <?php if($_theme == $arr[$i] && $sub_key=='100') echo "class='active'";?>>기본설정</a></div>
				<div><a href="./board_list.php?thema=<?php echo $arr[$i];?>" <?php if($_theme == $arr[$i] && $sub_key=='200') echo "class='active'";?>>게시판설정</a></div>
				<div><a href="./menu_list.php?thema=<?php echo $arr[$i];?>" <?php if($_theme == $arr[$i] && $sub_key=='300') echo "class='active'";?>>이윰메뉴설정</a></div>
				<div><a href="./banner_list.php?thema=<?php echo $arr[$i];?>" <?php if($_theme == $arr[$i] && $sub_key=='400') echo "class='active'";?>>배너/광고</a></div>
				<div class="btn_clone"><a href="./theme_clone.php?thema=<?php echo $arr[$i];?>" class="clone_theme" onclick="return false;">복사</a></div>
				<div class="btn_delete"><a href="./theme_delete.php?thema=<?php echo $arr[$i];?>" class="delete_theme" onclick="return false;">삭제</a></div>
				<div class="btn_chname"><a href="./theme_alias.php?thema=<?php echo $arr[$i];?>" class="alias_theme" onclick="return false;">별칭설정</a></div>
				<div class="btn_community"><a href="<?php echo G5_URL;?>/?theme=<?php if($tminfo[$arr[$i]]['tm_alias']) echo $tminfo[$arr[$i]]['tm_alias']; else echo $arr[$i];?>" target="_blank">커뮤니티</a></div>
				<div class="btn_shop"><a href="<?php echo G5_URL;?>/<?php echo G5_SHOP_DIR;?>/?theme=<?php if($tminfo[$arr[$i]]['tm_alias']) echo $tminfo[$arr[$i]]['tm_alias']; else echo $arr[$i];?>" target="_blank">쇼핑몰</a></div>
			</div>
			<?php
					unset($eyoom);
				} else {
			?>
			<div class="themes">
				<div class="theme_name"><?php echo $arr[$i];?></div>
				<div class="theme_setup"><a href="./theme_form.php?thema=<?php echo $arr[$i];?>" class="install_theme" onclick="return false;">테마설치하기</a></div>
				<div class="btn_delete"><a href="./theme_delete.php?thema=<?php echo $arr[$i];?>" class="delete_theme" onclick="return false;">삭제</a></div>
			</div>
			<?php
				}
			}
			unset($arr);
			?>
			</div>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>

<script>
$(function(){
	var win_theme = function(href) {
		var new_win = window.open(href, 'win_theme', 'left=100,top=100,width=620,height=400,scrollbars=1');
		new_win.focus();
	}
	$(".install_theme, .clone_theme, .delete_theme, .alias_theme").click(function(){
		var url = $(this).attr('href');
		win_theme(url);
	});
});
function set_theme(theme) {
	$("#mode").val('theme');
	$("#theme").val(theme);
	document.ftheme.submit();
}
</script>