<?php
	$sub_key = substr($sub_menu,3,3);
	if(!$sub_key) exit;

	$sql="select * from {$g5['eyoom_theme']} where 1 ";
	$res=sql_query($sql,false);
	for($i=0;$row=sql_fetch_array($res);$i++) {
		$tminfo[$row['tm_name']] = $row;
	}
	if(defined('_EYOOM_VESION_')) {
		$builder_score = $eb->version_score(str_replace("EyoomBuilder_", "", _EYOOM_VESION_));

		if (!ini_get("allow_url_fopen")) ini_set("allow_url_fopen", 1);
		if (ini_get("allow_url_fopen") == 1) {
			// 이윰넷에서 버전 가져오기
			$url = EYOOM_SITE . '/bbs/rss.php?bo_table=eyoom';
			$xml = simplexml_load_file($url);
			$builder = $xml->channel->item;
			$builder = $builder[0];

			preg_match("/\d+\.\d+\.\d+/", $builder->title, $match);
			$eyoom_score = $eb->version_score($match[0]);

			if($builder_score < $eyoom_score) {
				$new_version = "<a href='{$builder->link}' target='_blank' class='eb_new'>{$builder->title} 바로가기</a>";
			}

			if (defined('G5_YOUNGCART_VER')) {
				$url = GNU_URL . '/rss/yc5_pds';
				$xml = simplexml_load_file($url);
				$sir = $xml->channel->item;
				$sir = $sir[0];

				preg_match("/\d+\.\d+\.\d+/", $sir->title, $match);
				$sir_score = $eb->version_score($match[0]);
				$gnu_score = $eb->version_score(G5_YOUNGCART_VER);
				if($gnu_score < $sir_score) {
					$gnu_version = "<a href='{$sir->link}' target='_blank' class='gnu_new'>{$sir->title} 바로가기</a>";
				}
			} else {
				$url = GNU_URL . '/rss/g5_pds';
				$xml = simplexml_load_file($url);
				$sir = $xml->channel->item;
				$sir = $sir[0];

				preg_match("/\d+\.\d+\.\d+/", $sir->title, $match);
				$sir_score = $eb->version_score($match[0]);
				$gnu_score = $eb->version_score(G5_GNUBOARD_VER);
				if($gnu_score < $sir_score) {
					$gnu_version = "<a href='{$sir->link}' target='_blank' class='gnu_new'>{$sir->title} 바로가기</a>";
				}
			}
		}
?>
<style>
	.eb_new, .gnu_new {
		display: inline-block;
		margin:2px 10px;
		padding:5px 10px;
		color:#fff;
		font-weight: normal;
		font-size: 12px;
	}
	.eb_new {
		background: #d96c41;
	}
	.gnu_new {
		background: #b041d9;
	}
</style>
<section>
	<h2><strong style="color:#f30;">설치 버전</strong> : <?php echo _EYOOM_VESION_;?> <?php echo $new_version;?> <?php echo $gnu_version;?></h2>
    <div class="tbl_frm01 tbl_wrap">
        <table style="background:#fafafa;border:1px solid #eaeaea;">
        <caption>테마설정</caption>
        <tbody>
        <tr>
            <td>
				<div class="themes">
					<div class="refresh btn_latest"><a href="javascript:;" class="refresh_latest" onclick="return refresh_db_record('latest');">최신글정리</a></div>
					<div class="refresh btn_respond"><a href="javascript:;" class="refresh_respond" onclick="return refresh_db_record('respond');">내글반응정리</a></div>
					<div class="refresh btn_dbupdate"><a href="javascript:;" class="refresh_dbupdate" onclick="return refresh_db_record('dbupdate');">최신 DB 및 설정적용</a></div>
					<div class="refresh btn_theme"><a href="javascript:;" class="use_eyoom_theme" onclick="return use_eyoom_theme();">이윰테마사용</a></div>
					<div class="refresh btn_countdown"><a href="./countdown_form.php" class="countdown" onclick="return false;">공사중 설정</a></div>
					<div class="refresh btn_pointzip"><a href="./pointzip_form.php" class="pointzip" onclick="return false;">그누포인트 압축하기</a></div>
				</div>
			</td>
		</tr>
		</table>
	</div>
</section>
<?php
	}
?>
<section id="anc_scf_info">
    <h2 class="h2_frm">테마 메니저<span class='exp'>각 테마별로 다양한 설정을 하실 수 있습니다.</span></h2>

    <div class="tbl_frm01 tbl_wrap">
		<input type="hidden" name="shop_theme" id="shop_theme" value="<?php echo $shop_theme;?>">
		<input type="hidden" name="theme_target" id="theme_target" value="">
        <table style="background:#f5f5f5;border:1px solid #eaeaea;">
        <caption>테마설정</caption>
        <tbody>
        <tr>
            <td>
			<div id="theme_select">
			<?php
			$arr = get_skin_dir('theme',EYOOM_PATH);
			for ($i=0; $i<count($arr); $i++) {
				if($arr[$i] == 'countdown') continue;
				$config_file = $arr[$i] == 'basic' ? eyoom_config:G5_DATA_PATH.'/eyoom.'.$arr[$i].'.config.php';
				if(file_exists($config_file)) {
					include($config_file);
					$exist_theme[$i] = $arr[$i];
			?>
			<div class="themes">
				<div class="theme_name"><?php echo $arr[$i];?> <span style='font-weight:normal;'><?php if($tminfo[$arr[$i]]['tm_alias']) echo "(".$tminfo[$arr[$i]]['tm_alias'].")";?></span></div>
				<div><a href="javascript:;" onclick="set_theme('<?php echo $arr[$i];?>','c');" <?php if($theme == $arr[$i]) echo "class='theme_active'";?>>커뮤니티홈</a></div>
				<?php if(defined('G5_USE_SHOP')) {?><div style="margin-right:15px;"><a href="javascript:;" onclick="set_theme('<?php echo $arr[$i];?>','s');" <?php if($shop_theme == $arr[$i]) echo "class='theme_active'";?>>쇼핑몰홈</a></div><?php }?>
				<div><a href="./config_form.php?thema=<?php echo $arr[$i];?>" <?php if($_theme == $arr[$i] && $sub_key=='100') echo "class='active'";?>>기본설정</a></div>
				<div><a href="./board_list.php?thema=<?php echo $arr[$i];?>" <?php if($_theme == $arr[$i] && $sub_key=='200') echo "class='active'";?>>게시판설정</a></div>
				<div><a href="./menu_list.php?thema=<?php echo $arr[$i];?>" <?php if($_theme == $arr[$i] && $sub_key=='300') echo "class='active'";?>>커뮤니티메뉴</a></div>
				<?php if(defined('G5_USE_SHOP')) {?><div><a href="./shopmenu_list.php?thema=<?php echo $arr[$i];?>" <?php if($_theme == $arr[$i] && $sub_key=='400') echo "class='active'";?>>쇼핑몰메뉴</a></div><?php }?>
				<div><a href="./banner_list.php?thema=<?php echo $arr[$i];?>" <?php if($_theme == $arr[$i] && $sub_key=='500') echo "class='active'";?>>배너/광고</a></div>
				<div><a href="./tag_list.php?thema=<?php echo $arr[$i];?>" <?php if($_theme == $arr[$i] && $sub_key=='600') echo "class='active'";?>>태그</a></div>
				<div class="btn_clone"><a href="./theme_clone.php?thema=<?php echo $arr[$i];?>" class="clone_theme" onclick="return false;">복사</a></div>
				<div class="btn_delete"><a href="./theme_delete.php?thema=<?php echo $arr[$i];?>" class="delete_theme" onclick="return false;">삭제</a></div>
				<div class="btn_chname"><a href="./theme_alias.php?thema=<?php echo $arr[$i];?>" class="alias_theme" onclick="return false;">별칭설정</a></div>
				<div class="btn_community"><a href="<?php echo G5_URL;?>/?theme=<?php if($tminfo[$arr[$i]]['tm_alias']) echo $tminfo[$arr[$i]]['tm_alias']; else echo $arr[$i];?>" target="_blank">커뮤니티</a></div>
				<?php if(defined('G5_USE_SHOP')) {?><div class="btn_shop"><a href="<?php echo G5_URL;?>/<?php echo G5_SHOP_DIR;?>/?theme=<?php if($tminfo[$arr[$i]]['tm_alias']) echo $tminfo[$arr[$i]]['tm_alias']; else echo $arr[$i];?>" target="_blank">쇼핑몰</a></div><?php }?>
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
	$(".install_theme, .clone_theme, .delete_theme, .alias_theme, .countdown, .pointzip").click(function(){
		var url = $(this).attr('href');
		win_theme(url);
	});
});
function set_theme(theme,type) {
	$("#mode").val('theme');
	switch(type) {
		case 'c':
			if(confirm("정말로 [" + theme + "]테마를 커뮤니티홈으로 사용하시겠습니까?")) {
				$("#theme").val(theme);
				$("#theme_target").val('community');
				document.ftheme.submit();
			} else return;
			break;
		case 's':
			if(confirm("정말로 [" + theme + "]테마를 쇼핑몰홈으로 사용하시겠습니까?")) {
				$("#shop_theme").val(theme);
				$("#theme_target").val('shop');
				document.ftheme.submit();
			} else return;
	}
}
function refresh_db_record(target) {
	var msg='';
	switch(target) {
		case 'latest': msg = "이윰 최신글을 정리합니다.\n게시물의 수에 따라 시간이 소요될 수 있습니다.\n\n계속 진행하시겠습니까?"; break;
		case 'respond': msg = "내글반응의 노출숫자를 실 데이타와 일치시킵니다.\n\n계속 진행하시겠습니까?";break;
		case 'dbupdate': msg = "DB속성 및 이윰설정을 최근 설정으로 최적화합니다.\n\n계속 진행하시겠습니까?";break;
	}
	if(confirm(msg)) {
		var url = "./eyoom_db_refresh.php";
		$.ajax({
			url: url,
			type: "POST",
			data: {'tg': target },
			dataType: "json",
			async: false,
			cache: false,
			success: function(data, textStatus) {
				if(data.result == 'yes'){
					alert('정상적으로 처리하였습니다.');
				}
			}
		});
	} else return false;
}
function use_eyoom_theme() {
	if(confirm('그누보드5의 테마 대신 이윰빌더의 테마를 메인테마로 사용합니다.\n\n계속 진행하시겠습니까?')) {
		$.ajax({
			url: "./use_eyoom_theme.php",
			type: "POST",
			data: {'theme': 'eyoom'},
			dataType: "json",
			async: false,
			cache: false,
			success: function(data, textStatus) {
				if(data.result == 'yes'){
					alert('정상적으로 처리하였습니다.');
				}
			}
		});
	}
}
</script>