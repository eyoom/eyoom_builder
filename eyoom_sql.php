<?php
include_once('./_common.php');

if($is_admin != 'super')
    alert('최고관리자로 로그인 후 실행해 주십시오.', G5_URL);

$g5['title'] = '이윰 DB 업그레이드';
include_once(G5_PATH.'/head.sub.php');

// 이윰보드에 무한스크롤 사용여부 필드 추가
if(!sql_query(" select bo_use_infinite_scroll from {$g5['eyoom_board']} limit 1 ", false)) {
	$sql = "alter table `{$g5['eyoom_board']}` add `bo_use_infinite_scroll` char(1) not null default '2' after `bo_use_anonymous`";
    sql_query($sql, true);
}

echo '<p>이윰빌더 테이블 업그레이드 완료!</p>';

include_once(G5_PATH.'/tail.sub.php');

?>