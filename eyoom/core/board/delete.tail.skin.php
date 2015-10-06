<?php
	if (!defined('_GNUBOARD_')) exit;

	// 답글에 대한 내글반응 삭제 부분은 복잡한 관계로 다음 버전에서...

	// 이윰 새글에서 삭제
	sql_query(" delete from {$g5['eyoom_new']} where bo_table = '$bo_table' and wr_parent = '{$write['wr_id']}' ");
	
	// 사용자 프로그램
	@include_once(EYOOM_USER_PATH.'/board/delete.tail.skin.php');

	// 무한스크롤 모달창 닫기
	if($wmode) {
		delete_cache_latest($bo_table);
		echo "
		<script>window.parent.closeModal('{$write['wr_id']}');</script>
		";
		exit;
	}

?>