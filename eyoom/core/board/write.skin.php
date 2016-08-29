<?php
 	if (!defined('_GNUBOARD_')) exit;

	$uid = get_uniqid();

	// summernote는 모바일에서 사용가능 하기에 editor 재설정
	if(G5_IS_MOBILE && $config['cf_editor'] == 'summernote' && $eyoom_board['bo_use_summernote_mo'] == '1') {
		$is_dhtml_editor = false;
		$is_dhtml_editor_use = true;
		
		// 모바일에서는 G5_IS_MOBILE_DHTML_USE 설정에 따라 DHTML 에디터 적용
		if ($config['cf_editor'] && $is_dhtml_editor_use && $board['bo_use_dhtml_editor'] && $member['mb_level'] >= $board['bo_html_level']) {
		    $is_dhtml_editor = true;
		
		    if(is_file(G5_EDITOR_PATH.'/'.$config['cf_editor'].'/autosave.editor.js'))
		        $editor_content_js = '<script src="'.G5_EDITOR_URL.'/'.$config['cf_editor'].'/autosave.editor.js"></script>'.PHP_EOL;
		}
		$editor_html = editor_html('wr_content', $content, $is_dhtml_editor);
		$editor_js = '';
		$editor_js .= get_editor_js('wr_content', $is_dhtml_editor);
		$editor_js .= chk_editor_js('wr_content', $is_dhtml_editor);
	}
	
	// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
	if($eyoom_board['bo_use_addon_map'] == '1') {
		add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
	}

	// wr_1에 작성자의 레벨정보 입력
	if($is_member) {
		if($w==''||$w=='r') {
			$wr_1 = $member['mb_level']."|".$eyoomer['level'];
		} else if($w=='u' && $wr_1 && $is_anonymous) {
			list($gnu_level,$eyoom_level,$anonymous) = explode('|',$wr_1);
			$wr_1 = $gnu_level."|".$eyoom_level;
			if($anonymous == 'y') {
				$anonymous_checked = 'checked="checked"';
			}
		}
	}
	
	// wr_4 변수값 암호화
	$wr_4 = $eb->encrypt_md5($wr_4);

	for ($i=1; $is_link && $i<=G5_LINK_COUNT; $i++) {
		$wr_link[$i]['link_val'] = $write['wr_link'.$i]; 
	}

	// $file 변수 중복 제거 후, 첨부파일 갯수 세팅
	$wr_file = array();
	if(in_array($file, $eb->get_subdir_filename(G5_EXTEND_PATH))) unset($file);
	for ($i=0; $is_file && $i<$file_count; $i++) {
		$wr_file[$i]['file'] = $file[$i]['file'];
		$wr_file[$i]['size'] = $file[$i]['size'];
		$wr_file[$i]['source'] = $file[$i]['source'];
		$wr_file[$i]['bf_content'] = $file[$i]['bf_content'];
	}
	
	// 태그 정보
	if ($eyoom['use_tag'] == 'y' && $eyoom_board['bo_use_tag'] == '1' && $member['mb_level'] >= $eyoom_board['bo_tag_level']) {
		$tag_info = $eb->get_tag_info($bo_table, $wr_id);
		if($tag_info['wr_tag']) {
			$write['wr_tag'] = $tag_info['wr_tag'];
			$wr_tags = explode(',', $tag_info['wr_tag']);
		}
		if(isset($wr_tags)) $tpl->assign('wr_tags', $wr_tags);
	}

	// 사용자 프로그램
	@include_once(EYOOM_USER_PATH.'/board/write.skin.php');

	// Template define
	$tpl->define_template('board',$eyoom_board['bo_skin'],'write.skin.html');

	// Template assign
	@include EYOOM_INC_PATH.'/tpl.assign.php';
	$tpl->print_($tpl_name);

?>