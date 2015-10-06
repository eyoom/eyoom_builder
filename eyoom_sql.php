<?php
include_once('./_common.php');

if($is_admin != 'super')
    alert('최고관리자로 로그인 후 실행해 주십시오.', G5_URL);

$g5['title'] = '이윰메뉴 테이블 업그레이드';
include_once(G5_PATH.'/head.sub.php');

// 이윰메뉴의 me_type 속성 변경
$sql = "ALTER TABLE `g5_eyoom_menu` CHANGE `me_type` `me_type` VARCHAR(30) NOT NULL;";
sql_query($sql, true);

// 이윰NEW 테이블에 추천필드 추가
if(!sql_query(" select wr_good from {$g5['eyoom_new']} limit 1 ", false)) {
	// 필드 추가
	sql_query("alter table `{$g5['eyoom_new']}` add `wr_good` int(11) NOT NULL default 0 after `wr_hit`", true);

	// 오리지널 테이블의 추천값을 이윰NEW 테이블에 적용
	$sql = "select bn_id, bo_table, wr_id from `{$g5['eyoom_new']}` where wr_id = wr_parent order by bn_datetime asc";
	$res = sql_query($sql, false);
	for($i=0;$row=sql_fetch_array($res);$i++) {
		$temp_table = $g5['write_prefix'].$row['bo_table'];
		sql_query("update `{$g5['eyoom_new']}` as a set a.wr_good=(select b.wr_good from {$temp_table} as b where b.wr_id='{$row['wr_id']}') where a.bn_id='{$row['bn_id']}'", false);
	}
}

// 이윰NEW 테이블에 여유필드 추가
if(!sql_query(" select wr_1 from {$g5['eyoom_new']} limit 1 ", false)) {
	$sql = "
		alter table  `g5_eyoom_new` 
		add `wr_1` varchar( 255 ) not null,
		add `wr_2` varchar( 255 ) not null,
		add `wr_3` varchar( 255 ) not null,
		add `wr_4` varchar( 255 ) not null,
		add `wr_5` varchar( 255 ) not null,
		add `wr_6` varchar( 255 ) not null,
		add `wr_7` varchar( 255 ) not null,
		add `wr_8` varchar( 255 ) not null,
		add `wr_9` varchar( 255 ) not null,
		add `wr_10` varchar( 255 ) not null
	";
	sql_query($sql, false);
}

echo '<p>이윰빌더 테이블 업그레이드 완료!</p>';

include_once(G5_PATH.'/tail.sub.php');

?>