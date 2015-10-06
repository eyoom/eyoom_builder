<?php
$sub_menu = "800200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

include './eyoom_theme.php';

$sql = "select * from {$g5['board_table']} as a left join {$g5['group_table']} as b on a.gr_id = b.gr_id where 1 order by a.gr_id, a.bo_table asc";
$result = sql_query($sql);
$_board = $bo_table = array();
for($i=0; $bbs=sql_fetch_array($result); $i++) {
	$tmp = sql_fetch("select bo_table, bo_skin, use_gnu_skin from {$g5['eyoom_board']} where bo_table='{$bbs['bo_table']}' and bo_theme='{$_theme}'",false);
	if(!$tmp['bo_table']) {
		sql_query("insert into {$g5['eyoom_board']} set bo_table='{$bbs['bo_table']}', gr_id='{$bbs['gr_id']}', bo_theme='{$_theme}', bo_skin='basic', use_gnu_skin='n'");
	}

	$_board[$i]['bo_table'] = $bo_table[$i] = $bbs['bo_table'];
	$_board[$i]['gr_subject'] = $bbs['gr_subject'];
	$_board[$i]['bo_subject'] = $bbs['bo_subject'];
	$_board[$i]['bo_skin'] = $tmp['bo_skin'] ? $tmp['bo_skin']:'basic';
	$_board[$i]['use_gnu_skin'] = $tmp['use_gnu_skin'] ? $tmp['use_gnu_skin']:'n';
}

// 그누보드에서 삭제된 게시판 삭제
if($i > 0) sql_query("delete from {$g5['eyoom_board']} where find_in_set(bo_table,'".implode(',',$bo_table)."') = 0 ",false);

$g5['title'] = '게시판설정';
include_once('../admin.head.php');

$colspan = 5;

$frm_submit = '
<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="전체적용" class="btn_submit" accesskey="s">
    <a href="'.G5_URL.'">메인으로</a>
</div>
';
?>
<link rel="stylesheet" href="./css/eyoom_admin.css">
<form name="ftheme" id="ftheme" action="./board_list_update.php" onsubmit="return ftheme_submit(this);" method="post">
<input type="hidden" name="mode" id="mode" value="board">
<input type="hidden" name="theme" id="theme" value="<?php echo $_theme;?>">
<input type="hidden" name="ref" id="ref" value="board_list.php">

<?php include './theme_manager.php'; // 테마 메니저 ?>

<h2 class="h2_frm">[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] 게시판설정 <span class='exp'>그누보드 스킨 선택시 그누보드 게시판관리에서 직접 설정하셔야 합니다.</span></h2>
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">그룹</th>
        <th scope="col">제목</th>
		<th scope="col">TABLE</th>
        <th scope="col">스킨선택</th>
        <th scope="col">상세설정</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $i<count($_board); $i++) {
        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td>
            <?php echo $_board[$i]['gr_subject'] ?>
        </td>
        <td>
            <?php echo get_text($_board[$i]['bo_subject']) ?>
        </td>
        <td>
            <input type="hidden" name="board_table[<?php echo $i ?>]" value="<?php echo $_board[$i]['bo_table'] ?>">
            <a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $_board[$i]['bo_table'] ?>"><?php echo $_board[$i]['bo_table']; ?></a>
        </td>
        <td>
            <label for="bo_skin_<?php echo $i; ?>" class="sound_only">스킨</label>
			<?php 
				$arr = $eb->get_skin_dir('board',EYOOM_THEME_PATH.'/'.$_theme.'/skin_'.$_tpl_name);
				$checked1 = $_board[$i]['use_gnu_skin'] == 'n' ? 'checked="checked"':'';
				$checked2 = $_board[$i]['use_gnu_skin'] == 'y' ? 'checked="checked"':'';
				if($arr) {
					echo '<select name="bo_skin_'.$i.'" id="bo_skin_'.$i.'_1" required class="required">';
					for ($j=0; $j<count($arr); $j++) {
						if ($j == 0) echo "<option value=\"\">선택</option>";
						echo "<option value=\"".$arr[$j]."\"".get_selected($_board[$i]['bo_skin'], $arr[$j]).">".$arr[$j]."</option>\n";
					}
					echo '</select>';
					echo '<label for="use_gnu_skin1"><input type="radio" name="use_gnu_skin_'.$i.'" id="use_gnu_skin_'.$i.'_1" value="n" '.$checked1.'> 이윰빌더 스킨</label>';
					echo '<label for="use_gnu_skin2"><input type="radio" name="use_gnu_skin_'.$i.'" id="use_gnu_skin_'.$i.'_2" value="y" '.$checked2.'> 그누보드 스킨</label>';
				} else {
					echo "현재 테마에는 게시판 스킨이 존재하지 않습니다.";
				}
			?>
        </td>
		<td class="bo_btns">
			<div><a href="./board_form.php?bo_table=<?php echo $_board[$i]['bo_table']; ?>&thema=<?php echo $_theme; ?>">상세설정</a></div>
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

<?php echo $frm_submit; ?>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['PHP_SELF'].'?'.$qstr.'&amp;page='); ?>

<script>
function ftheme_submit(f)
{
    return true;
}
</script>

<?php
include_once('../admin.tail.php');
?>
