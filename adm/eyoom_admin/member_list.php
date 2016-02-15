<?php
$sub_menu = "800700";
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['eyoom_member']} as em left join {$g5['member_table']} as gm on em.mb_id = gm.mb_id ";

$sql_search = " where em.mb_id!='' ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'gm.mb_point' :
        case 'em.level_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'gm.mb_level' :
        case 'em.level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst = "gm.mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '이윰멤버관리';
include_once('../admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 16;
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    총회원수 <?php echo number_format($total_count) ?>명
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="em.mb_id"<?php echo get_selected($_GET['sfl'], "em.mb_id"); ?>>회원아이디</option>
    <option value="gm.mb_nick"<?php echo get_selected($_GET['sfl'], "gm.mb_nick"); ?>>닉네임</option>
    <option value="gm.mb_name"<?php echo get_selected($_GET['sfl'], "gm.mb_name"); ?>>이름</option>
    <option value="gm.mb_level"<?php echo get_selected($_GET['sfl'], "gm.mb_level"); ?>>그누레벨</option>
    <option value="em.level"<?php echo get_selected($_GET['sfl'], "em.level"); ?>>이윰레벨</option>
    <option value="gm.mb_point"<?php echo get_selected($_GET['sfl'], "gm.mb_point"); ?>>그누포인트</option>
    <option value="em.level_point"<?php echo get_selected($_GET['sfl'], "em.level_point"); ?>>경험치</option>
    <option value="gm.mb_datetime"<?php echo get_selected($_GET['sfl'], "gm.mb_datetime"); ?>>가입일시</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" class="btn_submit" value="검색">

</form>

<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">

<div class="tbl_head02 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_id"><?php echo subject_sort_link('gm.mb_id') ?>아이디</a></th>
        <th scope="col" id="mb_list_name"><?php echo subject_sort_link('gm.mb_name') ?>이름</a></th>
        <th scope="col" id="mb_list_nick"><?php echo subject_sort_link('gm.mb_nick', '', 'desc') ?>닉네임</a></th>
        <th scope="col" id="mb_list_level"><?php echo subject_sort_link('gm.mb_level') ?>그누레벨</a></th>
        <th scope="col" id="mb_list_point"><?php echo subject_sort_link('gm.mb_point') ?><?php echo $levelset['gnu_name'];?></a></th>
        <th scope="col" id="mb_list_level"><?php echo subject_sort_link('em.level') ?>이윰레벨</a></th>
		<th scope="col" id="mb_list_point"><?php echo subject_sort_link('em.level_point') ?><?php echo $levelset['eyoom_name'];?></a></th>
        <th scope="col" id="mb_list_lastcall"><?php echo subject_sort_link('mb_today_login', '', 'desc') ?>최종접속</a></th>
        <th scope="col" id="mb_list_datetime"><?php echo subject_sort_link('mb_datetime', '', 'desc') ?>가입일</a></th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
		if(!$row['mb_id']) continue;
        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td headers="mb_list_id" class="td_name sv_use"><?php echo $row['mb_id']; ?></td>
        <td headers="mb_list_name" class="td_mbname"><?php echo get_text($row['mb_name']); ?></td>
        <td headers="mb_list_name" class="td_mbname"><?php echo get_text($row['mb_nick']); ?></td>
        <td headers="mb_list_name" class="td_level"><?php echo number_format($row['mb_level']); ?></td>
        <td headers="mb_list_point" class="td_point"><?php echo number_format($row['mb_point']); ?></td>
        <td headers="mb_list_name" class="td_level"><?php echo number_format($row['level']); ?></td>
		<td headers="mb_list_point" class="td_point"><?php echo number_format($row['level_point']); ?></td>
        <td headers="mb_list_lastcall" class="td_date"><?php echo substr($row['mb_today_login'],2,8); ?></td>
        <td headers="mb_list_datetime" class="td_datetime"><?php echo substr($row['mb_datetime'],2,8); ?></td>
    </tr>

    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<section id="point_mng">
    <h2 class="h2_frm">개별회원 이윰레벨 포인트(<?php echo $levelset['eyoom_name'];?>) 증감 설정</h2>

    <form name="fpointlist2" method="post" id="fpointlist2" action="./member_point_update.php" autocomplete="off">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="<?php echo $token ?>">

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="mb_id">회원아이디<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="mb_id" value="<?php echo $mb_id ?>" id="mb_id" class="required frm_input" required onblur="calc_po_point();"></td>
        </tr>
        <tr>
            <th scope="row"><label for="po_point">적용 <?php echo $levelset['eyoom_name'];?><strong class="sound_only">필수</strong></label></th>
            <td>
				<select name="po_calc" id="po_calc">
					<option value="plus">+</option>
					<option value="minus">-</option>
				</select>
				<input type="text" name="po_point" id="po_point" required class="required frm_input" onblur="calc_po_point();">
			</td>
        </tr>
        <tr>
            <th scope="row"><label for="po_result">계산 결과</label></th>
            <td><div id="po_result" style='min-height:25px;'></div></td>
        </tr>
        </tbody>
        </table>
    </div>

    <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="적용하기" class="btn_submit">
    </div>

    </form>

</section>
<script>
function calc_po_point() {
	var obj = $("#po_result");
	var mb_id = $("#mb_id").val();
	var po_calc = $("#po_calc > option:selected").val();
	var po_point = parseInt($("#po_point").val());
	obj.html('');
	if(mb_id && po_calc && !isNaN(po_point)) {
		$.ajax({
			url: "./member_point_ajax.php",
			type: "POST",
			data: {
				"mb_id": mb_id,
				"po_calc": po_calc,
				"po_point": po_point
			},
			dataType: "json",
			async: false,
			cache: false,
			success: function(data, textStatus) {
				var eyoom_point = data.eyoom_point;
				var clac_point = data.clac_point;
				var calc = po_calc == 'plus' ? '+':'-';
				var addhtml = "<div style='padding:5px 0'>기존<?php echo $levelset['eyoom_name'];?>("+eyoom_point+") "+calc+" 적용<?php echo $levelset['eyoom_name'];?>("+po_point+") = 최종<?php echo $levelset['eyoom_name'];?>(" + clac_point + ")</div>";
				obj.html(addhtml);
			}
		});
	}
}
</script>
<?php
include_once ('../admin.tail.php');
?>
