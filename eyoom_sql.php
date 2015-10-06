<?php
include_once('./_common.php');

if($is_admin != 'super')
    alert('최고관리자로 로그인 후 실행해 주십시오.', G5_URL);

$g5['title'] = '이윰보드 테이블 업그레이드';
include_once(G5_PATH.'/head.sub.php');

// 이윰 New에 카테고리 분류명 기록 필드 추가
if(!sql_query(" select ca_name from {$g5['eyoom_new']} limit 1 ", false)) {
	$sql = "alter table `{$g5['eyoom_new']}` add `ca_name` varchar(255) NOT NULL after `wr_parent`";
    sql_query($sql, true);
}

// 이윰메뉴에 폰트어썸 입력 필드 추가
if(!sql_query(" select me_icon from {$g5['eyoom_menu']} limit 1 ", false)) {
	$sql = "alter table `{$g5['eyoom_menu']}` add `me_icon` varchar(100) NOT NULL after `me_name`";
    sql_query($sql, true);
}

echo '<p>이윰빌더 테이블 업그레이드 완료!</p>';

include_once(G5_PATH.'/tail.sub.php');

?>