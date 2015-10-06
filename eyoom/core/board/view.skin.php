<?php
	if (!defined('_GNUBOARD_')) exit;

	include_once(G5_LIB_PATH.'/thumbnail.lib.php');
	include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

	// 글쓴이 정보를 가져옴
	if(!$mb) $mb = get_member($view['mb_id']);
	$user = $eb->get_user_info($mb['mb_id'])+$mb;
	$lvuser = $eb->user_level_info($user);

	// 읽는사람 포인트 주기 및 이윰뉴 테이블의 히트수/댓글수 일치 시키기
    $spv_name = 'spv_board_'.$bo_table.'_'.$wr_id;
    if (!get_session($spv_name)) {
		if($is_member) $eb->level_point($levelset['read']);
        set_session($spv_name, TRUE);

		// 이윰뉴 테이블에 wr_hit 적용
		$where = "wr_id = '{$wr_id}' ";
		$parent = sql_fetch("select wr_hit, wr_comment from {$write_table} where $where");
		sql_query("update {$g5['eyoom_new']} set wr_hit = '{$parent['wr_hit']}', wr_comment = '{$parent['wr_comment']}' where $where and bo_table='{$bo_table}'");
    }

	// 짤은주소 체크 및 생성
	if(!($short_url = $eb->get_short_url())) {
		$short_url = $eb->make_short_url();
	}

	// 첨부파일 정보 가져오기
	if ($view['file']['count']) {
		$cnt = 0;
		for ($i=0; $i<count($view['file']); $i++) {
			if (isset($view['file'][$i]['source']) && $view['file'][$i]['source']) {
				$view_file[$cnt] = $view['file'][$i];
				$cnt++;
			}
		}
	}

	// 링크 정보 가져오기
	$i=1;
	foreach($view['link'] as $k => $v) {
		if(!$v) break;
		$view_link[$i]['link']	= cut_str($view['link'][$i], 70);
		$view_link[$i]['href']	= $view['link_href'][$i];
		$view_link[$i]['hit']	= $view['link_hit'][$i];
		$i++;
	}

	// 파일 출력
	$v_img_count = count($view['file']);
	if($v_img_count) {
		$file_conts = "<div id=\"bo_v_img\">\n";

		for ($i=0; $i<=count($view['file']); $i++) {
			if ($view['file'][$i]['view']) {
				//echo $view['file'][$i]['view'];
				$file_conts .= get_view_thumbnail($view['file'][$i]['view']);
			}
		}
		$file_conts .= "</div>\n";
	}
	$view_content = $eb->eyoom_content($view['content']);
	
	// 작성자 레벨정보 가져오기	
	if($view['wr_1']) {
		$lv = $eb->level_info($view['wr_1']);
	} else {
		$lv['gnu_level'] = '';
		$lv['gnu_icon'] = '';
		$lv['eyoom_icon'] = '';
		$lv['gnu_name'] = '';
		$lv['name'] = '';
	}

	// 익명글 기능
	if(!$lv['anonymous']) {
		// 작성자 프로필 사진
		$view['mb_photo'] = $eb->mb_photo($view['mb_id']);
	} else {
		$view['mb_photo'] = '';
		$view['mb_id'] = 'anonymous';
		$view['wr_name'] = '익명';
		$view['wr_email'] = '';
		$view['wr_homepage'] = '';
	}

	// 추천/비추천
	if($member['mb_id']) {
		$goodinfo = $eb->mb_goodinfo($member['mb_id'],$bo_table,$wr_id);
	}

	// sns 버튼들
	if($board['bo_use_sns']) {
		$sns_msg = urlencode(str_replace('\"', '"', $view['subject']));

		$longurl = urlencode($short_url);
		$sns_send  = EYOOM_CORE_URL.'/board/sns_send.php?longurl='.$longurl;
		$sns_send .= '&amp;title='.$sns_msg;

		$facebook_url = $sns_send.'&amp;sns=facebook';
		$twitter_url  = $sns_send.'&amp;sns=twitter';
		$gplus_url    = $sns_send.'&amp;sns=gplus';
		$kakaostory_url   = $sns_send.'&amp;sns=kakaostory';
		$band_url   = $sns_send.'&amp;sns=band';
	}

	// Window Mode 사용시
	if($wmode) {
		// 목록 출력을 강제로 막음
		$board['bo_use_list_view'] = 0;

		// 일반 버튼들 wmode 적용하기
		$add_query = '&wmode=1';
		$prev_href .= $prev_href ? $add_query:'';
		$next_href .= $next_href ? $add_query:'';
		$update_href .= $update_href ? $add_query:'';
		$delete_href .= $delete_href ? $add_query:'';
		$copy_href .= $copy_href ? $add_query:'';
		$move_href .= $move_href ? $add_query:'';
		$search_href .= $search_href ? $add_query:'';
		$reply_href .= $reply_href ? $add_query:'';
		$write_href .= $write_href ? $add_query:'';
	}

	// wr_1에 작성자의 레벨정보 입력
	if($is_member) $wr_1 = $member['mb_level']."|".$eyoomer['level'];

	include_once(G5_BBS_PATH.'/view_comment.php');

	$tpl->define(array(
		'cmt_pc'	=> 'skin_pc/board/' . $eyoom_board['bo_skin'] . '/view_comment.skin.html',
		'cmt_mo'	=> 'skin_mo/board/' . $eyoom_board['bo_skin'] . '/view_comment.skin.html',
		'cmt_bs'	=> 'skin_bs/board/' . $eyoom_board['bo_skin'] . '/view_comment.skin.html',
		'sns_pc'	=> 'skin_pc/board/' . $eyoom_board['bo_skin'] . '/sns.skin.html',
		'sns_mo'	=> 'skin_mo/board/' . $eyoom_board['bo_skin'] . '/sns.skin.html',
		'sns_bs'	=> 'skin_bs/board/' . $eyoom_board['bo_skin'] . '/sns.skin.html',
		'signature_pc'	=> 'skin_pc/signature/' . $eyoom['signature_skin'] . '/signature.skin.html',
		'signature_mo'	=> 'skin_mo/signature/' . $eyoom['signature_skin'] . '/signature.skin.html',
		'signature_bs'	=> 'skin_bs/signature/' . $eyoom['signature_skin'] . '/signature.skin.html',
	));

	// 사용자 프로그램
	@include_once(EYOOM_USER_PATH.'/board/view.skin.php');

	// Template define
	$tpl->define_template('board',$eyoom_board['bo_skin'],'view.skin.html');

	// Template assign
	@include EYOOM_INC_PATH.'/tpl.assign.php';
	$tpl->print_($tpl_name);

?>