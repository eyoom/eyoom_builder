<?php
	if (!defined('_GNUBOARD_')) exit;

	// 답변글에 대한 내글반응 적용하기
	if ($w == 'r') {
		$respond = array();
		$respond['type']		= 'reply';
		$respond['bo_table']	= $bo_table;
		$respond['pr_id']		= $_POST['wr_id'];
		$respond['wr_id']		= $wr_id;
		$respond['wr_subject']	= $wr_subject;
		$respond['wr_mb_id']	= $wr['mb_id'];
		if($_POST['anonymous'] == 'y') $anonymous = true;
		$eb->respond($respond);
	}

	// 업로드된 파일 정보 가져오기
	$result = sql_query(" select * from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' ");
	for($i=0; $row=sql_fetch_array($result);$i++) {
		if(!preg_match("/.(gif|jpg|jpeg|png)$/i",$row['bf_file'])) continue;
		$wr_image['bf'][$i] = "/data/file/{$bo_table}/".$row['bf_file'];
	}

	// 내용중의 링크 이미지 정보 가져오기
	$matches = get_editor_image(stripslashes($wr_content),false);
	if($matches[1]) {
		foreach($matches[1] as $k => $image) {
			$p = parse_url($image);
			$host = preg_replace("/www\./i","",$p['host']);
			$_host = preg_replace("/www\./i","",$_SERVER['HTTP_HOST']);

			$ex_url = '';
			if($host != $_host) $ex_url = "http://".$host;
			$wr_image['url'][$k] = $ex_url . $p['path'];
		}
	}
	if($wr_image) $wr_image = serialize($wr_image);

	$wr_content = $eb->remove_editor_code($wr_content);
	$wr_content = $eb->remove_editor_emoticon($wr_content);

	$wr_video = $eb->get_editor_video($wr_content);
	$wr_video = serialize($wr_video[1]);
	$wr_sound = $eb->get_editor_sound($wr_content);
	$wr_sound = serialize($wr_sound[1]);

	$wr_content = $eb->remove_editor_video($wr_content);
	$wr_content = $eb->remove_editor_sound($wr_content);

	// 내용글에서 텍스트 추출
	$content = conv_content(stripslashes($wr_content), 1);
	$content = trim(addslashes(cut_str(strip_tags($content), 300, '…')));

	$where = "bo_table = '{$bo_table}' and wr_id = '{$wr_id}'";
	$insert = "
		insert into {$g5['eyoom_new']} set 
			bo_table	= '{$bo_table}',
			pr_id		= '{$respond['pr_id']}',
			wr_id		= '{$wr_id}',
			wr_parent	= '{$wr_id}',
			ca_name		= '{$ca_name}',
			wr_subject	= '{$wr_subject}',
			wr_content	= '{$content}',
			wr_option	= '{$html},{$secret},{$mail}',
			bn_datetime = '".G5_TIME_YMDHIS."',
			mb_id		= '{$member['mb_id']}',
			mb_name		= '{$member['mb_name']}',
			mb_nick		= '{$member['mb_nick']}',
			mb_level	= '{$wr_1}',
			wr_image	= '{$wr_image}',
			wr_video	= '{$wr_video}',
			wr_sound	= '{$wr_sound}',
			wr_comment	= '0',
			wr_hit		= '0',
			wr_1		= '{$wr_1}',
			wr_2		= '{$wr_2}',
			wr_3		= '{$wr_3}',
			wr_4		= '{$wr_4}',
			wr_5		= '{$wr_5}',
			wr_6		= '{$wr_6}',
			wr_7		= '{$wr_7}',
			wr_8		= '{$wr_8}',
			wr_9		= '{$wr_9}',
			wr_10		= '{$wr_10}'
	";

	$update = "
		update {$g5['eyoom_new']} set 
			bo_table	= '{$bo_table}',
			pr_id		= '{$respond['pr_id']}',
			wr_id		= '{$wr_id}',
			wr_parent	= '{$wr_id}',
			ca_name		= '{$ca_name}',
			wr_subject	= '{$wr_subject}',
			wr_content	= '{$content}',
			wr_option	= '{$html},{$secret},{$mail}',
			wr_image	= '{$wr_image}',
			wr_video	= '{$wr_video}',
			wr_sound	= '{$wr_sound}',
			wr_1		= '{$wr_1}',
			wr_2		= '{$wr_2}',
			wr_3		= '{$wr_3}',
			wr_4		= '{$wr_4}',
			wr_5		= '{$wr_5}',
			wr_6		= '{$wr_6}',
			wr_7		= '{$wr_7}',
			wr_8		= '{$wr_8}',
			wr_9		= '{$wr_9}',
			wr_10		= '{$wr_10}'
		where $where
	";

	// Eyoom 새글
	if ($w == '' || $w == 'r') {
		$query = $insert;
		// 나의활동 
		switch($w) {
			default  : $act_type = 'new'; $eb->level_point($levelset['write']); break;
			case 'r' : $act_type = 'reply'; $eb->level_point($levelset['reply']); break;
		}
		$act_contents = array();
		$act_contents['bo_table'] = $bo_table;
		$act_contents['bo_name'] = $board['bo_subject'];
		$act_contents['wr_id'] = $wr_id;
		$act_contents['subject'] = $wr_subject;
		$act_contents['content'] = $content;
		$eb->insert_activity($member['mb_id'],$act_type,$act_contents);

	} else if($w == 'u') {
		$new = sql_fetch("select bn_id from {$g5['eyoom_new']} where $where");
		$query = $new['bn_id'] ? $update:$insert;
	}
	if($query) sql_query($query, false);
	unset($query, $insert, $update);

	// 지뢰폭탄 포인트 심기
	if ($w == '' || $w == 'r') {
		if($eyoom_board['bo_bomb_point'] > 0 && $eyoom_board['bo_bomb_point_limit'] > 0 && $eyoom_board['bo_bomb_point_cnt'] > 0) {
			for($i=0;$i<$eyoom_board['bo_bomb_point_cnt'];$i++) {
				$bomb[$i] = $eb->random_num($eyoom_board['bo_bomb_point_limit']-1);
			}
			if(is_array($bomb)) {
				$bomb = serialize($bomb);
				sql_query("update $write_table set wr_2 = '{$bomb}' where wr_id='{$wr_id}'");
			}
		}
	}

	// 사용자 프로그램
	@include_once(EYOOM_USER_PATH.'/board/write_update.skin.php');

	// 무한스크롤 리스트에서 뷰창을 띄웠을 경우
	$qstr .= $wmode ? $qstr.'&wmode=1':'';

?>