<?php 
	if (!defined('_GNUBOARD_')) exit;

	// 그룹정보 가져오기
	$sel_group = $eb->get_group();

	$k=0;
	for ($idx=$table_index, $k=0; $idx<count($search_table) && $k<$rows; $idx++) {
		$loop1[$idx]['bo_table'] = $search_table[$idx];
		$loop1[$idx]['bo_subject'] = $bo_subject[$idx];
		$loop2 = &$loop1[$idx]['list'];

		for ($i=0; $i<count($list[$idx]) && $k<$rows; $i++, $k++) {
			if ($list[$idx][$i]['wr_is_comment'])
			{
				$comment_def = '<span class="cmt_def">댓글 | </span>';
				$comment_href = '#c_'.$list[$idx][$i]['wr_id'];
			}
			else
			{
				$comment_def = '';
				$comment_href = '';
			}

			$data['href'] = $list[$idx][$i]['href'];
			$data['subject'] = $list[$idx][$i]['subject'];
			$data['content'] = $list[$idx][$i]['content'];
			$data['name'] = $list[$idx][$i]['name'];
			$data['wr_datetime'] = $list[$idx][$i]['wr_datetime'];
			$data['comment_def'] = $comment_def;
			$data['comment_href'] = $comment_href;
			$loop2[$i] = $data;
		}
	}

	//var_dump($str_board_list);

	// Paging 
	$paging = $thema->pg_pages($tpl_name,$_SERVER['PHP_SELF'].'?'.$search_query.'&amp;gr_id='.$gr_id.'&amp;srows='.$srows.'&amp;onetable='.$onetable.'&amp;page=');

	// 사용자 프로그램
	@include_once(EYOOM_USER_PATH.'/search/search.skin.php');

	// Template define
	$tpl->define_template('search',$eyoom['search_skin'],'search.skin.html');

	// Template assign
	@include EYOOM_INC_PATH.'/tpl.assign.php';
	$tpl->print_($tpl_name);

?>