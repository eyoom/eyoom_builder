<?php
	if (!defined('_GNUBOARD_')) exit;

	// 답변글에 대한 내글반응 적용하기
	if ($w == 'c') {
		if($reply_char) {
			$prev = sql_fetch(" select mb_id from {$write_table} where wr_id = '$_POST[comment_id]' and wr_is_comment = 1 and wr_comment_reply = '".substr($tmp_comment_reply,0,-1)."' ");
			$type = 'cmt_re';
			$pr_id = $_POST['comment_id'];
			$wr_mb_id = $prev['mb_id']; // 부모댓글 작성자 아이디
		} else {
			$type = 'cmt';
			$pr_id = $_POST['wr_id'];
			$wr_mb_id = $wr['mb_id']; // 부모글 작성자 아이디
		}

		$respond = array();
		$respond['type']		= $type;
		$respond['bo_table']	= $bo_table;
		$respond['pr_id']		= $pr_id;
		$respond['wr_id']		= $wr_id;
		$respond['wr_cmt']		= $comment_id;
		$respond['wr_subject']	= $wr_subject;
		$respond['wr_mb_id']	= $wr_mb_id;
		if($_POST['anonymous'] == 'y') $anonymous = true;
		$eb->respond($respond);
	}

	$wr_content = $eb->remove_editor_code($wr_content);
	$wr_content = $eb->remove_editor_emoticon($wr_content);

	$wr_video = $eb->get_editor_video($wr_content);
	$wr_video = serialize($wr_video[1]);
	$wr_sound = $eb->get_editor_sound($wr_content);
	$wr_sound = serialize($wr_sound[1]);

	$wr_content = $eb->remove_editor_video($wr_content);
	$wr_content = $eb->remove_editor_sound($wr_content);
	$wr_content = htmlspecialchars($wr_content);

	// Eyoom 새글에 등록
	if ($w == 'c') {
		// 원글관련 댓글수 증가
		sql_query(" update {$g5['eyoom_new']} set wr_comment = wr_comment + 1 where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' ");
		$query = "
			insert into {$g5['eyoom_new']} set 
				bo_table	= '{$bo_table}',
				pr_id		= '{$respond['pr_id']}',
				wr_id		= '{$comment_id}',
				wr_parent	= '{$wr_id}',
				ca_name		= '{$wr['ca_name']}',
				wr_content	= '{$wr_content}',
				wr_option	= '{$wr_secret}',
				bn_datetime = '".G5_TIME_YMDHIS."',
				mb_id		= '{$mb_id}',
				mb_name		= '{$member['mb_name']}',
				mb_nick		= '{$member['mb_nick']}',
				mb_level	= '{$wr_1}',
				wr_image	= '{$wr_image}',
				wr_video	= '{$wr_video}',
				wr_sound	= '{$wr_sound}'
		";

		// 나의 활동
		$act_contents = array();
		$act_contents['bo_table'] = $bo_table;
		$act_contents['bo_name'] = $board['bo_subject'];
		$act_contents['wr_id'] = $comment_id;
		$act_contents['wr_parent'] = $wr_id;
		$act_contents['content'] = $wr_content;
		$eb->insert_activity($mb_id,$type,$act_contents);
		$eb->level_point($levelset['cmt']);

		// 댓글 포인트
		if($eyoom_board['bo_firstcmt_point'] || $eyoom_board['bo_bomb_point'] || $eyoom_board['bo_lucky_point']) {
			$point = $eb->point_comment();
			if(is_array($point)) {
				$point = serialize($point);
				// 댓글의 경우 wr_link1을 사용하지 않기에 활용
				sql_query(" update $write_table set wr_link1 = '{$point}' where wr_id='{$comment_id}'");
			}
		}

	} else if($w == 'cu') {
		$query = "
			update {$g5['eyoom_new']} set 
				bo_table	= '{$bo_table}',
				pr_id		= '{$respond['pr_id']}',
				wr_id		= '{$comment_id}',
				wr_parent	= '{$wr_id}',
				ca_name		= '{$wr['ca_name']}',
				wr_content	= '{$wr_content}',
				wr_option	= '{$wr_secret}',
				mb_level	= '{$wr_1}',
				wr_image	= '{$wr_image}',
				wr_video	= '{$wr_video}',
				wr_sound	= '{$wr_sound}'
			where bo_table = '{$bo_table}' and wr_id = '{$comment_id}'
		";
	}
	if($query) sql_query($query);
	unset($query);

	// 사용자 프로그램
	@include_once(EYOOM_USER_PATH.'/board/write_comment_update.skin.php');

	if($goback) {
		delete_cache_latest($bo_table);
		$mb_photo = $eb->mb_photo($mb_id);
		$output['mb_nick'] = $member['mb_nick'];
		$output['mb_photo'] = $mb_photo;
		$output['datetime'] = G5_TIME_YMDHIS;
		include_once EYOOM_CLASS_PATH."/json.class.php";

		$json = new Services_JSON();
		$data = $json->encode($output);
		echo $data;
		exit;
	}

?>