<?php
include_once('./_common.php');

if($is_admin != 'super')
    alert('최고관리자로 로그인 후 실행해 주십시오.', G5_URL);

$g5['title'] = '이윰 DB 업그레이드';
include_once(G5_PATH.'/head.sub.php');

// 이윰 짧은주소 테이블 생성
$sql = "
	create table if not exists `g5_eyoom_link` (
	  `s_no` int(11) unsigned not null auto_increment,
	  `bo_table` varchar(20) collate utf8_unicode_ci not null,
	  `wr_id` int(11) unsigned not null default '0',
	  `theme` varchar(40) collate utf8_unicode_ci not null,
	  primary key  (`s_no`)
	) engine=myisam default charset=utf8
";
sql_query($sql, false);

echo '<p>이윰빌더 테이블 업그레이드 완료!</p>';

include_once(G5_PATH.'/tail.sub.php');

?>