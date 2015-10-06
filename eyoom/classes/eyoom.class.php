<?php
class eyoom extends qfile
{
	protected	$tpl_name;
	protected	$member_path;
	protected	$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	public function __construct() {
		global $eyoom;
		$this->member_path = G5_DATA_PATH . '/member';
		if($eyoom) $this->eyoom = $eyoom;
	}

	// 랜덤
	public function random_num($max_num) {
		mt_srand ((double) microtime() * 1000000);
		$num = mt_rand(0, $max_num); 
		return $num; 
	}

	// 메인페이지 설정
	public function print_page($target) {
		global $g5, $tpl, $tpl_name, $eyoom, $member, $user, $eyoomer, $eb, $config, $levelset, $is_admin, $is_member;

		if(count($_GET) > 0 && !$_GET['theme']) {
			// 마이홈 주소 체계 - /?user_id&permit_string
			$permit = array('page','following','follower','friends','guest');
			$index = false; $i=0;
			foreach($_GET as $k => $v) {
				if($i==0) { $dummy_id = $k; $i++; continue; } // 첫번째 변수는 dummy_id 
				if(!in_array($k,$permit)) { 
					$index = true; // 허용하지 않은 키값은 무시하고 기본 홈으로
					break;
				} else {
					if($v && $k=='page') ${$k} = (int)$v;
					else $userpage = $k;
				}
				if($i==2) break; // GET변수는 3개까지만 허용
				$i++;
			}
			if($index || $dummy_id == 'home' || $dummy_id == 'auto_login' || $dummy_id == 'device') {
				// 홈으로 이동
				$this->go_index_page();
			} else {
				include_once(G5_LIB_PATH.'/register.lib.php');

				// 사용자 아이디 유효성 체크
				if(empty_mb_id($dummy_id)) { $this->go_index_page(); exit; }
				if(valid_mb_id($dummy_id)) { $this->go_index_page(); exit; }
				if(count_mb_id($dummy_id)) { $this->go_index_page(); exit; }
				if(exist_mb_id($dummy_id)) {
					$user = $this->get_user_info($dummy_id);

					// 공개여부, 비회원여부, 공개하지 않았으나 마이홈으로 이동일 경우 등 
					if($user['open_page']=='y' || ($user['mb_id'] == $member['mb_id'] && $user['mb_id']) ) {
						include_once(EYOOM_CORE_PATH.'/mypage/myhome.php');
						$tpl->define_template('mypage',$eyoom['mypage_skin'],'myhome.skin.html');
						$tpl_index = $tpl_name;
					} else {
						$msg = "회원이 아니거나 마이홈을 공개하지 않은 회원입니다.";
						alert($msg, G5_URL);
					}
				}
			}
		} else {
			switch($target) {
				case 'index':
					$tpl_index = 'index_'.$tpl_name;
					break;
				case 'mypage':
					if(!$member['mb_id']) break;
					include_once(EYOOM_CORE_PATH.'/mypage/mypage.php');
					break;
				case 'myhome':
					if(!$member['mb_id']) break;
					$user = $eyoomer;
					include_once(EYOOM_CORE_PATH.'/mypage/myhome.php');
					break;
				default:
					$tpl_index = 'index_'.$tpl_name;
					break;
			}
			if(!$tpl_index) $tpl_index = 'index_'.$tpl_name;

			// 마이페이지, 마이홈 중복출력 방지
			if($target == 'index' || $target == '') {
				$tpl->print_($tpl_index);
			}
		}
	}

	// 기본홈 
	private function go_index_page() {
		global $tpl, $tpl_name;
		$tpl_index = 'index_'.$tpl_name;
		$tpl->print_($tpl_index);
	}

	// 읽지 않은 쪽지수
	public function get_memo($mb_id) {
		global $g5;

        $sql = " select count(*) as cnt from {$g5['memo_table']} where me_recv_mb_id = '{$mb_id}' and me_read_datetime = '0000-00-00 00:00:00' ";
        $row = sql_fetch($sql);
        return $row['cnt'];
	}

	// 이윰보드 설정정보
	public function eyoom_board_info($bo_table, $theme) {
		global $g5;
		$sql = "select a.*,b.bo_subject,c.gr_subject from {$g5['eyoom_board']} as a left join {$g5['board_table']} as b on a.bo_table = b.bo_table left join {$g5['group_table']} as c on b.gr_id = c.gr_id where a.bo_table='{$bo_table}' and a.bo_theme='{$theme}'";
		$board_info = sql_fetch($sql,false);
		return sql_fetch($sql);
	}

	// 내글반응 - 내글반응 등록 및 업데이트
	public function respond($respond = array()) {
		global $g5, $member, $anonymous;

		if(!is_array($respond)) return;
		foreach($respond as $key => $val) {
			if(!$val) return;
			${$key} = $val;
		}
		if($wr_mb_id == $member['mb_id']) return;

		// 익명글
		if(!$anonymous) {
			$mb_id = $member['mb_id'];
			$mb_nick = $member['mb_nick'];
		} else {
			$mb_id = 'anonymous';
			$mb_nick = '익명';
		}

		$set = "
			bo_table	= '$bo_table',
			pr_id		= '$pr_id',
			wr_id		= '$wr_id',
			wr_cmt		= '$wr_cmt',
			wr_mb_id	= '$wr_mb_id',
			mb_id		= '" . $mb_id . "',
			mb_name		= '" . $mb_nick . "',
			re_type		= '$type',
			wr_subject	= '" . addslashes(get_text($wr_subject)) . "',
		";
		$where = "
			wr_mb_id = '$wr_mb_id' and 
			bo_table = '$bo_table' and 
			pr_id = '$pr_id' and 
			re_type = '$type'
		";

		// 열람하지 않은 내글반응이 이미 있는지 체크
		$row = sql_fetch(" select rid from {$g5['eyoom_respond']} where $where and re_chk <> '1' order by rid desc ", false);
		$rid = $row['rid'];

		if($rid) {
			// 열람하지 않은 내글반응이 이미 있을 경우, 카운트만 올림
			sql_query("update {$g5['eyoom_respond']} set re_cnt=re_cnt+1, regdt='".G5_TIME_YMDHIS."' where rid='{$rid}'", false);
		} else {
			// 내글 반응 등록
			$insert = " insert into {$g5['eyoom_respond']} set $set regdt = '".G5_TIME_YMDHIS."' ";
			sql_query($insert, false);
			$rid = mysql_insert_id();

			// 원본글 작성자의 반응글 적용
			$row = sql_fetch("select mb_id from {$g5['eyoom_member']} where mb_id = '{$wr_mb_id}'", false);
			if($row['mb_id']) {
				sql_query(" update {$g5['eyoom_member']} set respond = respond + 1 where mb_id = '{$wr_mb_id}' ", false);
			} else {
				sql_query(" insert into {$g5['eyoom_member']} set mb_id = '{$wr_mb_id}', respond=1", false);
			}
		}

		// 푸시등록
		$user = sql_fetch("select onoff_push_respond from {$g5['eyoom_member']} where mb_id = '{$wr_mb_id}'");
		if($user['onoff_push_respond'] == 'on') $this->set_push("respond",$rid,$wr_mb_id,$mb_nick,$type);

	}

	// 내글반응의 종류에 따라 출력될 메세지 결정
	public function respond_mention($type,$name,$cnt) {
		switch($type) {
			case 'reply'	: 
				$reinfo['type'] = '답글';
				$reinfo['mention'] = $cnt > 0 ?  "<b>".$name."</b>님외 <b>".$cnt."</b>개의 답글이 내글에 달렸습니다." : "<b>".$name."</b>님이 내글에 답글을 남겼습니다.";
				break;
			case 'good'		: 
				$reinfo['type'] = '추천';
				$reinfo['mention'] = $cnt > 0 ?  "<b>".$name."</b>님외 <b>".$cnt."</b>명이 내글을 추천하였습니다." : "<b>".$name."</b>님이 내글을 추천하였습니다.";
				break;
			case 'nogood'	:
				$reinfo['type'] = '비추천';
				$reinfo['mention'] = $cnt > 0 ?  "<b>".$name."</b>님외 <b>".$cnt."</b>명이 내글을 비추천하였습니다." : "<b>".$name."</b>님이 내글을 비추천하였습니다.";
				break;
			case 'cmt'		:
				$reinfo['type'] = '댓글';
				$reinfo['mention'] = $cnt > 0 ?  "<b>".$name."</b>님외 <b>".$cnt."</b>개의 댓글이 내글에 달렸습니다." : "<b>".$name."</b>님이 내글에 댓글을 남겼습니다.";
				break;
			case 'cmt_re':
				$reinfo['type'] = '대댓글';
				$reinfo['mention'] = $cnt > 0 ?  "<b>".$name."</b>님외 <b>".$cnt."</b>개의 대댓글이 내댓글에 달렸습니다." : "<b>".$name."</b>님이 내댓글에 대댓글을 남겼습니다.";
				break;
			case 'goodcmt'	:
				$reinfo['type'] = '댓글공감';
				$reinfo['mention'] = $cnt > 0 ?  "<b>".$name."</b>님외 <b>".$cnt."</b>명이 내댓글에 공감합니다." : "<b>".$name."</b>님이 내댓글을 공감하였습니다.";
				break;
			case 'nogoodcmt'	:
				$reinfo['type'] = '댓글비공감';
				$reinfo['mention'] = $cnt > 0 ?  "<b>".$name."</b>님외 <b>".$cnt."</b>명이 내댓글에 비공감합니다." : "<b>".$name."</b>님이 내댓글을 비공감하였습니다.";
				break;
		}
		return $reinfo;
	}

	// 호스트명 추출
	public function eyoom_host($url='') {
		if(!$url) $url = G5_URL;
		$info = parse_url($url);
		if($info['query']) parse_str($info['query'], $query);
		$info['host'] = preg_replace("/www\./i","",$info['host']);
		$info['query'] = $query;
		return $info;
	}

	// 그누보드5/영카트5 루트폴더
	public function g5_root($path) {
		$path = str_replace('\\', '/', $path);
		$tilde_remove = preg_replace('/^\/\~[^\/]+(.*)$/', '$1', $_SERVER['SCRIPT_NAME']);
		$document_root = str_replace($tilde_remove, '', $_SERVER['SCRIPT_FILENAME']);
		$output = str_replace($document_root, '', $path);
		$output = str_replace('extend', '', $output);
		return $output;
	}

	// 푸쉬 생성
	public function set_push($item,$val,$target_id,$mb_name,$re_type='') {
		$push_file = $this->member_path.'/push/push.'.$target_id.'.php';
		$push[$item]['val'] = $val;
		$push[$item]['nick'] = $mb_name;
		$push[$item]['type'] = $re_type;
		$this->save_file('push',$push_file,$push);
	}

	// 회원 프로필 사진
	public function mb_photo($mb_id,$photo_filename='') {
		$photo = '';
		$dest_path = G5_DATA_PATH.'/member/profile/';
		$dest_url = G5_DATA_URL.'/member/profile/';
		$permit = array('jpg','gif','png');
		if($photo_filename) {
			$photo_file = $dest_path.$photo_filename;
			if(file_exists($photo_file)) {
				$photo = '<img class="user-photo" src="'.$dest_url.$photo_filename.'">';
			}
		} else {
			foreach($permit as $val) {
				$photo_name = $mb_id.'.'.$val;
				$photo_file = $dest_path.$photo_name;

				// 사진이 있다면 변수 넘김
				if(file_exists($photo_file)) {
					$photo = '<img class="user-photo" src="'.$dest_url.$photo_name.'">';
					break;
				}
			}
		}
		return $photo;
	}

	// 회원 마이홈 커버이미지
	private function myhome_cover($mb_id,$photo_filename='') {
		$photo = '';
		$dest_path = G5_DATA_PATH.'/member/cover/';
		$dest_url = G5_DATA_URL.'/member/cover/';
		$permit = array('jpg','gif','png');
		if($photo_filename) {
			$photo_file = $dest_path.$photo_filename;
			if(file_exists($photo_file)) {
				$photo = '<img src="'.$dest_url.$photo_filename.'">';
			}
		} else return false;
		return $photo;
	}

	// 현재 접속자 정보
	public function get_connect() {
		global $config, $g5;

		// 회원, 방문객 카운트
		$sql = " select sum(IF(mb_id<>'',1,0)) as mb_cnt, count(*) as total_cnt from {$g5['login_table']}  where mb_id <> '{$config['cf_admin']}' ";
		$connect = sql_fetch($sql);
		return $connect;
	}

	// 게시판 그룹정보
	public function get_group() {
		global $g5;
		$sql = " select gr_id, gr_subject from {$g5['group_table']} order by gr_id ";
		$result = sql_query($sql);
		for ($i=0; $row=sql_fetch_array($result); $i++) {
			$group[$i]['gr_id']		 = $row['gr_id'];
			$group[$i]['gr_subject'] = $row['gr_subject'];
		}
		if($group)	return $group; else return false;
	}

	// 전체 게시판 정보
	public function get_bo_subject() {
		global $g5;
		$sql = "select a.bo_table, a.bo_subject, b.gr_subject from {$g5['board_table']} as a left join {$g5['group_table']} as b on a.gr_id = b.gr_id where 1 order by b.gr_subject asc, a.bo_subject asc";
		$res = sql_query($sql, false);
		for($i=0; $row=sql_fetch_array($res);$i++) {
			$bo_name[$row['bo_table']]['gr_name'] = $row['gr_subject'];
			$bo_name[$row['bo_table']]['bo_name'] = $row['bo_subject'];
		}
		return $bo_name;
	}

	// 나의 활동 기록
	public function insert_activity($mb_id, $type, $content) {
		global $g5;
		$act_content = serialize($content);
		$sql = "
			insert into {$g5['eyoom_activity']} set 
				mb_id = '{$mb_id}',
				act_type = '{$type}',
				act_contents = '{$act_content}',
				act_regdt = '".G5_TIME_YMDHIS."'
		";
		sql_query($sql, false);
	}

	// date 함수를 이용한 날짜 표시
	public function date_format($format,$date) {
		// $time : 예) YYYY-mm-dd HH:ii:ss
		// $format : 예) Y-m-d H:i:s
		$time = strtotime($date);
		return date($format,$time);
	}

	public function date_time($format, $date) {
		$time = strtotime($date);
		$time_gap = time() - $time;
		if($time_gap < 60) return $time_gap.'초전';
		else if ($time_gap < 3600) return round($time_gap/60).'분전';
		else if ($time_gap < 86400) {
			$minute = round(($time_gap%3600)/60);
			return round($time_gap/3600).'시간 '.$minute.'분전';
		}
		else return date($format,$time);
	}

	// 회원정보 또는 유저정보 가져오기
	public function get_user_info($mb_id='') {
		global $g5;

		if(!$mb_id) return false;
		$single = false;
		if(is_array($mb_id)) {
			$where = "find_in_set(a.mb_id,'".implode(',',$mb_id)."')";
		} else {
			$where = "a.mb_id = '{$mb_id}'";
			$single = true;
		}
		$fields = "a.mb_nick, a.mb_name, b.level, a.mb_email, a.mb_homepage, a.mb_tel, a.mb_hp, a.mb_point, a.mb_datetime, a.mb_signature, a.mb_profile, b.* ";
		$sql_common = " from {$g5['member_table']} as a	left join {$g5['eyoom_member']} as b on a.mb_id = b.mb_id ";
		$sql = "select " . $fields . $sql_common . ' where ' . $where . ' order by a.mb_today_login desc';

		if($single) {
			$user = sql_fetch($sql, false);
			if($user['mb_id']) {
				$user['mb_photo'] = $this->mb_photo($user['mb_id'],$user['photo']);
				$user['wallpaper'] = $this->myhome_cover($user['mb_id'],$user['myhome_cover']);
				$snsinfo = $this->get_sns_info($user['following'],$user['follower'],$user['likes']);
				$userinfo = $snsinfo + $user;
				return $userinfo;
			} else {
				// 이윰 멤버로 등록이 안되어 있다면 등록 후, 등록한 정보를 넘겨줌
				$insert = "insert into {$g5['eyoom_member']} set mb_id = '{$mb_id}'";
				sql_query($insert, false);
				return $this->get_user_info($mb_id);
			}
		} else {
			$res = sql_query($sql, false);
			for($i=0;$row=sql_fetch_array($res);$i++) {
				$_following	= unserialize($row['following']);
				$_follower	= unserialize($row['follower']);
				$snsinfo[$i] = $row;
				$snsinfo[$i]['mb_photo'] = $this->mb_photo($row['mb_id'],$row['photo']);
				$snsinfo[$i]['following'] = $_following ? count($_following):0;
				$snsinfo[$i]['follower'] = $_follower ? count($_follower):0;
			}
			return $snsinfo;
		}
	}
	
	// 소셜 정보
	private function get_sns_info($following, $follower, $likes) {
		$_following	= unserialize($following);
		$_follower	= unserialize($follower);
		$_likes		= unserialize($likes);
		$_friends	= is_array($_following) && is_array($_follower) ? array_intersect($_following,$_follower):array();
	
		$user['cnt_following']	= $_following ? count($_following):0;
		$user['cnt_follower']	= $_follower ? count($_follower):0;
		$user['cnt_friends']	= $_friends ? count($_friends):0;
		$user['cnt_likes']		= $_likes ? count($_likes):0;
		$user['following']		= $_following;
		$user['follower']		= $_follower;
		$user['friends']		= $_friends;
		$user['likes']			= $_likes;
		return $user;
	}

	// 레벨 포인트
	public function level_point($point,$r_id='',$r_point=0) {
		global $g5, $eyoomer, $is_admin;
		if($point) {
			$level_point = $eyoomer['level_point'];
			$point_sum = $level_point + $point;
			$level = $this->get_level_from_point($point_sum,$eyoomer['level']);

			$sql = "update {$g5['eyoom_member']} set level='{$level}', level_point='{$point_sum}' where mb_id='{$eyoomer['mb_id']}'";
			sql_query($sql, false);

			if($r_id) {
				$sql = "update {$g5['eyoom_member']} set level_point=level_point+".$r_point." where mb_id='{$r_id}'";
				sql_query($sql, false);
			}
		} else return false;
	}

	// 그누레벨 자동업/다운
	public function set_gnu_level($level) {
		global $g5, $member;
		$mb_level = $this->get_gnulevel_from_eyoomlevel($level);
		if($mb_level != $member['mb_level']) {
			sql_query("update {$g5['member_table']} set mb_level = '{$mb_level}' where mb_id='{$member['mb_id']}'");
		} else return false;
	}

	// 이윰레벨에서 그누레벨 가져오기
	private function get_gnulevel_from_eyoomlevel($level) {
		global $levelset;
		$gnulevel = array();
		for($i=2;$i<=$levelset['max_use_gnu_level'];$i++) {
			$lv_key = 'cnt_gnu_level_'.$i;
			$max = $levelset[$lv_key] + $gnulevel[$i-1];
			$gnulevel[$i] = $max;
		}
		foreach($gnulevel as $gnu_lv => $max_level) {
			if($level > $max_level) {
				if($gnu_lv == $levelset['max_use_gnu_level']) $mb_level = $gnu_lv;
				else $mb_level = $gnu_lv + 1;
			} else {
				$mb_level = $gnu_lv;
				break;
			}
		}
		return $mb_level;
	}

	// 포인트를 통한 레벨 가져오기
	private function get_level_from_point($point,$level) {
		global $levelinfo;
		
		$lvinfo = $levelinfo[$level];
		if($point > $lvinfo['max']) {
			$level++;
			// 만렙일 경우, 만렙을 유지
			$lvinfo = $levelinfo[$level];
			if(!$lvinfo['min']) $level--;
		}
		if($point < $lvinfo['min']) $level--;
		return $level;
	}

	// 레벨포인트에 따른 조정된 이윰레벨 가져오기
	public function get_eyoom_level($point, $level) {
		$_level = $this->get_level_from_point($point,$level);
		if($_level == $level) {
			return $_level;
		} else {
			return $this->get_eyoom_level($point, $_level);
		}
	}

	// 이윰레벨에서 최종 조정된 그누레벨 가져오기
	public function get_gnu_level($level,$mb_level) {
		$_level = $this->get_gnulevel_from_eyoomlevel($level);
		if($_level != $mb_level) {
			return $this->get_gnu_level($level,$_level);
		} else return $_level;
	}

	public function eyoom_level_info($member) {
		global $eyoomer, $levelinfo, $levelset;

		$lvinfo = $levelinfo[$eyoomer['level']];
		$bar_len = $lvinfo['max'] - $lvinfo['min'];
		$lv_len = $eyoomer['level_point'] - $lvinfo['min'];
		$ratio = ($lv_len/$bar_len)*100;
		if($ratio >= 100) {
			$eyoomer['level'] = $eyoomer['level']+1;
			$this->level_point(1);
			$lvinfo = $levelinfo[$eyoomer['level']];
			$bar_len = $lvinfo['max'] - $lvinfo['min'];
			$lv_len = $eyoomer['level_point'] - $lvinfo['min'];
			$ratio = ceil(($lv_len/$bar_len)*100);
		}
		$lvinfo = $levelinfo[$eyoomer['level']];
		$lvinfo['gnu_name'] = $levelset['gnu_alias_'.$member['mb_level']];
		$lvinfo['level'] = $eyoomer['level'];
		$lvinfo['ratio'] = ceil($ratio*100)/100;
		return $lvinfo;
	}

	public function user_level_info($user) {
		global $levelinfo, $levelset;

		$lvinfo = $levelinfo[$user['level']];
		$bar_len = $lvinfo['max'] - $lvinfo['min'];
		$lv_len = $user['level_point'] - $lvinfo['min'];
		if(!$bar_len) $bar_len = 1;
		$ratio = ceil(($lv_len/$bar_len)*100);

		$lvinfo['gnu_name'] = $levelset['gnu_alias_'.$user['mb_level']];
		$lvinfo['level'] = $user['level'];
		$lvinfo['ratio'] = $ratio;
		return $lvinfo;
	}

	// $levels : "그누레벨|이윰레벨" 형식
	public function level_info($levels) {
		global $eyoom, $levelset, $levelinfo, $theme;
		if($levels) {
			list($gnu_level,$eyoom_level,$anonymous) = explode('|',$levels);
			if($anonymous == 'y') {
				$level['anonymous'] = true;
				return $level;
			} else {
				$level['gnu_name'] = $levelset['gnu_alias_'.$gnu_level];
				$level['name'] = $levelinfo[$eyoom_level]['name'];
				$level['gnu_level'] = $gnu_level;
				$level['eyoom_level'] = $eyoom_level;

				$icon_path = EYOOM_THEME_PATH.'/'.$theme.'/image/level_icon';
				$icon_dir = EYOOM_THEME_URL.'/'.$theme.'/image/level_icon';
				if($eyoom['use_level_icon_gnu'] == 'y') {
					if($gnu_level == 10) $_gnu_level = 'admin';
					else $_gnu_level = $gnu_level;
					$gnu_path = $icon_path.'/gnuboard/'.$eyoom['level_icon_gnu'].'/'.$_gnu_level.'.gif';
					if(file_exists($gnu_path)) $level['gnu_icon'] = $icon_dir.'/gnuboard/'.$eyoom['level_icon_gnu'].'/'.$_gnu_level.'.gif';
				}
				if($eyoom['use_level_icon_eyoom'] == 'y') {
					if($gnu_level == 10) $_eyoom_level = 'admin';
					else $_eyoom_level = $eyoom_level;
					$eyoom_path = $icon_path.'/eyoom/'.$eyoom['level_icon_eyoom'].'/'.$_eyoom_level.'.gif';
					if(file_exists($eyoom_path)) {
						$level['eyoom_icon'] = $icon_dir.'/eyoom/'.$eyoom['level_icon_eyoom'].'/'.$_eyoom_level.'.gif';
						$level['grade_icon'] = $icon_dir.'/grade/'.$eyoom['level_icon_eyoom'].'/g'.$_eyoom_level.'.gif';
					}
				}
				return $level;
			}
		} else return false;
	}

	// 댓글쓰기 포인트
	public function point_comment() {
		global $g5, $member, $eyoom_board, $cmt_amt, $board, $wr_id, $comment_id, $wr;

		unset($point);
		// 첫댓글 포인트
		if($eyoom_board['bo_firstcmt_point'] > 0 && !$cmt_amt && $member['mb_id'] != $wr['mb_id']) {
			$point['firstcmt'] = $eyoom_board['bo_firstcmt_point_type'] == 1 ? $this->random_num($eyoom_board['bo_firstcmt_point']-1)+1 : $eyoom_board['bo_firstcmt_point'];
			if($eyoom_board['bo_cmtpoint_target'] == '1') {
				insert_point($member['mb_id'], $point['firstcmt'], $board['bo_subject'].' wr_id='.$wr_id.' 게시물 첫 댓글 포인트', '@firstcmt', $member['mb_id'], $board['bo_subject'].'|'.$wr_id.'|'.$comment_id);
			} else if($eyoom_board['bo_cmtpoint_target'] == '2') {
				$this->level_point($point['firstcmt']);
			}
		}

		// 지뢰폭탄 포인트 - 게시판 여유필드 wr_2를 사용
		if($eyoom_board['bo_bomb_point'] > 0 && $eyoom_board['bo_bomb_point_limit'] > 0 && $eyoom_board['bo_bomb_point_cnt'] > 0 && $wr['wr_2']) {
			$bomb = @unserialize($wr['wr_2']);
			if(is_array($bomb)) {
				foreach($bomb as $key => $val) {
					if($val == $cmt_amt) {
						$point['bomb'][$key] = $eyoom_board['bo_bomb_point_type'] == 1 ? $this->random_num($eyoom_board['bo_bomb_point']-1)+1 : $eyoom_board['bo_bomb_point'];
						if($eyoom_board['bo_cmtpoint_target'] == '1') {
							insert_point($member['mb_id'], $point['bomb'][$key], $board['bo_subject'].' wr_id='.$wr_id.' 게시물 지뢰폭탄 포인트', '@bomb', $member['mb_id'], $board['bo_subject'].'|'.$wr_id.'|'.$comment_id.'|'.$key);
						} else if($eyoom_board['bo_cmtpoint_target'] == '2') {
							$this->level_point($point['bomb'][$key]);
						}						
					}
				}
			}
		}

		// 럭키 포인트
		if($eyoom_board['bo_lucky_point'] > 0 && $eyoom_board['bo_lucky_point_ratio'] > 0) {
			$max = ceil(100/$eyoom_board['bo_lucky_point_ratio']);
			$random = $this->random_num($max-1);
			if($random%$max == 0) {
				$point['lucky'] = $eyoom_board['bo_lucky_point_type'] == 1 ? $this->random_num($eyoom_board['bo_lucky_point']-1)+1 : $eyoom_board['bo_lucky_point'];
				if($eyoom_board['bo_cmtpoint_target'] == '1') {
					insert_point($member['mb_id'], $point['lucky'], $board['bo_subject'].' wr_id='.$wr_id.' 게시물 행운의 포인트', '@lucky', $member['mb_id'], $board['bo_subject'].'|'.$wr_id.'|'.$comment_id);
				} else if($eyoom_board['bo_cmtpoint_target'] == '2') {
					$this->level_point($point['lucky']);
				}
			}
		}
		if(is_array($point)) return $point;
	}

	public function empty_key($key_val) {
		global $tpl, $tm, $theme, $preview;
		if($theme != 'basic') {
			list($h,$k,$d) = explode("|",$key_val);
			$url = $this->eyoom_host();
			/*
			echo "h : " . $h . " - " . $url['host'] . "<br>";
			echo "k : " . $k . " - " . $tm['tm_code'] . "<br>";
			echo "d : " . $d . " - " . $tm['tm_time'] . "<br>";
			*/
			if(($h != 'n' && $h != $url['host']) || 
				$k != $tm['tm_code'] || 
				$k == null ||
				$d != $tm['tm_time']) {
				//if(!$preview) $this->del_tmfile(config_file);
			}
		}
	}

	// 복호화 함수
	public function decrypt_md5($hex_buf, $key="password") {
        $len = strlen($hex_buf);
        for ($i=0; $i<$len; $i+=2) $buf .= chr(hexdec(substr($hex_buf, $i, 2)));
        
        $key1 = pack("H*", md5($key));
        while($buf) {
           $m = substr($buf, 0, 16);
           $buf = substr($buf, 16);
           
           $c = "";
           for($i=0;$i<16;$i++) $c .= $m{$i}^$key1{$i};
           
           $ret_buf .= $m = $c;
           $key1 = pack("H*",md5($key.$key1.$m));
        }
        return($ret_buf);
    }

	// 내용 가공
	public function eyoom_content($content) {

		// SyntaxHighlighter 처리하기
		$content = $this->syntaxhighlighter($content);

		// 썸네일화하기
		$content = get_view_thumbnail($content);

		// 동영상 처리하기
		$content = preg_replace("/{동영상\s*\:([^}]*)}/ie", "\$this->video_content('\\1')", $content);
		$content = preg_replace("/{이모티콘\s*\:([^}]*)}/ie", "\$this->emoticon_content('\\1')", $content);
		$content = preg_replace("/{soundcloud\s*\:([^}]*)}/ie", "\$this->soundcloud_content('\\1')", $content);
		return $content;
	}

	public function syntaxhighlighter($content) {
		$content = preg_replace("/{CODE\s*\:([^}]*)}/i","<pre class=\"brush: \\1;\">",$content);
		$content = preg_replace("/{\/CODE}/i","</pre>",$content);
		$content = preg_replace_callback("/<pre[^>]*>(.*?)<\/pre>/s",array($this,'syntaxhighlighter_remove_tag'),$content);
		return $content;
	}

	private function syntaxhighlighter_remove_tag($str) {
		$code = $str[0];
		$code = preg_replace("/(<BR>|<BR \/>|<BR\/>|<DIV>|<\/DIV>|<P>|<\/P>)/i","",$code);
		return $code;
	}

	public function video_content($video_url) {
		$video_url = trim(strip_tags($video_url));
		$video_url = preg_replace("/&#?[a-z0-9]+;/i","",htmlentities($video_url));
		$video_url = preg_replace("/nbsp;/i","",$video_url );
		$video = $this->video_from_soruce($video_url);

		if(!$video['width']) {
			switch($video['host']) {
				case 'channel.pandora.tv': $video['width'] = 480; break;
				default : $video['width'] = "640"; break;
			}
		}
		if(!$video['height']) {
			switch($video['host']) {
				case 'nate.com'	: $video['height'] = 384; break;
				default			: $video['height'] = 360; break;			
			}
		}
		return $this->video_source($video);
	}

	// 동영상 경로로 부터 동영상정보 가져오기
	private function video_from_soruce($src) {
		$video_url = trim(strip_tags($src));
		$video_url = preg_replace("/amp;/","&",$video_url);
		$info = $this->eyoom_host($video_url);
		$host = $info['host'];
		$query = $info['query'];
		$video['host'] = $host;

		switch($host) {
			case 'youtube.com':
				$video['key'] = $query['v'];;
				$video['vlist'] = $query['list'];
				break;
			case 'tvcast.naver.com':
				$data = $this->get_video_use_curl($video_url, $host);
				$video['key'] = $data['vid'];
				$video['outKey'] = $data['outKey'];
				break;
			case 'serviceapi.nmv.naver.com':
			case 'serviceapi.rmcnmv.naver.com':
				$video['key'] = $query['vid'];
				$video['outKey'] = $query['outKey'];
				break;
			case 'channel.pandora.tv':
				$video['key'] = $query['prgid'];
				$video['userid'] = $query['ch_userid'];
				break;
			case 'tvpot.daum.net':
				if($query['clipid']) {
					$video['key'] = $this->get_video_use_curl($video_url, $host);
				} else {
					$video['key'] = $this->get_video_key($info);
				}
				break;
			case 'facebook.com':
				if($query['video_id']) {
					$video['key'] = $query['video_id'];
				} else {
					$video['key'] = $query['v'];
				}		
				if(!is_numeric($video['key'])) $video = NULL;
				break;
			case 'slideshare.net':
				$video['key'] = $this->get_video_use_curl($video_url, $host);
				break;
			default:
				$video['key'] = $this->get_video_key($info);
				break;
		}
		return $video;
	}

	// 동영상 키값 추출
	private function get_video_key($info) {
		$tmp = explode("/",$info['path']);
		$video_key = trim($tmp[count($tmp)-1]);
		return $video_key;
	}

	// CURL를 활용한 동영상페이지 웹스크랩핑
	private function get_video_use_curl($url, $host) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = curl_exec($ch);
		curl_close($ch);

		switch($host) {
			case 'tvcast.naver.com':
				preg_match('/nhn.rmcnmv.RMCVideoPlayer\("(?P<vid>[A-Z0-9]+)", "(?P<inKey>[a-z0-9]+)"/i', $output, $video);
				$out['vid'] = $video['vid'];
				$out['inKey'] = $video['inKey'];

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "http://serviceapi.rmcnmv.naver.com/flash/getExternSwfUrl.nhn?vid=".$out['vid'].'&inKey='.$out['inKey']);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				$output = curl_exec($ch);
				curl_close($ch);
				preg_match('/&outKey=(?P<outKey>[a-zA-Z0-9]+)&/i', $output, $video);
				$out['outKey']= $video['outKey'];
				return $out;
				
				break;
			case 'tvpot.daum.net':
				preg_match('/\<meta property=\"og:url\"([^\<\>])*\>/i', $output, $scrapping);
				$temp = explode("/v/",htmlspecialchars($scrapping[0]));
				$res  = explode("&quot;",$temp[1]);
				$code = $res[0];
				return $code;
				break;
			case 'slideshare.net';
				preg_match('/\<meta class=\"twitter_player\"([^\<\>])*\>/i', $output, $scrapping);
				$temp = explode("embed_code/",htmlspecialchars($scrapping[0]));
				$res  = explode("&quot;",$temp[1]);
				$code = $res[0];
				return $code;
				break;
		}
	}

	private function video_source($video) {
		switch($video['host']) {
			case 'youtu.be':
			case 'youtube.com':
				$vlist = $video['vlist'] ? '&list='.$video['vlist'] : '';
				$source = '<iframe width="'.$video['width'].'" height="'.$video['height'].'" src="http://www.youtube.com/embed/'.$video['key'].'?wmode=opaque&autohide=1'.$vlist.'" frameborder="0" allowfullscreen></iframe>';
				break;
			case 'serviceapi.rmcnmv.naver.com':
			case 'tvcast.naver.com':
				$source = '<iframe width="'.$video['width'].'" height="'.$video['height'].'" src="http://serviceapi.rmcnmv.naver.com/flash/outKeyPlayer.nhn?vid='.$video['key'].'&outKey='.$video['outKey'].'&controlBarMovable=true&jsCallable=true&skinName=tvcast_black" frameborder="no" scrolling="no" marginwidth="0" marginheight="0"></iframe>';
				break;
			case 'serviceapi.nmv.naver.com':
				$source = '<iframe width="'.$video['width'].'" height="'.$video['height'].'" src="http://serviceapi.nmv.naver.com/flash/convertIframeTag.nhn?vid='.$video['key'].'&outKey='.$video['outKey'].'" frameborder="no" scrolling="no"></iframe>';
				break;
			case 'vimeo.com':
				$source = '<iframe src="//player.vimeo.com/video/'.$video['key'].'" width="'.$video['width'].'" height="'.$video['height'].'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
				break;
			case 'ted.com':
				$source = '<iframe src="https://embed-ssl.ted.com/talks/'.$video['key'].'.html" width="'.$video['width'].'" height="'.$video['height'].'" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
				break;
			case 'tvpot.daum.net':
				$source = '<iframe width="'.$video['width'].'" height="'.$video['height'].'" src="http://videofarm.daum.net/controller/video/viewer/Video.html?vid='.$video['key'].'&play_loc=undefined&wmode=opaque" frameborder="0" scrolling="no"></iframe>';
				break;
			case 'channel.pandora.tv':
				$source = '<iframe width="'.$video['width'].'" height="'.$video['height'].'" src="http://channel.pandora.tv/php/embed.fr1.ptv?userid='.$video['userid'].'&prgid='.$video['key'].'&skin=1&autoPlay=false&share=on" frameborder="0" allowfullscreen></iframe>';
				break;
			case 'tagstory.com':
				$source = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="'.$video['width'].'" height="'.$video['height'].'" id="'.$video['key'].'" ><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="allowFullScreen" value="true" /><param name="movie" value="http://www.tagstory.com/player/basic/'.$video['key'].'" /><embed src="http://www.tagstory.com/player/basic/'.$video['key'].'" width="'.$video['width'].'" height="'.$video['height'].'" name="'.$video['key'].'" allowScriptAccess="always" allowNetworking="all" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" /></object>';
				break;
			case 'dailymotion.com':
				$source = '<iframe frameborder="0" width="'.$video['width'].'" height="'.$video['height'].'" src="http://www.dailymotion.com/embed/video/'.$video['key'].'"></iframe>';
				break;
			case 'facebook.com':
				$source = '<iframe src="https://www.facebook.com/video/embed?video_id='.urlencode($video['key']).'" width="'.$video['width'].'" height="'.$video['height'].'" frameborder="0"></iframe>';
				break;
			case 'slideshare.net':
				$source = '<iframe src="https://www.slideshare.net/slideshow/embed_code/'.$video['key'].'" width="'.$video['width'].'" height="'.$video['height'].'" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" allowfullscreen></iframe>';
				break;
		}
		if($source) {
			$source = "<div class='responsive-video'>".$source."</div>";
			return $source;
		} else return false;		
	}

	public function emoticon_content($emoticon) {
		global $theme;
		$dir = preg_replace("/([0-9]|_|-)/i","",$emoticon);
		$path = EYOOM_THEME_URL.'/'.$theme.'/emoticon/'.$dir.'/';
		$output = "<img src='".$path.$emoticon.".gif' align='absmiddle' width='50'>";
		return $output;
	}

	public function get_emoticon($dirname) {
		global $theme;
		$path = EYOOM_THEME_PATH.'/'.$theme.'/emoticon/'.$dirname;
		$url = EYOOM_THEME_URL.'/'.$theme.'/emoticon/'.$dirname;
		$files = glob($path.'/*.gif');
		foreach($files as $k => $file) {
			$temp = explode("/",$file);
			$filename = $temp[(count($temp)-1)];
			$emoticon[$k]['emoticon'] = substr($filename,0,-4);
			$emoticon[$k]['url'] = $url.'/'.$filename;
		}
		return $emoticon;
	}

	public function soundcloud_content($source) {
		$src = trim(strip_tags($source));
		$src = str_replace('\"', '', $src);

		if(!$source) return;
		$soundcloud = '';
		if(preg_match('/soundcloud.com/i', $src)) {
			$soundcloud = '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url='.$src.'"></iframe>'."\n";
		}
		$soundcloud = "<div style='margin:15px 0;'>".$soundcloud."</div>";
		return $soundcloud;
	}

	public function get_editor_video($content) {
		if(!$content) return false;

		$pattern = '/{동영상\s*\:([^}]*)}/ie';
		preg_match_all($pattern, $content, $matchs);
		return $matchs;
	}

	public function get_editor_sound($content) {
		if(!$content) return false;

		$pattern = '/{soundcloud\s*\:([^}]*)}/ie';
		preg_match_all($pattern, $content, $matchs);
		return $matchs;
	}

	public function remove_editor_code($content) {
		$content = preg_replace("/\\n/","",$content);
		$content = preg_replace("/\s{2,}/","",$content);
		$content = preg_replace("/{CODE\s*\:([^}]*)}(.*?){\/CODE}/is","",$content);
		return $content;
	}

	public function remove_editor_video($content) {
		$content = preg_replace("/{동영상\s*\:([^}]*)}/ie","",$content);
		return $content;
	}

	public function remove_editor_sound($content) {
		$content = preg_replace("/{soundcloud\s*\:([^}]*)}/ie","",$content);
		return $content;
	}

	public function remove_editor_emoticon($content) {
		$content = preg_replace("/{이모티콘\s*\:([^}]*)}/ie","",$content);
		return $content;
	}

	// 에디터로 업로드된 이미지 파일 삭제
	public function delete_editor_image($content) {
		if(!$content) return false;

		// 게시물 내용에서 이미지 추출
		$matchs = get_editor_image($content,false);
		if(!$matchs) return false;

		for($i=0; $i<count($matchs[1]); $i++) {
			// 이미지 path 구함
			$imgurl = parse_url($matchs[1][$i]);
			$srcfile = $_SERVER['DOCUMENT_ROOT'].$imgurl['path'];
			$filename = preg_replace("/\.[^\.]+$/i", "", basename($srcfile));
			$filepath = dirname($srcfile);
			$files = glob($filepath.'/thumb-'.$filename.'*');
			if (is_array($files)) {
				foreach($files as $filename)
					@unlink($filename);
			}
			@unlink($srcfile);
		}
	}

	// 댓글에 첨부한 이미지 삭제
	public function delete_comment_image($content,$bo_table) {
		if(!$content || !$bo_table) return false;

		$b_file = unserialize($content);
		foreach($b_file as $key => $bf) {
			@unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$bf['file']);
		}
	}

	// 회원의 추천/비추천 정보 가져오기
	public function mb_goodinfo($mb_id, $bo_table, $wr_id) {
		global $g5;
		if(!$mb_id || !$bo_table || !$wr_id) return false;
		else {
			$sql = "select * from {$g5['board_good_table']} where bo_table='{$bo_table}' and wr_id='{$wr_id}' and mb_id='{$mb_id}' limit 1";
			$info = sql_fetch($sql,false);
			return $info;
		}
	}

	public function get_skin_dir($skin, $skin_path=G5_SKIN_PATH) {
		global $g5;

		$result_array = array();

		$dirname = $skin_path.'/'.$skin.'/';
		$handle = @opendir($dirname);
		while ($file = @readdir($handle)) {
			if($file == '.'||$file == '..') continue;

			if (@is_dir($dirname.$file)) $result_array[] = $file;
		}
		@closedir($handle);
		@sort($result_array);

		return $result_array;
	}

	// 10진수를 62진수 변환 - PHP스쿨 마냐님 소스 : http://www.phpschool.com/link/tipntech/79695 참조
	public function base62_encode($val, $base=62) {
		// can't handle numbers larger than 2^31-1 = 2147483647
		$str = '';
		do {
			$i = $val % $base;
			$str = $this->chars[$i] . $str;
			$val = ($val - $i) / $base;
		} while($val > 0);
		return $str;
	}

	// 62진수를 10진수로 변환 - PHP스쿨 마냐님 소스 : http://www.phpschool.com/link/tipntech/79695 참조
	public function base62_decode($str, $base=62) {
		$len = strlen($str);
		$val = 0;
		$arr = array_flip(str_split($this->chars));
		for($i = 0; $i < $len; ++$i) {
			$val += $arr[$str[$i]] * pow($base, $len-$i-1);
		}
		return $val;
	}

	// 짧은주소에서 게시판 기본정보 추출하기
	public function short_url_data($t) {
		global $g5;

		$s_no = (int)$this->base62_decode($t);
		if(!$s_no || !is_int($s_no)) {
			return false;
		} else {
			$link = sql_fetch("select * from {$g5['eyoom_link']} where s_no = '{$s_no}' limit 1", false);
			if($link) {
				if (isset($link['wr_id'])) {
					$data['wr_id'] = (int)$link['wr_id'];
				} else {
					$data['wr_id'] = 0;
				}

				if(isset($link['bo_table'])) {
					$data['bo_table'] = preg_replace('/[^a-z0-9_]/i', '', trim($link['bo_table']));
					$data['bo_table'] = substr($data['bo_table'], 0, 20);
				} else {
					$data['bo_table'] = '';
				}

				$write = array();
				$write_table = "";
				if ($data['bo_table']) {
					$data['board'] = sql_fetch(" select * from {$g5['board_table']} where bo_table = '{$data['bo_table']}' ");
					if ($data['board']['bo_table']) {
						set_cookie("ck_bo_table", $data['board']['bo_table'], 86400 * 1);
						$data['gr_id'] = $data['board']['gr_id'];
						$write_table = $g5['write_prefix'] . $data['bo_table']; // 게시판 테이블 전체이름
						if (isset($data['wr_id']) && $data['wr_id']) {
							$data['write'] = sql_fetch(" select * from $write_table where wr_id = '{$data['wr_id']}' ");
						}
					}
				}

				if ($data['gr_id']) {
					$data['group'] = sql_fetch(" select * from {$g5['group_table']} where gr_id = '{$data['gr_id']}' ");
				}
				$data['theme'] = $link['theme'];
				$data['write_table'] = $write_table;
				return $data;

			} else {
				return false;
			}
		}
	}

	// 짧은주소로 가져오기
	public function get_short_url() {
		global $g5, $bo_table, $wr_id, $theme;
		if(!$bo_table || !$wr_id || !$theme) {
			return false;
		} else {
			$link = sql_fetch("select * from {$g5['eyoom_link']} where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' and theme = '{$theme}' ", false);
			if($link['bo_table']) {
				$t = $this->base62_encode($link['s_no']);
				return G5_BBS_URL . "/?t=".$t;
			} else return false;
		}
	}

	// 짧은주소 생성하기
	public function make_short_url() {
		global $g5, $bo_table, $wr_id, $theme;
		$sql = "insert into {$g5['eyoom_link']} set bo_table='{$bo_table}', wr_id = '{$wr_id}', theme = '{$theme}'";
		sql_query($sql,false);
		$s_no = mysql_insert_id();
		$t = $this->base62_encode($s_no);
		return G5_BBS_URL . "/?t=".$t;
	}

	// Device의 OS검색
	public function user_agent(){
		$iPod	 = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
		$iPhone  = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$iPad	 = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
		$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
		if($iPad||$iPhone||$iPod){
			return 'ios';
		}else if($android){
			return 'android';
		}else{
			return 'pc';
		}
	}

}
?>