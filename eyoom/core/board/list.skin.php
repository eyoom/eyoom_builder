<?php
 	if (!defined('_GNUBOARD_')) exit;

	// 선택옵션으로 인해 셀합치기가 가변적으로 변함
	$colspan = 5;

	if ($is_checkbox) $colspan++;
	if ($is_good) $colspan++;
	if ($is_nogood) $colspan++;
	if ($eyoom_board['bo_use_profile_photo']) $colspan++;

	// 제목에서 구분자로 회원정보 추출
	foreach($list as $key => $val) {
		$list[$key]['mb_photo'] = $eb->mb_photo($list[$key]['mb_id']);
		$level = $list[$key]['wr_1'] ? $eb->level_info($list[$key]['wr_1']):'';
		$list[$key]['gnu_level'] = $level['gnu_level'];
		$list[$key]['eyoom_level'] = $level['eyoom_level'];
		$list[$key]['lv_gnu_name'] = $level['gnu_name'];
		$list[$key]['lv_name'] = $level['name'];
		$list[$key]['gnu_icon'] = $level['gnu_icon'];
		$list[$key]['eyoom_icon'] = $level['eyoom_icon'];
		$list[$key]['grade_icon'] = $level['grade_icon'];

		$thumb = get_list_thumbnail($board['bo_table'], $list[$key]['wr_id'], $board['bo_gallery_width'], $board['bo_gallery_height']);
		if(!$thumb) {
			// 내용중의 첫번째 이미지 정보 가져오기
			$matches = get_editor_image(stripslashes($list[$key]['wr_content']),false);
			if($matches[1][0]) {
				$p = parse_url($matches[1][0]);
				$host = preg_replace("/www\./i","",$p['host']);
				$_host = preg_replace("/www\./i","",$_SERVER['HTTP_HOST']);

				$ex_url = '';
				if(!preg_match("/".$host."/i",$_host,$p['host'])) $ex_url = "http://".$host;
				$thumb['src'] = $ex_url . $p['path'];
			} else {
				$thumb['src'] = '';
			}
		}
		if($tpl_name == 'bs') {
			if($thumb['src']) {
				$list[$key]['img_content'] = '<img class="img-responsive" src="'.$thumb['src'].'" alt="'.$thumb['alt'].'">';
				$list[$key]['img_src'] = $thumb['src'];
			} else {
				$list[$key]['img_content'] = '<span style="width:100%;">no image</span>';
			}
		} else {
			if($thumb['src']) {
				$list[$key]['img_content'] = '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'" width="'.$board['bo_gallery_width'].'" height="'.$board['bo_gallery_height'].'">';
				$list[$key]['img_src'] = $thumb['src'];
			} else {
				$list[$key]['img_content'] = '<span style="width:'.$board['bo_gallery_width'].'px;height:'.$board['bo_gallery_height'].'px">no image</span>';
			}
		}
	}

	// 카테고리
	if ($board['bo_use_category']) {
		foreach($categories as $key => $val) {
			$bocate[$key]['ca_name'] = trim($val);
			$bocate[$key]['ca_sca'] = urlencode($bocate[$key]['ca_name']);
		}
		$decode_sca =urldecode($sca);
	}

	// Paging 
	$paging = $thema->pg_pages($tpl_name,"./board.php?bo_table=".$bo_table.$qstr."&amp;page=");

	// 사용자 프로그램
	@include_once(EYOOM_USER_PATH.'/board/list.skin.php');

	// Template define
	$tpl->define_template('board',$eyoom_board['bo_skin'],'list.skin.html');

	// Template assign
	@include EYOOM_INC_PATH.'/tpl.assign.php';
	$tpl->print_($tpl_name);

?>