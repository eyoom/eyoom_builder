<?php
include_once('./_common.php');

if($is_admin != 'super')
    alert('최고관리자로 로그인 후 실행해 주십시오.', G5_URL);

$g5['title'] = '이윰 DB 업그레이드';
include_once(G5_PATH.'/head.sub.php');

// 이윰메뉴에 사이드 레이아웃 표시 여부 필드 추가
if(!sql_query(" select me_side from {$g5['eyoom_menu']} limit 1 ", false)) {
	$sql = "alter table `{$g5['eyoom_menu']}` add `me_side` enum('y', 'n') not null default 'y' after `me_order`";
    sql_query($sql, true);
}

// basic 테마에 환경설정 항목 추가
$eyoom_keys = array(
	"theme",
	"theme_selector",
	"bootstrap",
	"outlogin_skin",
	"connect_skin",
	"popular_skin",
	"poll_skin",
	"visit_skin",
	"new_skin",
	"member_skin",
	"faq_skin",
	"qa_skin",
	"search_skin",
	"shop_skin",
	"newwin_skin",
	"mypage_skin",
	"signature_skin",
	"respond_skin",
	"push_skin",
	"board_skin",
	"emoticon_skin",
	"use_gnu_outlogin",
	"use_gnu_connect",
	"use_gnu_popular",
	"use_gnu_poll",
	"use_gnu_visit",
	"use_gnu_new",
	"use_gnu_member",
	"use_gnu_faq",
	"use_gnu_qa",
	"use_gnu_search",
	"use_gnu_shop",
	"use_gnu_newwin",
	"use_eyoom_menu",
	"use_sideview",
	"use_switcher",
	"use_main_side_layout",
	"use_sub_side_layout",
	"pos_side_layout",
	"level_icon_gnu",
	"use_level_icon_gnu",
	"level_icon_eyoom",
	"use_level_icon_eyoom",
	"push_reaction",
	"push_sound",
	"push_time",
	"photo_width",
	"photo_height",
	"cover_width",
	"cover_height",
);

foreach($eyoom_keys as $key) {
	if($key == 'use_main_side_layout' && !$eyoom[$key]) $eyoom[$key] = 'y';
	if($key == 'use_sub_side_layout' && !$eyoom[$key]) $eyoom[$key] = 'y';
	if($key == 'pos_side_layout' && !$eyoom[$key]) $eyoom[$key] = 'right';
	$eyoom_config[$key] = $eyoom[$key];
}
$qfile->save_file('eyoom',eyoom_config,$eyoom_config);

echo '<p>이윰빌더 테이블 업그레이드 완료!</p>';

include_once(G5_PATH.'/tail.sub.php');

?>