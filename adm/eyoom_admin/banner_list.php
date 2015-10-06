<?php
$sub_menu = "800500";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

include './eyoom_theme.php';
$banner_folder = G5_DATA_PATH.'/banner';
if(@is_dir($banner_folder)) {
	@include_once($banner_folder.'/banner.'.$_theme.'.config.php');
} else {
	@mkdir($banner_folder, G5_DIR_PERMISSION);
	@chmod($banner_folder, G5_DIR_PERMISSION);
}
if(is_array($bn_loccd)) ksort($bn_loccd);

$sql_common = " from {$g5['eyoom_banner']} ";

$sql_search = " where bn_theme='{$_theme}' ";
if ($loccd) {
    $sql_search .= " and ( ";
    $sql_search .= " (bn_location = '{$loccd}') ";
    $sql_search .= " ) ";
}

$sql = " select count(*) as cnt {$sql_common} {$sql_search} order by bn_regdt desc ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} order by bn_regdt desc limit {$from_record}, {$rows}";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '배너/광고 리스트 관리';
include_once('../admin.head.php');

$colspan = 12;
?>
<link rel="stylesheet" href="./css/eyoom_admin.css">
<form name="ftheme" action="./banner_list_update.php" onsubmit="return ftheme_check(this)" method="post" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="mode" id="mode" value="skin">
<input type="hidden" name="theme" id="theme" value="<?php if($_theme) echo $_theme; else echo $theme;?>">
<input type="hidden" name="ref" id="ref" value="banner_list.php">

<?php include './theme_manager.php'; // 테마 메니저 ?>

</form>

<h2 class="h2_frm">[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] 배너/광고관리</h2><br>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    등록된 배너수 <?php echo number_format($total_count) ?>개
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

<label for="sfl" class="sound_only">검색대상</label>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
배너 선택보기 : 
<select name="loccd" id="loccd" onchange="this.form.submit();" class="frm_input">
	<option value="">전체보기</option>
	<?php foreach($bn_loccd as $key => $val) {?>
	<option value="<?php echo $key;?>" <?php if($loccd == $key) echo "selected";?>><?php echo $key .'.'.$val;?></option>
	<?php }?>
</select>
</form>

<?php if ($is_admin == 'super') { ?>
<div class="btn_add01 btn_add" style='float:left;'>
	<a href="banner_location.php?thema=<?php echo $_theme;?>" class="banner_location" target="win_banner_location">배너위치관리</a>
</div>
<div class="btn_add01 btn_add">
    <a href="./banner_form.php?thema=<?php echo $_theme;?>" id="bo_add">배너/광고 추가</a>
</div>
<?php } ?>

<form name="fbannerlist" id="fbannerlist" action="#" onsubmit="return fbannerlist_submit(this);" method="post">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">배너위치</th>
        <th scope="col">치환코드</th>
        <th scope="col">이미지</th>
        <th scope="col">노출수</th>
        <th scope="col">클릭수</th>
        <th scope="col">클릭률</th>
        <th scope="col">시작일</th>
        <th scope="col">종료일</th>
        <th scope="col">상태</th>
        <th scope="col">등록일</th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
		unset($bn_img);
		$one_update = '<a href="./banner_form.php?w=u&amp;bn_no='.$row['bn_no'].'&amp;thema='.$_theme.'&amp;'.$qstr.'">수정</a>';
		$one_del = '<a href="./banner_delete.php?bn_no='.$row['bn_no'].'&amp;thema='.$_theme.'&amp;'.$qstr.'" onclick="if(!confirm(\'정말로 삭제하시겠습니까?\')) return false;">삭제</a>';
        $bg = 'bg'.($i%2);

		$bn_file = G5_DATA_PATH.'/banner/'.$row['bn_theme'].'/'.$row['bn_img'];
		if (file_exists($bn_file) && $row['bn_img']) {
			$bn_url = G5_DATA_URL.'/banner/'.$row['bn_theme'].'/'.$row['bn_img'];
			$bn_img = '<img src="'.$bn_url.'" alt="" height=80> ';
		}
		$bn_exposed = $row['bn_exposed']==0 ? 1:$row['bn_exposed'];
		$ratio = ceil(($row['bn_clicked']/$bn_exposed)*100);
		switch($row['bn_state']) {
			case '1': $bn_state = "보이기"; break;
			case '2': $bn_state = "<span style='color:#f30;'>숨기기</span>"; break;
		}
		if($row['bn_period'] == '1') {
			$bn_start = '무제한';
			$bn_end = '무제한';
		} else {
			$bn_start = substr($row['bn_start'],0,4).'/'.substr($row['bn_start'],4,2).'/'.substr($row['bn_start'],-2);
			$bn_end = substr($row['bn_end'],0,4).'/'.substr($row['bn_end'],4,2).'/'.substr($row['bn_end'],-2);
		}
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <input type="checkbox" name="chk[]" value="<?php echo $row['bn_no'] ?>" id="chk_<?php echo $row['bn_no'] ?>">
        </td>
        <td align="left">
            <?php echo $row['bn_location'].'.'.$bn_loccd[$row['bn_location']]?> 
        </td>
        <td align="center">
            &lt;!--{@eb_banner(<?php echo $row['bn_location'];?>)}--&gt;{.html}&lt;!--{/}--&gt;
        </td>
		<td align="center">
			<?php echo $bn_img;?>
        </td>
		<td align="center">
			<?php echo $row['bn_exposed'];?>
        </td>
		<td align="center">
			<?php echo $row['bn_clicked'];?>
        </td>
		<td align="center">
			<?php echo $ratio;?> %
        </td>
		<td align="center">
			<?php echo $bn_start;?>
        </td>
		<td align="center">
			<?php echo $bn_end;?>
        </td>
		<td align="center">
            <select name="bn_state[<?php echo $row['bn_no'];?>]" id="bn_state_<?php echo $row['bn_no'];?>" required class="frm_input">
				<option value="">선택</option>
				<option value="1" <?php if($row['bn_state'] == '1') echo "selected";?>>O 보이기</option>
				<option value="2" <?php if($row['bn_state'] == '2') echo "selected";?>>X 숨기기</option>
			</select>
        </td>
        <td align="center">
			<?php echo $row['bn_regdt']?>
        </td>
        <td class="td_mngsmall">
            <?php echo $one_update ?>&nbsp;&nbsp;<?php echo $one_del ?>
        </td>
    </tr>
    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="btn_list01 btn_list">
    <?php if ($is_admin == 'super') { ?>
    <input type="button" name="act_button" value="선택수정" onclick="banner_edit();">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
    <?php } ?>
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['PHP_SELF'].'?'.$qstr.'&amp;page='); ?>

<script>
function fbannerlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(confirm("선택한 배너를 정말로 삭제하시겠습니까?")) {
            f.action = 'banner_delete.php';
        }
    }

    return true;
}
function banner_edit() {
	var f = document.fbannerlist;
    if (!is_checked("chk[]")) {
        alert("수정할 배너를 하나 이상 선택하세요.");
        return false;
    }
	f.action = 'banner_edit.php';
	f.submit();
}
$(function(){
    $(".banner_location").click(function(){
        window.open(this.href, "win_banner_location", "left=100,top=100,width=700,height=500");
        return false;
    });
});
</script>

<?php
include_once('../admin.tail.php');
?>
