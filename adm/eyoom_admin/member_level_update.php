<?php
$sub_menu = '800800';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], "r");

$levelset_config = G5_DATA_PATH.'/eyoom.levelset.php';
$levelset = $_POST['levelset'];

$levelset['max_use_gnu_level'] = $_POST['max_use_gnu_level'];
$levelset['cnt_gnu_level_2'] = $_POST['cnt_gnu_level_2'];
$levelset['cnt_gnu_level_3'] = $_POST['cnt_gnu_level_3'];
$levelset['cnt_gnu_level_4'] = $_POST['cnt_gnu_level_4'];
$levelset['cnt_gnu_level_5'] = $_POST['cnt_gnu_level_5'];
$levelset['cnt_gnu_level_6'] = $_POST['cnt_gnu_level_6'];
$levelset['cnt_gnu_level_7'] = $_POST['cnt_gnu_level_7'];
$levelset['cnt_gnu_level_8'] = $_POST['cnt_gnu_level_8'];
$levelset['cnt_gnu_level_9'] = $_POST['cnt_gnu_level_9'];
$levelset['calc_level_point'] = $_POST['calc_level_point'];
$levelset['calc_level_ratio'] = $_POST['calc_level_ratio'];

$qfile->save_file('levelset',$levelset_config,$levelset);

$levelinfo_config = G5_DATA_PATH.'/eyoom.levelinfo.php';
$lvlinfo = $_POST['levelinfo'];
foreach($lvlinfo as $level => $arr_level) {
	foreach($arr_level as $key => $val) {
		if($key == 'name') {
			if(!$val) $val = 'Level '.$level;
		}
		if($key == 'min') {
			if(!$val) $val = 0;
		}
		$levelinfo[$level][$key] = $val;
	}
}
$qfile->save_file('levelinfo',$levelinfo_config,$levelinfo,true);

$back = "./member_level.php";
?>
<script>
alert("정상적으로 이윰레벨 설정을 적용하였습니다.");
parent.document.location.href='<?php echo $back;?>';
</script>