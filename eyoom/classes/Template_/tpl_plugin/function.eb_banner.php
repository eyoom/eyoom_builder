<?php

/* Return Banner Data Function */

function eb_banner($loccd) {
	global $g5, $theme;

	$link_path = G5_DATA_URL.'/banner/';

	// 배너위치로 등록된 배너 불러오기
	$sql = "select * from {$g5['eyoom_banner']} where bn_theme='{$theme}' and bn_location = '" . $loccd . "' and bn_state = '1' order by bn_regdt desc";
	$result = sql_query($sql, false);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$banner[$i][$row['bn_no']] = $row;
	}
	$max_num = count($banner)-1;
	mt_srand ((double) microtime() * 1000000);
	$num = mt_rand(0, $max_num);
	$bn = $banner[$num];
	$bn_no = key($bn);
	$data = $banner[$num][$bn_no];
	unset($banner);

	if($data) {
		if($data['bn_type'] == 'intra') {
			$img = $data['bn_img'];
			$data['image'] = $link_path.$theme .'/'. $img;

			if($data['bn_link'] == '') $data['bn_link'] = 'nolink';

			$data['tag_img'] = '<img class="img-responsive full-width" src="'.$data['image'].'" align="absmiddle">';

			if ( $data['bn_link'] != '' && $data['bn_link'] != 'nolink' ){
				$tocken = encrypt_md5($bn_no . "||" . $_SERVER['REMOTE_ADDR'] . "||" . $data['bn_link']);
				$data['html'] = '<a id="banner_' . $data['bn_no'] . '" href="' . G5_BBS_URL . '/banner.php?tocken=' . $tocken . '" target="' . $data['bn_target'] . '">';
				$data['html'] .= $data['tag_img'];
				$data['html'] .= '</a>';
			} else {
				$data['html'] = $data['tag_img'];
			}
		} else if($data['bn_type'] == 'extra') {
			$data['html'] = stripslashes($data['bn_code']);
		}
		$banner[] = $data;
	}

	sql_query("update {$g5['eyoom_banner']} set bn_exposed = bn_exposed + 1 where bn_no = '{$bn_no}'");

	return $banner;
}

function encrypt_md5($buf, $key="password") {
	$key1 = pack("H*",md5($key));
	while($buf) {
		$m = substr($buf, 0, 16);
		$buf = substr($buf, 16);
		
		$c = "";
		for($i=0;$i<16;$i++) $c .= $m{$i}^$key1{$i};
		$ret_buf .= $c;
		$key1 = pack("H*",md5($key.$key1.$m));
	}
	
	$len = strlen($ret_buf);
	for($i=0; $i<$len; $i++) $hex_data .= sprintf("%02x", ord(substr($ret_buf, $i, 1)));
	return($hex_data);
}

?>