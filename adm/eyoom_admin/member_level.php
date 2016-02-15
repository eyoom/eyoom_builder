<?php
$sub_menu = '800800';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(EYOOM_PATH.'/common.php');
auth_check($auth[$sub_menu], "r");

$g5['title'] = '이윰레벨설정';
include_once (G5_ADMIN_PATH.'/admin.head.php');

$frm_submit = '
<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s">
    <a href="'.G5_URL.'">메인으로</a>
</div>
';
?>
<link rel="stylesheet" href="./css/eyoom_admin.css">
<form name="fmemberlevel" action="./member_level_update.php" onsubmit="return fmemberlevel_check(this)" method="post">
<section id="anc_scf_info">
    <h2 class="h2_frm"><b style='color:#f30;'>포인트 설정</b></h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>포인트설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="theme">그누보드 포인트 명칭</label></th>
            <td>
				<input type="text" name="levelset[gnu_name]" id="levelset[gnu_name]" value="<?php echo $levelset['gnu_name'];?>" style="width:80px;" class="frm_input"> <span class="exp">그누보드 회원포인트 명칭을 설정합니다. 포인트값 설정은 <a href="../config_form.php">[환경설정 - 기본환경설정]</a>에서 설정해 주세요.</span>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="theme">이윰레벨 포인트 명칭</label></th>
            <td>
				<input type="text" name="levelset[eyoom_name]" id="levelset[eyoom_name]" value="<?php echo $levelset['eyoom_name'];?>" style="width:80px;" class="frm_input"> <span class="exp">이윰 레벨을 결정하는 포인트의 명칭을 설정합니다.</span>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="theme">이윰레벨 포인트값</label></th>
            <td>
				<div class="inner_titme">커뮤니티 포인트</div>
				<ul class="eyoom_level_point_set">
					<li><span class="point_item">로그인 포인트</span> : <input type="text" name="levelset[login]" id="levelset[login]" value="<?php echo $levelset['login']?>" class="frm_input"> </li>
					<li><span class="point_item">글쓰기 포인트</span> : <input type="text" name="levelset[write]" id="levelset[write]" value="<?php echo $levelset['write']?>" class="frm_input"> </li>
					<li><span class="point_item">답글쓰기 포인트</span> : <input type="text" name="levelset[reply]" id="levelset[reply]" value="<?php echo $levelset['reply']?>" class="frm_input"> </li>
					<li><span class="point_item">글읽기 포인트</span> : <input type="text" name="levelset[read]" id="levelset[read]" value="<?php echo $levelset['read']?>" class="frm_input"> </li>
					<li><span class="point_item">댓글쓰기 포인트</span> : <input type="text" name="levelset[cmt]" id="levelset[cmt]" value="<?php echo $levelset['cmt']?>" class="frm_input"> </li>
					<li><span class="point_item">추천 포인트</span> : <input type="text" name="levelset[good]" id="levelset[good]" value="<?php echo $levelset['good']?>" class="frm_input"> </li>
					<li><span class="point_item">추천받기 포인트</span> : <input type="text" name="levelset[regood]" id="levelset[regood]" value="<?php echo $levelset['regood']?>" class="frm_input"> </li>
					<li><span class="point_item">비추천 포인트</span> : <input type="text" name="levelset[nogood]" id="levelset[nogood]" value="<?php echo $levelset['nogood']?>" class="frm_input"> </li>
					<li><span class="point_item">비추천받기 포인트</span> : <input type="text" name="levelset[renogood]" id="levelset[renogood]" value="<?php echo $levelset['renogood']?>" class="frm_input"> </li>
					<li><span class="point_item">쪽지쓰기 포인트</span> : <input type="text" name="levelset[memo]" id="levelset[memo]" value="<?php echo $levelset['memo']?>" class="frm_input"> </li>
					<!--li><span class="point_item">쪽지받기 포인트</span> : <input type="text" name="levelset[rememo]" id="levelset[rememo]" value="<?php echo $levelset['rememo']?>" class="frm_input"> </li>
					<li><span class="point_item">스크랩하기 포인트</span> : <input type="text" name="levelset[scrap]" id="levelset[scrap]" value="<?php echo $levelset['scrap']?>" class="frm_input"> </li-->
					<li><span class="point_item">팔로우하기 포인트</span> : <input type="text" name="levelset[following]" id="levelset[following]" value="<?php echo $levelset['following']?>" class="frm_input"> </li>
					<li><span class="point_item">팔로우받기 포인트</span> : <input type="text" name="levelset[follower]" id="levelset[follower]" value="<?php echo $levelset['follower']?>" class="frm_input"> </li>
					<li><span class="point_item">배너/광고클릭 포인트</span> : <input type="text" name="levelset[banner]" id="levelset[banner]" value="<?php echo $levelset['banner']?>" class="frm_input"> </li>
					<!--li><span class="point_item">1:1문의하기 포인트</span> : <input type="text" name="levelset[qa]" id="levelset[qa]" value="<?php echo $levelset['qa']?>" class="frm_input"> </li>
					<li><span class="point_item">설문참여 포인트</span> : <input type="text" name="levelset[poll]" id="levelset[poll]" value="<?php echo $levelset['poll']?>" class="frm_input"> </li-->
				</ul>
				<div class="inner_titme" style="border-top:1px solid #eee;padding-top:20px;">쇼핑몰 포인트</div>
				<ul class="eyoom_level_point_set">
					<li><span class="point_item">상품보기 포인트</span> : <input type="text" name="levelset[goodsview]" id="levelset[goodsview]" value="<?php echo $levelset['goodsview']?>" class="frm_input"> </li>
					<li><span class="point_item">장바구니담기 포인트</span> : <input type="text" name="levelset[cart]" id="levelset[cart]" value="<?php echo $levelset['cart']?>" class="frm_input"> </li>
					<li><span class="point_item">위시리스트 포인트</span> : <input type="text" name="levelset[wishlist]" id="levelset[wishlist]" value="<?php echo $levelset['wishlist']?>" class="frm_input"> </li>
					<li><span class="point_item">상품추천 포인트</span> : <input type="text" name="levelset[recommend]" id="levelset[recommend]" value="<?php echo $levelset['recommend']?>" class="frm_input"> </li>
					<li><span class="point_item">상품후기 포인트</span> : <input type="text" name="levelset[review]" id="levelset[review]" value="<?php echo $levelset['review']?>" class="frm_input"> </li>
					<li><span class="point_item">상품문의 포인트</span> : <input type="text" name="levelset[goodsqa]" id="levelset[goodsqa]" value="<?php echo $levelset['goodsqa']?>" class="frm_input"> </li>
					<li><span class="point_item">주문완료 포인트</span> : <input type="text" name="levelset[order]" id="levelset[order]" value="<?php echo $levelset['order']?>" class="frm_input"> </li>
					<li><span class="point_item">주문취소 포인트</span> : <input type="text" name="levelset[cancel]" id="levelset[cancel]" value="<?php echo $levelset['cancel']?>" class="frm_input"> </li>
				</ul>
			</td>
		</tr>
		</table>
	</div>
</section>

<?php echo $frm_submit; ?>

<?php

//이윰레벨 기본값 
// max_use_gnu_level : 이윰레벨로 활용할 그누보드 레벨
$mgl = !$_POST['max_use_gnu_level'] ? $levelset['max_use_gnu_level'] : $_POST['max_use_gnu_level'];

$cgl2 = !$_POST['cnt_gnu_level_2'] ? $levelset['cnt_gnu_level_2'] : $_POST['cnt_gnu_level_2'];
$cgl3 = !$_POST['cnt_gnu_level_3'] ? $levelset['cnt_gnu_level_3'] : $_POST['cnt_gnu_level_3'];
$cgl4 = !$_POST['cnt_gnu_level_4'] ? $levelset['cnt_gnu_level_4'] : $_POST['cnt_gnu_level_4'];
$cgl5 = !$_POST['cnt_gnu_level_5'] ? $levelset['cnt_gnu_level_5'] : $_POST['cnt_gnu_level_5'];
$cgl6 = !$_POST['cnt_gnu_level_6'] ? $levelset['cnt_gnu_level_6'] : $_POST['cnt_gnu_level_6'];
$cgl7 = !$_POST['cnt_gnu_level_7'] ? $levelset['cnt_gnu_level_7'] : $_POST['cnt_gnu_level_7'];
$cgl8 = !$_POST['cnt_gnu_level_8'] ? $levelset['cnt_gnu_level_8'] : $_POST['cnt_gnu_level_8'];
$cgl9 = !$_POST['cnt_gnu_level_9'] ? $levelset['cnt_gnu_level_9'] : $_POST['cnt_gnu_level_9'];

$clp = !$_POST['calc_level_point'] ? $levelset['calc_level_point'] : $_POST['calc_level_point'];
$clr = !$_POST['calc_level_ratio'] ? $levelset['calc_level_ratio'] : $_POST['calc_level_ratio'];

?>
<a name="calc"></a>
<section id="anc_scf_info">
    <h2 class="h2_frm"><b style='color:#f30;'>이윰레벨 설정</b></h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>포인트설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
		<tr>
			<th scope="row"><label for="theme">레벨룰</label></th>
            <td>
				<div style="height:30px;">
					<span class="level_item">
						이윰레벨로 사용할 그누레벨 : 
						<select name="max_use_gnu_level" id="max_use_gnu_level">
							<option value="2" <?php if($mgl == '2') echo "selected";?>>2레벨</option>
							<option value="3" <?php if($mgl == '3') echo "selected";?>>3레벨</option>
							<option value="4" <?php if($mgl == '4') echo "selected";?>>4레벨</option>
							<option value="5" <?php if($mgl == '5') echo "selected";?>>5레벨</option>
							<option value="6" <?php if($mgl == '6') echo "selected";?>>6레벨</option>
							<option value="7" <?php if($mgl == '7') echo "selected";?>>7레벨</option>
							<option value="8" <?php if($mgl == '8') echo "selected";?>>8레벨</option>
							<option value="9" <?php if($mgl == '9') echo "selected";?>>9레벨</option>
						</select>까지 사용
					</span>
				</div>
				<div style="padding:5px 0 10px;">
					<table class="level_tbl" style="width:700px;">
						<thead>
						<tr>
							<th>그누레벨</th>
							<th>2레벨</th>
							<th>3레벨</th>
							<th>4레벨</th>
							<th>5레벨</th>
							<th>6레벨</th>
							<th>7레벨</th>
							<th>8레벨</th>
							<th>9레벨</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<th rowspan="10">구간별 이윰레벨 갯수</th>
							<th><input type="text" name="cnt_gnu_level_2" id="cnt_gnu_level_2" value="<?php echo $cgl2;?>" class="frm_input" style="width:50px;text-align:right;"></th>
							<th><input type="text" name="cnt_gnu_level_3" id="cnt_gnu_level_3" value="<?php echo $cgl3;?>" class="frm_input" style="width:50px;text-align:right;"></th>
							<th><input type="text" name="cnt_gnu_level_4" id="cnt_gnu_level_4" value="<?php echo $cgl4;?>" class="frm_input" style="width:50px;text-align:right;"></th>
							<th><input type="text" name="cnt_gnu_level_5" id="cnt_gnu_level_5" value="<?php echo $cgl5;?>" class="frm_input" style="width:50px;text-align:right;"></th>
							<th><input type="text" name="cnt_gnu_level_6" id="cnt_gnu_level_6" value="<?php echo $cgl6;?>" class="frm_input" style="width:50px;text-align:right;"></th>
							<th><input type="text" name="cnt_gnu_level_7" id="cnt_gnu_level_7" value="<?php echo $cgl7;?>" class="frm_input" style="width:50px;text-align:right;"></th>
							<th><input type="text" name="cnt_gnu_level_8" id="cnt_gnu_level_8" value="<?php echo $cgl8;?>" class="frm_input" style="width:50px;text-align:right;"></th>
							<th><input type="text" name="cnt_gnu_level_9" id="cnt_gnu_level_9" value="<?php echo $cgl9;?>" class="frm_input" style="width:50px;text-align:right;"></th>
						</tr>
						</tbody>
					</table>
				</div>
				<div>
					<span class="level_item">기준포인트 : <input type="text" name="calc_level_point" id="calc_level_point" value="<?php echo $clp;?>" class="frm_input"></span>
					<span class="level_item">포인트 증가비율 : <input type="text" name="calc_level_ratio" id="calc_level_ratio" value="<?php echo $clr;?>" class="frm_input">%</span>
					<span class="bo_btns" style="display:inline-block;width:80px;text-align:center;"><a href="javascript:;" onclick="calc_eyoom_level();return false;" style="background:#ff3061;color:#fff;padding:5px;border:0;">계산하기</a></span>  <span class="exp">계산하기를 실행한 후, [확인]버튼을 클릭해야만 정상적으로 적용이 됩니다.</span>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="theme">레벨별 포인트 설정</label></th>
            <td>
				<table class="level_tbl">
					<colgroup>
						<col class="grid_2">
						<col class="grid_4">
						<col class="grid_2">
						<col class="grid_4">
						<col>
						<col>
						<col>
					</colgroup>
					<thead>
					<tr>
						<th>그누레벨</th>
						<th>그누레벨 별칭</th>
						<th>이윰레벨</th>
						<th>이윰레벨 별칭</th>
						<th>최소 포인트</th>
						<th>최대 포인트</th>
						<th>레벨업 포인트</th>
					</tr>
					</thead>
					<tbody>
					<?php
					$level = 1;
					for($i=2;$i<=$mgl;$i++) {
						$cgl_varname = 'cgl'.$i;
						$cgl = $$cgl_varname;
						for($j=0;$j<$cgl;$j++) {
							$min = $max;
							$max = $min + $clp*$clr*$level/100;
					?>
					<tr>
						<?php if($j==0) {?>
						<th rowspan="<?php echo $cgl;?>"><?php echo $i?>레벨</th>
						<th rowspan="<?php echo $cgl;?>"><input type="text" name="levelset[gnu_alias_<?php echo $i;?>]" id="levelset[gnu_alias_<?php echo $i;?>]" value="<?php echo $levelset['gnu_alias_'.$i];?>" class="frm_input" style='font-weight:normal;'></th>
						<?php }?>
						<td style="text-align:center;">Level <?php echo $level;?></td>
						<th><input type="text" name="levelinfo[<?php echo $level;?>][name]" id="levelinfo[<?php echo $level;?>][name]" value="<?php echo $levelinfo[$level]['name'];?>" class="frm_input" style='font-weight:normal;'></th>
						<td><div class="point_number"><?php echo number_format($min);?></div><input type="hidden" name="levelinfo[<?php echo $level;?>][min]" value="<?php echo $min;?>"></td>
						<td><div class="point_number"><?php echo number_format($max);?></div><input type="hidden" name="levelinfo[<?php echo $level;?>][max]" value="<?php echo $max;?>"></td>
						<td><div class="point_number"><?php echo number_format($max-$min);?></div></td>
					</tr>
					<?php
							$level++;
						}
					}
					?>
					</tbody>
				</table>
			</td>
		</tr>
		</table>
	</div>
</section>

<?php echo $frm_submit; ?>
</form>
<script>
function calc_eyoom_level() {
	var level = parseInt($("#max_use_gnu_level > option:selected").val());
	for(var i=2;i<=level;i++) {
		if(check_cnt_gnu_level(i)==false) {break; return false;}
	}
	if($("#calc_level_point").val() == '') {
		alert("기준포인트를 입력해 주세요.");
		$("#calc_level_point").focus();
		return false;
	}
	if($("#calc_level_ratio").val() == '') {
		alert("포인트 증가비율을 입력해 주세요.");
		$("#calc_level_ratio").focus();
		return false;
	}
	var form = document.fmemberlevel;
	form.action = 'member_level.php#calc';
	form.submit();
}
function check_cnt_gnu_level(num) {
	if($("#cnt_gnu_level_"+num).val() == '') {
		alert("그누 "+num+"레벨 구간에 이윰레벨의 갯수를 설정해 주세요.");
		$("#cnt_gnu_level_"+num).focus();
		return false;
	}
}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
