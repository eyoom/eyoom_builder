<?php 
	include_once('./_common.php');
	include_once(EYOOM_PATH.'/common.php');

	if($_POST['theme'] != 'eyoom') exit;
	$sql = "update {$g5['config_table']} set cf_theme='' where cf_theme!='' ";
	if(sql_query($sql,false)) {
		$tocken = 'yes';
	}
	

	if($tocken) {
		$_value_array = array();
		$_value_array['result'] = $tocken;

		include_once EYOOM_CLASS_PATH."/json.class.php";

		$json = new Services_JSON();
		$output = $json->encode($_value_array);

		echo $output;
	}
	exit;
	
?>