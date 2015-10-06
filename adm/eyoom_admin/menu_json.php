<?php
$sub_menu = '800300';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');
auth_check($auth[$sub_menu], "r");

$theme = $_GET['thema'];
$admin_mode = true;
$eyoom_menu = $thema->eyoom_menu($theme);

$output  = '';
$output .= '[{';
$output .= '
	"id":"1",
	"text":"커뮤니티 메뉴",
	"children":[
';
if(is_array($eyoom_menu)) {
	foreach($eyoom_menu as $key => $val) {
		unset($blind);
		if($val['me_use'] == 'n') $blind = " <span style='color:#f30;'><i class='fa fa-eye-slash'></i></span>";
		$_output[$val['me_order']] .= '{';
		$_output[$val['me_order']] .= '"id":"'.$val['me_code'].'",';
		$_output[$val['me_order']] .= '"order":"'.$val['me_order'].'",';
		$_output[$val['me_order']] .= '"text":"'.$val['me_name'].$blind.'"';
		if(is_array($val) && count($val)>3) $_output[$val['me_order']] .= $thema->eyoom_menu_json($val);
		$_output[$val['me_order']] .= '}';
	}
	ksort($_output);
	$output .= implode(',',$_output);
}
$output .= '
	]
';
$output .= '}]';

echo $output;

?>