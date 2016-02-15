<?php
$sub_menu = "800600";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

include './eyoom_theme.php';

$sql_common = " from {$g5['eyoom_tag']} ";

$sql_search = " where (1) and tg_theme = '{$_theme}' ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'tg_word' 	:
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst = "tg_regdt";
    $sod = "desc";
    $sdt = "";
} else if($sst != 'tg_regdt') {
	$sdt = ", tg_regdt {$sod}";
}

$sql_order = " order by {$sst} {$sod} {$sdt}";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order}";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows}";
$result = sql_query($sql);

$g5['title'] = '태그 관리';
include_once('../admin.head.php');

$colspan = 9;
?>
<link rel="stylesheet" href="./css/eyoom_admin.css">
<style>
	.btn {border:1px solid #ccc;padding:3px 10px;display:inline-block;color:#fff;background: #ddd;color:#555;}
	.btn-dpmenu-y {background: #2388b6;color:#fff;border:1px solid #555;}
	.btn-dpmenu-n {background: #bf1d11;color:#fff;border:1px solid #555;}
	.btn-recommdt-y {background: #778b3a;color:#fff;border:1px solid #3e6616;}
	.btn-recommdt-n {background: #6d5444;color:#fff;border:1px solid #3e6616;}
</style>
<form name="ftheme" onsubmit="return ftheme_check(this)" method="post">
<input type="hidden" name="mode" id="mode" value="skin">
<input type="hidden" name="theme" id="theme" value="<?php if($_theme) echo $_theme; else echo $theme;?>">
<input type="hidden" name="ref" id="ref" value="banner_list.php">

<?php include './theme_manager.php'; // 테마 메니저 ?>

<h2 class="h2_frm">[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] 태그 설정 <span class='exp'>테마별로 태그를 관리합니다.</span></h2>
</form>
<br>
<div class="tbl_head01 tbl_wrap">
	<div class="local_ov01 local_ov">
	    등록된 태그수 <?php echo number_format($total_count) ?>개
	</div>

<?php if ($is_admin == 'super') { ?>
	<form name="ftagregist" id="ftagregist" action="tag_form_update.php" method="post">
	<input type="hidden" name="tg_theme" id="tg_theme" value="<?php echo $_theme;?>">
	<div class="btn_add01 btn_add" style="float:right;font-weight: bold;">
	    <input type="text" name="tg_word" value="" id="tg_word" required class="required frm_input">
	    <a href="javascript:;" onclick="return ftagregist_submit()" id="tag_add" style="padding:4px 10px 3px;">태그추가</a>
	</div>
	
	<div class="btn_add01 btn_add" style="float:right;font-weight: bold;color:#2e5ca0;">
	    [<?php echo $_theme;?> 테마]의 모든 태그를 
	    <select id="target_theme" name="target_theme">
		    <option value="">:: 선택 ::</option>
		    <?php 
			    if(is_array($exist_theme)) {
				    foreach($exist_theme as $k => $tm) {
					    if($tm == $_theme) continue;
					    echo '<option value="'.$tm.'">'.$tm.'</option>';
				    }
			    }
		    ?>
	    </select> 테마에 
	    <a href="javascript:;" onclick="return clone_tag();" style="padding:4px 10px 3px;">태그복사</a>
	</div>
	</form>
	
	<script>
	function ftagregist_submit() {
		var f = document.ftagregist;
		if( f.tg_word.value == '' ) {
			alert("추가할 태그를 입력해 주세요.");
			f.tg_word.focus();
			return false;
		} else {
			f.submit();
		}
	}
	
	function clone_tag() {
		var theme = '<?php echo $_theme;?>';
		var target = $("#target_theme option:selected").val();
		if(!target) {
			alert('태그를 복사할 테마를 선택해 주세요.');
			return false;
		} else {
			var url = "./tag_clone.php";
			$.post(url, {'theme':theme,'target':target}, function(data) {
				if(data.tag_clone == 'ok') {
					alert('정상적으로 태그를 복사하였습니다.');
					document.location.href = "./tag_list.php?thema="+target;
				}
			},"json");
		}
	}
	</script>
<?php } ?>


	<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="sfl" value="tg_word">
	태그명 : 
	<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
	<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
	<input type="submit" class="btn_submit" value="검색">
	</form>
	
</div>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col"><?php echo subject_sort_link('tg_word') ?>태그명</a></th>
        <th scope="col"><?php echo subject_sort_link('tg_regcnt') ?>등록수</a></th>
        <th scope="col"><?php echo subject_sort_link('tg_scnt') ?>검색수</a></th>
        <th scope="col"><?php echo subject_sort_link('tg_score') ?>노출점수</a></th>
        <th scope="col"><?php echo subject_sort_link('tg_dpmenu') ?>메뉴노출</a></th>
        <th scope="col">태그추천</th>
        <th scope="col"><?php echo subject_sort_link('tg_recommdt') ?>추천일자</a></th>
        <th scope="col"><?php echo subject_sort_link('tg_regdt') ?>등록일</a></th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $one_update = '<a href="./tag_form.php?tg_id='.$row['tg_id'].'&amp;'.$qstr.'">수정</a>';
        $one_delete = '<a href="./tag_form_update.php?w=d&amp;tg_id='.$row['tg_id'].'&amp;'.$qstr.'" onclick="return delconfirm();">삭제</a>';
        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td align="center">
            <?php echo $row['tg_word']; ?>
        </td>
        <td align="center">
            <?php echo $row['tg_regcnt']?>
        </td>
        <td align="center">
			<?php echo $row['tg_scnt']?>
        </td>
        <td align="right">
			<?php echo number_format($row['tg_score']);?>
        </td>
		<td align="center" style="width:120px;">
			<a href="javascript:;" id="dpmenu_y_<?php echo $row['tg_id'];?>" class="btn <?php if($row['tg_dpmenu'] == 'y') echo 'btn-dpmenu-y';?>" onclick="chg_dpmenu('<?php echo $row['tg_id'];?>', 'y');return false;">노출</a>
			<a href="javascript:;" id="dpmenu_n_<?php echo $row['tg_id'];?>" class="btn <?php if($row['tg_dpmenu'] == 'n') echo 'btn-dpmenu-n';?>" onclick="chg_dpmenu('<?php echo $row['tg_id'];?>', 'n');return false;">미노출</a>
        </td>
        <td align="center" style="width:120px;">
			<a href="javascript:;" id="tg_recommbtn_y_<?php echo $row['tg_id'];?>" class="btn btn-recommdt-y" onclick="set_recommend('<?php echo $row['tg_id'];?>','y');return false;">추천</a>
			<a href="javascript:;" id="tg_recommbtn_n_<?php echo $row['tg_id'];?>" class="btn btn-recommdt-n" onclick="set_recommend('<?php echo $row['tg_id'];?>','n');return false;">취소</a>
        </td>
        <td align="center" style="width:120px;">
			<div id="tg_recommdt_<?php echo $row['tg_id'];?>"><?php echo $row['tg_recommdt'] == '0000-00-00 00:00:00' ? '-':$row['tg_recommdt'];?></div>
        </td>
        <td align="center" style="width:120px;">
			<?php echo $row['tg_regdt']?>
        </td>
        <td class="td_mngsmall">
            <?php echo $one_update; ?> &nbsp;
            <?php echo $one_delete; ?>
        </td>
    </tr>
    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">등록된 태그가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['PHP_SELF'].'?'.$qstr.'&amp;page='); ?>

<script>

var delconfirm = function() {
	if(confirm("해당 태그를 정말로 삭제하시겠습니까?")) {
		return true;
	} else return false;
}

var chg_dpmenu = function(id,yn) {
	var url = "./tag_dpmenu.php";
	$.post(url, {'id':id,'yn':yn}, function(data) {
		if(data.dpmenu) {
			if(data.dpmenu == 'y') {
				$('#dpmenu_y_'+id).removeClass( 'btn-dpmenu-y' );
				$('#dpmenu_n_'+id).addClass( 'btn-dpmenu-n' );
			} else {
				$('#dpmenu_y_'+id).addClass( 'btn-dpmenu-y' );
				$('#dpmenu_n_'+id).removeClass( 'btn-dpmenu-n' );
			}
		}
	},"json");
}

var set_recommend = function(id,yn) {
	var url = "./tag_recommend.php";
	$.post(url, {'id':id,'yn':yn}, function(data) {
		if(data.recommdt && yn == 'y') {
			$("#tg_recommdt_"+id).text(data.recommdt);
		} else {
			$("#tg_recommdt_"+id).text('-');
		}
	},"json");
}
</script>

<?php
include_once('../admin.tail.php');
?>
