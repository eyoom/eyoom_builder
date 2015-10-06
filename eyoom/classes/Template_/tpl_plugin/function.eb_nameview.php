<?php

// 회원 레이어
function eb_nameview($skin_dir, $mb_id, $name='', $email='', $homepage='')
{
    global $config;
    global $g5;
    global $bo_table, $sca, $is_admin, $member, $tpl_name, $tpl, $eyoomer;

	$following = $eyoomer['following'];
	if(!$following) $following = array();
	if(in_array($mb_id, $following)) {
		$follow = true;
	}

    $email = base64_encode($email);
    $homepage = set_http(clean_xss_tags($homepage));

    $name = preg_replace("/\&#039;/", "", $name);
    $name = preg_replace("/\'/", "", $name);
    $name = preg_replace("/\"/", "&#034;", $name);
	$name = get_text(cut_str($name, $config['cf_cut_name']));
    $title_name = $name;

    $tmp_name = "";
    if ($mb_id) {
        $head['link'] = G5_BBS_URL.'/profile.php?mb_id='.$mb_id;

        if ($config['cf_use_member_icon']) {
            $mb_dir = substr($mb_id,0,2);
            $icon_file = G5_DATA_PATH.'/member/'.$mb_dir.'/'.$mb_id.'.gif';

            if (file_exists($icon_file)) {
                $width = $config['cf_member_icon_width'];
                $height = $config['cf_member_icon_height'];
                $icon_file_url = G5_DATA_URL.'/member/'.$mb_dir.'/'.$mb_id.'.gif';
                $tmp_name .= '<img src="'.$icon_file_url.'" width="'.$width.'" height="'.$height.'" alt="">';

                if ($config['cf_use_member_icon'] == 2) // 회원아이콘+이름
                    $tmp_name = $tmp_name.' '.$name;
            } else {
                  $tmp_name = $tmp_name." ".$name;
            }
        } else {
            $tmp_name = $tmp_name.' '.$name;
        }
		$head['name'] = $tmp_name;
		$head['title'] = '['.$mb_id.']';
    } else {
        if(!$bo_table)
            return $name;

		$head['link'] = G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;sca='.$sca.'&amp;sfl=wr_name,1&amp;stx='.$name;
		$head['name'] = $name;
		$head['title'] = '[비회원]';
    }

    $name     = get_text($name);
    $email    = get_text($email);
    $homepage = get_text($homepage);

	if($mb_id) {
		$link['memo'] = G5_BBS_URL."/memo_form.php?me_recv_mb_id=".$mb_id;
		$link['profile'] = G5_BBS_URL."/profile.php?mb_id=".$mb_id;
		$link['article'] = G5_BBS_URL."/new.php?mb_id=".$mb_id;
		$link['userpage'] = G5_URL."/?".$mb_id;
	}
	if($email) $link['email'] = G5_BBS_URL."/formmail.php?mb_id=".$mb_id."&amp;name=".urlencode($name)."&amp;email=".$email;
	if($homepage) $link['home'] = $homepage;
	if($bo_table) {
		if($mb_id) $link['sid'] = G5_BBS_URL."/board.php?bo_table=".$bo_table."&amp;sca=".$sca."&amp;sfl=mb_id,1&amp;stx=".$mb_id;
		else $link['sname'] = G5_BBS_URL."/board.php?bo_table=".$bo_table."&amp;sca=".$sca."&amp;sfl=wr_name,1&amp;stx=".$name;
	}
    if($g5['sms5_use_sideview']){
        $mb = get_member($mb_id, " mb_open, mb_sms , mb_hp ");
        if( $mb['mb_open'] && $mb['mb_sms'] && $mb['mb_hp'] ){
			$link['sms'] = G5_SMS5_URL."/?mb_id=".$mb_id;
		}
    }
	if($is_admin == "super" && $mb_id) {
		$link['info'] = G5_ADMIN_URL."/member_form.php?w=u&amp;mb_id=".$mb_id;
		$link['point'] = G5_ADMIN_URL."/point_list.php?sfl=mb_id&amp;stx=".$mb_id;
	}

	$tpl->define(array(
		'pc' => 'skin_pc/nameview/' . $skin_dir . '/nameview.skin.html',
		'mo' => 'skin_mo/nameview/' . $skin_dir . '/nameview.skin.html',
		'bs' => 'skin_bs/nameview/' . $skin_dir . '/nameview.skin.html',
	));
	$tpl->assign(array(
		"head" => $head,
		"link" => $link,
		"mb_id" => $mb_id,
		"email" => $email,
		"home" => $homepage,
		"bo_table" => $bo_table,
		"g5" => $g5,
		"is_admin" => $is_admin,
		"follow" => $follow,
	));
	$tpl->print_($tpl_name);
}