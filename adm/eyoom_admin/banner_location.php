<?php
$sub_menu = "800500";
include_once("./_common.php");

auth_check($auth[$sub_menu], 'w');

$g5['title'] = '배너/광고위치 관리';
include_once(G5_PATH.'/head.sub.php');

include './eyoom_theme.php';

$banner_config = G5_DATA_PATH.'/banner/banner.'.$_theme.'.config.php';
if(file_exists($banner_config)) {
	@include_once($banner_config);
}
$count = count($bn_loccd);
if(!$count) $count = 10;

$tmps = array_fill(1,$count,0);
$nums = array_keys($tmps);
$half = count($nums) / 2;
?>
<link rel="stylesheet" href="./css/eyoom_admin.css">
<div class="new_win">
    <h1>[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] <?php echo $g5['title']; ?><span style="font-size:12px;color:#777;margin-left:10px;font-weight:normal;">구분을 위해 배너위치를 입력해 놓으세요. 예) 메인상단큰배너, 우측배너1, 우측배너2</span></h1>

	<form name="form" method="post" action="./banner_location_update.php">
	<input type="hidden" name="theme" id="theme" value="<?php echo $_theme;?>">
	<div class="tbl_frm01 tbl_wrap" style="background:#f5f5f5;">
		<table>
        <colgroup>
            <col class="grid_2">
            <col>
        </colgroup>
		<tr>
			<th style="padding-left:20px;">배너/광고수</th>
			<td>
				<input type="text" name="banner_count" value="<?php echo $count;?>" id="banner_count" style="width:60px;" class="frm_input">
				<input type="submit" class="btn_submit" value="수정" style="width:60px;height:24px;">
			</td>
		</tr>
		</table>
	</div>
	<div class="tbl_frm01 tbl_wrap">
		<table>
        <colgroup>
            <col class="grid_2">
            <col>
			<col class="grid_2">
            <col>
        </colgroup>
		<?php for ( $k = 0; $k < $half; $k++ ){ $i = $nums[$k]; $j = $nums[$k + $half]; ?>
		<tr>
			<th style="padding-left:20px;"><b>배너위치 <?php echo $i?></b></th>
			<td><input type=text name="bn_loccd[<?php echo $i?>]" value="<?php echo ( $bn_loccd[$i] ? $bn_loccd[$i] : '배너위치입력' );?>" class="frm_input" style="width:95%;"></td>
			<th style="padding-left:20px;"><b>배너위치 <?php echo $j?></b></th>
			<td><input type=text name="bn_loccd[<?php echo $j?>]" value="<?php echo ( $bn_loccd[$j] ? $bn_loccd[$j] : '배너위치입력' );?>" class="frm_input" style="width:95%;"></td>
		</tr>
		<?php } ?>
		</table>
	</div>

    <div class="btn_confirm01 btn_confirm">
        <input type="submit" class="btn_submit" value="적용">
        <input type="button" class="btn_cancel" value="창닫기" onclick="window.close();">
    </div>

    </form>

</div>


<?php
include_once(G5_PATH.'/tail.sub.php');
?>
