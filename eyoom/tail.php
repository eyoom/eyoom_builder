<?php
	if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

	// 쇼핑몰 레이아웃을 커뮤니티에 적용하기
	if((isset($shop_layout_use) && $shop_layout_use)) {
		@include_once(EYOOM_CORE_PATH.'/shop/shop.tail.php');
		return;
	}

	// PC/모바일 링크 생성
	$href = $thema->get_href($tpl_name);

	// 사용자 프로그램
	@include_once(EYOOM_USER_PATH.'/tail.php');

	// 템플릿에 변수 할당
	@include EYOOM_INC_PATH.'/tpl.assign.php';

	// 푸시 템플릿
	$tpl->define(array(
		'push_pc'	=> 'skin_pc/push/' . $eyoom['push_skin'] . '/push.skin.html',
		'push_mo'	=> 'skin_mo/push/' . $eyoom['push_skin'] . '/push.skin.html',
		'push_bs'	=> 'skin_bs/push/' . $eyoom['push_skin'] . '/push.skin.html',
	));

	// 템플릿 출력
	$tpl_tail = 'tail_' . $tpl_name;
	$tpl->print_($tpl_tail);
?>