<?php
 	if (!defined('_GNUBOARD_')) exit;

	// 게시판 이윰설정 링크생성
	$eyoom_href = "";
	// 최고관리자 또는 그룹관리자라면
	if ($member['mb_id'] && ($is_admin == 'super' || $group['gr_admin'] == $member['mb_id']))
		$eyoom_href = G5_ADMIN_URL.'/eyoom_admin/board_form.php?bo_table='.$bo_table.'&thema='.$theme;

	// 선택옵션으로 인해 셀합치기가 가변적으로 변함
	$colspan = 5;

	if ($is_checkbox) $colspan++;
	if ($is_good) $colspan++;
	if ($is_nogood) $colspan++;
	if ($eyoom_board['bo_use_profile_photo']) $colspan++;

	// 갤러리 스킨의 경우, 가로 이미지 갯수 자동처리
	if ($bo_gallery_cols && 12%$bo_gallery_cols == 0) {
		$cols = 12/$bo_gallery_cols;
	} else {
		$cols = 4;
	}

	// 제목에서 구분자로 회원정보 추출
	foreach($list as $key => $val) {
		$level = $list[$key]['wr_1'] ? $eb->level_info($list[$key]['wr_1']):'';
		if(is_array($level)) {
			if(!$level['anonymous']) {
				$list[$key]['mb_photo'] = $eb->mb_photo($list[$key]['mb_id']);
				$list[$key]['gnu_level'] = $level['gnu_level'];
				$list[$key]['eyoom_level'] = $level['eyoom_level'];
				$list[$key]['lv_gnu_name'] = $level['gnu_name'];
				$list[$key]['lv_name'] = $level['name'];
				$list[$key]['gnu_icon'] = $level['gnu_icon'];
				$list[$key]['eyoom_icon'] = $level['eyoom_icon'];
			} else {
				$list[$key]['mb_id'] = 'anonymous';
				$list[$key]['wr_name'] = '익명';
				$list[$key]['email'] = '';
				$list[$key]['homepage'] = '';
				$list[$key]['gnu_level'] = '';
				$list[$key]['gnu_icon'] = '';
				$list[$key]['eyoom_icon'] = '';
				$list[$key]['lv_gnu_name'] = '';
				$list[$key]['lv_name'] = '';
			}
		}

		if($board['bo_use_list_file']) {
			$thumb = get_list_thumbnail($board['bo_table'], $list[$key]['wr_id'], $board['bo_gallery_width'], $board['bo_gallery_height']);
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
		if($board['bo_use_list_content']) {
			$content_length = G5_IS_MOBILE ? 100:150;
			$wr_content = $list[$key]['wr_content'];
			$wr_content = $eb->remove_editor_code($wr_content);
			$wr_content = $eb->remove_editor_emoticon($wr_content);
			$wr_content = $eb->remove_editor_video($wr_content);
			$wr_content = $eb->remove_editor_sound($wr_content);
			$list[$key]['content'] = cut_str(trim(strip_tags(preg_replace("/\?/","",$wr_content))),$content_length, '…');
		}
		
		// 게시물 view페이지의 wmode(Window Mode) 설정
		if($_wmode) {
			$list[$key]['href'] = $list[$key]['href'].'&wmode=1';
		}
	}

	// 카테고리
	if ($board['bo_use_category']) {
		// 카테고리별 게시글수 출력 표시 - 비즈팔님이 아이디어를 제공해 주셨습니다.
		$res = sql_query("select distinct ca_name, count(*) as cnt from {$write_table} where wr_id = wr_parent group by ca_name",false);
		$ca_total=0;
		for($i=0;$row=sql_fetch_array($res);$i++) {
			$ca_name = $row['ca_name'] ? $row['ca_name'] : '미분류';
			$ca_count[$ca_name] = $row['cnt'];
			$ca_total += $row['cnt'];
		}

		// 카테고리 정보 재구성
		foreach($categories as $key => $val) {
			$bocate[$key]['ca_name'] = trim($val);
			$bocate[$key]['ca_sca'] = urlencode($bocate[$key]['ca_name']);
			$bocate[$key]['ca_count'] = number_format($ca_count[$val]);
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