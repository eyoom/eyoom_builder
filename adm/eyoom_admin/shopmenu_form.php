<?php
$sub_menu = '800400';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(EYOOM_PATH.'/common.php');
auth_check($auth[$sub_menu], "r");

$g5['title'] = '이윰메뉴';

include_once(G5_PATH.'/head.sub.php');
$theme = $_GET['thema'];
$me_code = $_GET['id'];
$depth = strlen($me_code)/3;

if($theme && $me_code) {
	$sql = "select * from {$g5['eyoom_menu']} where me_theme='{$theme}' and me_code='{$me_code}' and me_shop = '1'";
	$meinfo = sql_fetch($sql, false);
	if($meinfo['me_side'] == 'y') $checked['me_side1'] = 'checked'; else $checked['me_side2'] = 'checked';
	if($meinfo['me_use'] == 'y') $checked['me_use1'] = 'checked'; else $checked['me_use2'] = 'checked';
	if($meinfo['me_use_nav'] == 'y' || !$meinfo['me_use_nav']) $checked['me_use_nav1'] = 'checked'; else $checked['me_use_nav2'] = 'checked';
	if(!$meinfo['me_path']) {
		$meinfo['me_path'] = $thema->get_path($meinfo['me_code']);
	}
	$g5_url = parse_url(G5_URL);
	$meinfo['me_link'] = str_replace($g5_url['path'],'',$meinfo['me_link']);
	if(!preg_match('/(http|https):/i',$meinfo['me_link'])) {
		$meinfo['me_link'] = G5_URL.$meinfo['me_link'];
	}
}

$frm_submit = '
<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="적용하기" class="btn_submit" accesskey="s">
</div>
';

?>

<div id="wrapper" style="min-width:100%;">

    <div id="container" style="min-width:100%;">
		<link rel="stylesheet" href="./css/eyoom_admin.css">
		<form name="fmenu" action="./menu_form_update.php" onsubmit="return fmenu_check(this)" method="post">
		<input type="hidden" name="mode" id="mode" value="update">
		<input type="hidden" name="theme" id="theme" value="<?php echo $theme;?>">
		<input type="hidden" name="me_code" id="me_code" value="<?php echo $me_code;?>">
		<input type="hidden" name="me_shop" id="me_shop" value="1">
		<section id="anc_scf_info">
		<div class="tbl_frm01 tbl_wrap">
		<?php
		if($me_code === '1' || !$me_code) {
		?>
			<table>
			<colgroup>
				<col class="grid_3">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<th scope="row"><label for="theme">메뉴위치</label></th>
				<td>쇼핑몰 메뉴 루트</td>
			</tr>
			</table>

		<?php 
		} else {
		?>
			<table>
			<colgroup>
				<col class="grid_3">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<th scope="row"><label for="me_code">메뉴코드</label></th>
				<td><b><?php echo $meinfo['me_code'];?></b></td>
			</tr>
			<tr>
				<th scope="row"><label for="me_order">출력순서</label></th>
				<td>
					<input type="text" name="me_order" id="me_order" value="<?php echo $meinfo['me_order'];?>" required class="required frm_input" size="5">
					<input type="hidden" name="me_order_prev" id="me_order_prev" value="<?php echo $meinfo['me_order'];?>">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="me_icon">폰트어썸 아이콘</label></th>
				<td>
					<div class="themes">
						<input type="text" name="me_icon" id="me_icon" value="<?php echo $meinfo['me_icon'];?>" class="frm_input" size="24">
						<a href="http://eyoom.net/page/?pid=pc-eyoomcss-3" target="_blank" style="display:inline-block;width:97px;text-align:center;color:#fff;background:#555;border:1px solid #333;">Font Awesome</a> <span class="exp">예) fa-user</span>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="me_name">메뉴명</label></th>
				<td>
					<?php if($meinfo['me_icon']) echo '<i class="fa '.$meinfo['me_icon'].'"></i>';?> <input type="text" name="me_name" id="me_name" value="<?php echo $meinfo['me_name'];?>" required class="required frm_input" size="43">
					<input type="hidden" name="me_name_prev" id="me_name_prev" value="<?php echo $meinfo['me_name'];?>">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="me_path">메뉴위치</label></th>
				<td><input type="text" name="me_path" id="me_path" value="<?php echo $meinfo['me_path'];?>" required class="required frm_input" size="82"></td>
			</tr>
			<tr>
				<th scope="row"><label for="me_link">메뉴링크</label></th>
				<td>
					<input type="text" name="me_link" id="me_link" value="<?php if($meinfo['me_link']) echo $meinfo['me_link'];?>" class="frm_input" size="60">
					<select name="me_target" id="me_target" class="frm_input">
					  <option value="">타겟을 선택하세요.</option>
					  <option value="blank" <?php if($meinfo['me_target']=='blank') echo "selected";?>>새창</option>
					  <option value="self" <?php if($meinfo['me_target']=='self') echo "selected";?>>현재창</option>
					</select>
					<span class="exp">예) <?php echo G5_BBS_URL;?>/board.php?bo_table=free</span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="me_permit_level">메뉴보이기 레벨설정</label></th>
				<td>
					<select name="me_permit_level" id="me_permit_level">
						<option value="">선택</option>
						<?php 
						  for($i=1;$i<=10;$i++) {
						?>
						<option value="<?php echo $i;?>" <?php if($meinfo['me_permit_level'] == $i) echo "selected";?>><?php echo $i;?></option>
						<?php
							}
						?>
					</select> 레벨
					<span class="exp">선택한 그누레벨 이상의 회원에게 메뉴가 보이게 됩니다.</span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="me_side">사이드 레이아웃</label></th>
				<td>
					<label for="me_side1"><input type="radio" name="me_side" id="me_side1" value="y" <?php echo $checked['me_side1'];?>> 보이기</label>
					<label for="me_side2"><input type="radio" name="me_side" id="me_side2" value="n" <?php echo $checked['me_side2'];?>> 감추기</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="me_use">출력여부</label></th>
				<td>
					<label for="me_use1"><input type="radio" name="me_use" id="me_use1" value="y" <?php echo $checked['me_use1'];?>> 보이기</label>
					<label for="me_use2"><input type="radio" name="me_use" id="me_use2" value="n" <?php echo $checked['me_use2'];?>> 감추기</label>
				</td>
			</tr>
			<?php if($depth == 1) {?>
			<tr>
				<th scope="row"><label for="me_use_nav">상단메뉴사용</label></th>
				<td>
					<label for="me_use_nav1"><input type="radio" name="me_use_nav" id="me_use_nav1" value="y" <?php echo $checked['me_use_nav1'];?>> 사용</label>
					<label for="me_use_nav2"><input type="radio" name="me_use_nav" id="me_use_nav2" value="n" <?php echo $checked['me_use_nav2'];?>> 사용하지 않음</label> <span class="exp">상단 네이게이션 메뉴로 사용하지 않지만 하단이나 다른 곳의 메뉴로 사용할 경우 유용합니다.</span>
				</td>
			</tr>
			<?php }?>
			<tr>
				<th scope="row"><label for="me_use">메뉴삭제</label></th>
				<td>
					<div class="themes">
						<div style="width:62px;float:left;"><a href="javascript:;" onclick="delete_menu('<?php echo $meinfo['me_id'];?>','<?php echo $meinfo['me_theme'];?>');" class='active'>삭제하기</a></div>
						<div style="padding-top:4px;"><span class="exp">삭제시, 서브메뉴까지 함께 삭제됩니다.</span></div>
					</div>
					
				</td>
			</tr>

			</table>
			<br>
		<?php 
			echo $frm_submit;
		}
		if($depth < 5) {
		?>
			<h2 class="h2_frm" style="margin-left:-20px;margin-top:30px;"><b style='color:#f30;'>서브 메뉴</b> <span class='exp' style='color:#777;'>선택한 메뉴의 서브메뉴를 생성합니다.</span></h2>
			<table>
			<colgroup>
				<col class="grid_3">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<th scope="row"><label for="subme_type">대상 선택</label></th>
				<td>
					<select name="subme_type" id="subme_type" onchange="view_select_list(this.value);return false;">
						<option value="userpage">직접입력</option>
						<option value="group">게시판그룹</option>
						<option value="board">게시판</option>
						<option value="page">내용페이지</option>
					</select>
					<span id="selbox"></span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="subme_icon">폰트어썸 아이콘</label></th>
				<td>
					<div class="themes">
						<input type="text" name="subme_icon" id="subme_icon" value="" class="frm_input" size="24">
						<a href="http://eyoom.net/page/?pid=pc-eyoomcss-3" target="_blank" style="display:inline-block;width:97px;text-align:center;color:#fff;background:#555;border:1px solid #333;">Font Awesome</a> <span class="exp">예) fa-user</span>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="subme_name">서브 메뉴명</label></th>
				<td>
					<input type="text" name="subme_name" id="subme_name" value="" class="frm_input" size="43">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="subme_link">메뉴링크</label></th>
				<td>
					<input type="text" name="subme_link" id="subme_link" value="" class="frm_input" size="60">
					<select name="subme_target" id="subme_target" class="frm_input">
					  <option value="">타겟을 선택하세요.</option>
					  <option value="blank">새창</option>
					  <option value="self" selected>현재창</option>
					</select>
					<span class="exp">예) <?php echo G5_BBS_URL;?>/board.php?bo_table=free</span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="subme_permit_level">메뉴보이기 레벨설정</label></th>
				<td>
					<select name="subme_permit_level" id="subme_permit_level">
						<option value="">선택</option>
						<?php 
						  for($i=1;$i<=10;$i++) {
						?>
						<option value="<?php echo $i;?>" <?php if($i == 1) echo "selected";?>><?php echo $i;?></option>
						<?php
							}
						?>
					</select> 레벨
					<span class="exp">선택한 그누레벨 이상의 회원에게 메뉴가 보이게 됩니다.</span>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="subme_side">사이드 레이아웃</label></th>
				<td>
					<label for="subme_side1"><input type="radio" name="subme_side" id="subme_side1" value="y" checked> 보이기</label>
					<label for="subme_side2"><input type="radio" name="subme_side" id="subme_side2" value="n"> 감추기</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="subme_use">출력여부</label></th>
				<td>
					<label for="subme_use1"><input type="radio" name="subme_use" id="subme_use1" value="y" checked> 보이기</label>
					<label for="subme_use2"><input type="radio" name="subme_use" id="subme_use2" value="n"> 감추기</label>
				</td>
			</tr>
			<?php if($me_code === '1') {?>
			<tr>
				<th scope="row"><label for="me_use_nav">상단메뉴사용</label></th>
				<td>
					<label for="subme_use_nav1"><input type="radio" name="subme_use_nav" id="subme_use_nav1" value="y" checked> 사용 </label>
					<label for="subme_use_nav2"><input type="radio" name="subme_use_nav" id="subme_use_nav2" value="n"> 사용하지 않음</label>
				</td>
			</tr>
			<?php }?>
			</table>

		</section>

		<?php echo $frm_submit;?>
		<?php } ?>
		</form>
	</div>
</div>
<script>
function fmenu_check(f) {
	if(f.me_name.value == '') {
		alert('메뉴명은 필수항목입니다.');
		f.me_name.focus();
		f.me_name.select();
		return false;
	}
}
function view_select_list(type) {
	var theme = '<?php echo $theme;?>';
	var url = "./menu_ajax.php";
	$.post(url, {'type':type,'theme':theme}, function(data) {
		if(data.pid) {
			var pid_str = data.pid;
			var name_str = data.name;
			var pid = pid_str.split("|");
			var name = name_str.split("|");
			if(pid.length>0) {
				var select = "<select name='select_item' id='select_item' onchange='set_item_value(this.value)'><option value=''>::선택해주세요::</option>";
				for(var i=0; i<pid.length;i++) {
					select += "<option value=\""+pid[i]+"|"+name[i]+"\">"+name[i]+"</option>";
				}
				select += "</select>";
			}
			$("#selbox").html(select);
		}
	},"json");
}
function set_item_value(str) {
	var type = $("#subme_type > option:selected").val();
	var data = str.split("|");
	switch(type) {
		case 'group':
			var url = '<?php echo G5_BBS_URL?>/group.php?gr_id='+data[0];
			var name = data[1];
			break;
		case 'board':
			var url = '<?php echo G5_BBS_URL?>/board.php?bo_table='+data[0];
			var path = data[1].split(' > ');
			var name = path[1];
			break;
		case 'page':
			var url = '<?php echo G5_BBS_URL?>/content.php?co_id='+data[0];
			var name = data[1];
			break;
	}
	$("#subme_link").val(url);
	$("#subme_name").val(name);
}
function delete_menu() {
	if(confirm("본 메뉴를 삭제하시면 하위메뉴까지 모두 삭제됩니다.\n\n그래도 삭제하시겠습니까?")) {
		var form = document.fmenu;
		form.mode.value = 'delete';
		form.submit();
	} else return false;
}
</script>
</body>
</html>
