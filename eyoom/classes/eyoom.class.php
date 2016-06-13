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
	
	// 공사중 설정 시간 변환하여 리턴
	public function mktime_countdown_date($cd_datetime) {
		
		if(strlen($cd_datetime) == 12) {
			$cd_date = array();
			$cd_date['year'] 	= substr($cd_datetime,0,4);
			$cd_date['month'] 	= substr($cd_datetime,4,2);
			$cd_date['day'] 	= substr($cd_datetime,6,2);
			$cd_date['hour'] 	= substr($cd_datetime,8,2);
			$cd_date['minute']	= substr($cd_datetime,10,2);
			$cd_date['mktime']	= mktime($cd_date['hour'], $cd_date['minute'], 0, $cd_date['month'], $cd_date['day'], $cd_date['year']);
			$cd_date['month_text'] = date('F', $cd_date['mktime']);
			
			return $cd_date;
			
		} else {
			return false;
		}
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
	
	// 이윰보드 설정값이 DB에 없는 상태에서 기본값 설정
	public function eyoom_board_default($bo_table) {
		global $theme;
		if($bo_table) {
			$eyoom_board = array(
				'bo_table'					=> $bo_table,
				'bo_theme'					=> $theme,
				'bo_skin'					=> 'basic',
				'use_gnu_skin'				=> 'n',
				'bo_use_profile_photo'		=> 1,
				'bo_sel_date_type'			=> 1,
				'bo_use_hotgul'				=> 1,
				'bo_use_anonymous'			=> 2,
				'bo_use_infinite_scroll'	=> 2,
				'bo_use_point_explain'		=> 1,
				'bo_use_video_photo'		=> 2,
				'bo_use_list_image'			=> 1,
				'bo_use_yellow_card'		=> 0,
				'bo_use_exif'				=> 0,
				'bo_use_rating'				=> 0,
				'bo_use_rating_list'		=> 1,
				'bo_use_summernote_mo'		=> 1,
				'bo_use_addon_emoticon'		=> 1,
				'bo_use_addon_video'		=> 1,
				'bo_use_addon_coding'		=> 0,
				'bo_use_addon_soundcloud'	=> 0,
				'bo_use_addon_map'			=> 0,
				'bo_use_addon_cmtimg'		=> 1,
				'bo_blind_limit'			=> 5,
				'bo_blind_view'				=> 10,
				'bo_blind_direct'			=> 10,
				'bo_firstcmt_point'			=> 0,
				'bo_firstcmt_point_type' 	=> 1,
				'bo_bomb_point'				=> 0,
				'bo_bomb_point_type'		=> 1,
				'bo_bomb_point_limit'		=> 10,
				'bo_bomb_point_cnt'			=> 1,
				'bo_lucky_point'			=> 0,
				'bo_lucky_point_type'		=> 1,
				'bo_lucky_point_ratio'		=> 1,
				'download_fee_ratio'		=> 0,
			);
			return $eyoom_board;
			
		} else {
			return false;
		}
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
			$rid = sql_insert_id();

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

	// 암호화 함수
	public function encrypt_md5($buf, $key="password") {
		$key1 = pack("H*",md5($key));
		while($buf) {
			$m = substr($buf, 0, 16);
			$buf = substr($buf, 16);
			
			$c = "";
			for($i=0;$i<16;$i++) $c .= $m{$i}^$key1{$i};
			$ret_buf .= $c;
			$key1 = pack("H*",md5($key.$key1.$m));
		}
		
		$len = strlen($ret_buf);
		for($i=0; $i<$len; $i++) $hex_data .= sprintf("%02x", ord(substr($ret_buf, $i, 1)));
		return($hex_data);
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
		$content = $this->get_thumbnail($content);

		// 동영상
		$content = preg_replace_callback("/{동영상\s*\:([^}]*)}/i", array($this,'video_content'), $content);
		
		// 이모티콘
		$content = preg_replace_callback("/{이모티콘\s*\:([^}]*)}/i", array($this, 'emoticon_content'), $content);
		
		// 사운드클라우드
		$content = preg_replace_callback("/{soundcloud\s*\:([^}]*)}/i", array($this, 'soundcloud_content'), $content);
		
		// 지도
		$content = preg_replace_callback("/{지도\s*\:([^}]*)}/i", array($this, 'map_content'), $content);
		
		return $content;
	}
	
	// 게시글 내용에서 텍스트만 추출
	public function eyoom_text_abstract($content, $length=300) {
		$content = preg_replace("#\\r#","",cut_str(str_replace('&nbsp;','',strip_tags(stripslashes($content))),$length,''));
		$content = preg_replace("#\\n#","",$content);
		$content = preg_replace("#\\t#","",$content);
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
		$video_url = trim(strip_tags($video_url[1]));
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

		if($host == 'vimeo.com') {
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$output = unserialize(curl_exec($ch));
			$output = $output[0];
			curl_close($ch);
			return $output;
		} else {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$output = curl_exec($ch);
			curl_close($ch);
			if($host == 'ted.com') return $output;
		}
		
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
			case 'slideshare.net':
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
			case 'sendvid.com':
				$source = '<iframe width="'.$video['width'].'" height="'.$video['height'].'" src="//sendvid.com/embed/'.$video['key'].'" frameborder="0" allowfullscreen></iframe>';
				break;
		}
		if($source) {
			$source = "<div class='responsive-video'>".$source."</div>";
			return $source;
		} else return false;		
	}
	
	/**
	 * URL로부터 동영상 이미지 경로를 찾기
	 */
	public function get_imgurl_from_video($src) {
		$video = $this->video_from_soruce($src);
		
		switch($video['host']) {
			case 'youtu.be':
			case 'youtube.com':
				$path_name = mb_substr($video['key'],0,11,"utf-8");
				$video['img_url'] = "http://img.youtube.com/vi/{$path_name}/0.jpg ";
				break;

			case 'vimeo.com':
				$url = "http://vimeo.com/api/v2/video/{$video['key']}.php";
				$thumb = $this->get_video_use_curl($url, $video['host']);
				$video['img_url'] = $thumb['thumbnail_large'];
				break;
			
			case 'ted.com':
				$content = $this->get_video_use_curl($src, $video['host']);
				if (preg_match('!(((ht|f)tps?:\/\/)|(www.))[a-zA-Z0-9_\-.:#/~}?]+.jpg!', $content, $match)) {
					$video['img_url'] = $match[0];
				}
				break;
			case 'sendvid.com':
				$video['img_url'] = "http://sendvid.com/{$video['key']}.jpg";
				break;
			default : $video['img_url'] = ''; break;
		}
		return $video;
	}
	
	/**
	 * 동영상 URL를 이용하여 목록이미지 thumbnail 생성하기
	 */
	public function make_thumb_from_video($src, $bo_table, $wr_id, $width, $height) {
		global $w;
		$src = preg_replace('/&nbsp;/', '', $src);
		
		$prefix = 'vlist';
		
		$video = $this->get_imgurl_from_video($src);
		$filename = trim($this->get_filename_from_url($video['img_url']));
		$thumb_info = '/file/' . $bo_table . '/' . $prefix . '_thumb_' . $wr_id . '_' . $filename;
		$vlist_thumb_path = G5_DATA_PATH . $thumb_info;
		$vlist_thumb_url = G5_DATA_URL . $thumb_info;
		
		if($video['img_url']) {
			if( file_exists($vlist_thumb_path) && $w != 'u') {
				return $vlist_thumb_url;
			} else {
				if($video['host'] != 'ted.com') { // TED 이미지는 용량이 너무 커서 로컬에 저장하는 시간이 너무 길어서 제외
					$local_image = G5_DATA_PATH . '/file/' . $bo_table . '/' . $prefix . '_img_' . $wr_id . '_' . $filename;
					if(file_exists($local_image)) {
						return $this->make_thumb_list_image($prefix, $bo_table, $wr_id, $filename, $width, $height);
					} else {
						$this->save_url_image($video['img_url'], $local_image);
						return $this->make_thumb_list_image($prefix, $bo_table, $wr_id, $filename, $width, $height);
					}
				} else {
					return $video['img_url'];
				}
			}
		} else return false;
	}
	
	public function make_thumb_from_extra_image($bo_table, $wr_id, $content, $width, $height) {
		global $w;
		
		if(!$content) return false;

		// 게시물 내용에서 이미지 추출
		$matchs = get_editor_image($content,false);
		if(!$matchs) return false;
		
		$prefix = 'extimg';
		
		$extra_img_url = $matchs[1][0];
		$extra_parse_url = parse_url($extra_img_url);
		$host = $extra_parse_url['host'];
		if($host == $_SERVER['HTTP_HOST']) return false;
		
		$filename = trim($this->get_filename_from_url($extra_img_url));
		$thumb_info = '/file/' . $bo_table . '/' . $prefix . '_thumb_' . $wr_id . '_' . $filename;
		$list_thumb_path = G5_DATA_PATH . $thumb_info;
		$list_thumb_url = G5_DATA_URL . $thumb_info;
		
		if($extra_img_url) {
			if( file_exists($list_thumb_path) && $w != 'u') {
				return $list_thumb_url;
			} else {
				$local_image = G5_DATA_PATH . '/file/' . $bo_table . '/' . $prefix . '_img_' . $wr_id . '_' . $filename;
				if(file_exists($local_image)) {
					return $this->make_thumb_list_image($prefix, $bo_table, $wr_id, $filename, $width, $height);
				} else {
					$this->save_url_image($extra_img_url, $local_image);
					return $this->make_thumb_list_image($prefix, $bo_table, $wr_id, $filename, $width, $height);
				}
			}
		} else return false;
	}
	
	/**
	 * URL로 부터 파일명 가져오기
	 */
	public function get_filename_from_url($url) {
		$dirs = explode('/', $url);
		return $dirs[(count($dirs)-1)];
	}
	
	/**
	 * 외부 이미지 로컬에 저장하기
	 */
	public function save_url_image($url, $local_image) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		$rawdata = curl_exec($ch);
		curl_close ($ch);
		
		if(file_exists($local_image)){
			unlink($local_image);
		}
		$fp = fopen($local_image,'x');
		fwrite($fp, $rawdata);
		fclose($fp);
	}
	
	/**
	 * 다운로드된 비디오 이미지 파일을 썸네일화
	 */
	public function make_thumb_list_image ($prefix, $bo_table, $wr_id, $filename, $width, $height) {
		
		$img_info = '/file/' . $bo_table . '/' . $prefix . '_img_' . $wr_id . '_' . $filename;
		$img = G5_DATA_PATH . $img_info;

		if (file_exists($img)) {
			$size = getimagesize($img);
			switch ($size['mime']) {
				case "image/jpeg"	: $source = @imagecreatefromjpeg($img); $ext = 'jpg'; break;
				case "image/gif"	: $source = @imagecreatefromgif($img); $ext = 'gif'; break;
				case "image/png"	: $source = @imagecreatefrompng($img); $ext = 'png'; break;
			}

			if(!$height) {
				$height = $width*($size[1]/$size[0]);
			}
			
			$dest = @imagecreatetruecolor($width, $height);
			$out_file = G5_DATA_PATH . '/file/' . $bo_table . '/' . $prefix . '_thumb_' . $wr_id . '_' . $filename;
			$out_url = G5_DATA_URL . '/file/' . $bo_table . '/' . $prefix . '_thumb_' . $wr_id . '_' . $filename;
			@imagecopyresampled($dest, $source, 0, 0, 0, 0, $width , $height, $size[0], $size[1]);
			@imagejpeg($dest, $out_file , 100);
			@imagedestroy($dest);
			@imagedestroy($source);
			@unlink($img);
			
			return $out_url;

		} else return false;
	}

	public function emoticon_content($emoticon) {
		global $theme;
		$dir = preg_replace("/([0-9]|_|-)/i","",$emoticon[1]);
		$path = EYOOM_THEME_URL.'/'.$theme.'/emoticon/'.$dir.'/';
		$output = "<img src='".$path.$emoticon[1].".gif' align='absmiddle' width='50'>";
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
		$src = trim(strip_tags($source[1]));
		$src = str_replace('\"', '', $src);

		if(!$src) return;
		$soundcloud = '';
		if(preg_match('/soundcloud.com/i', $src)) {
			$soundcloud = '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url='.$src.'"></iframe>'."\n";
		}
		$soundcloud = "<div style='margin:15px 0;'>".$soundcloud."</div>";
		return $soundcloud;
	}

	public function map_content($source) {
		global $eyoom_board;
		
		list($type, $address, $name, $subgps) = explode('^|^', $source[1]);
		
		if(!$subgps || $eyoom['use_map_content'] == 'n') return $address;
		else {
			$map_content = '';
			$map_hashkey = md5(time().$this->random_num(1000));
			
			$gps_number = preg_replace('/\(|\)/','',$subgps);
			list($gps_x,$gps_y) = explode(',',$gps_number);
			
			switch($type) {
				case '1': $map_type = 'google'; break;
				case '2': $map_type = 'naver'; break;
				case '3': $map_type = 'daum'; break;
				default : $map_type = 'google'; break;
			}
			
			$map_info = $this->encrypt_md5($source);
			$map_content = '<div class="map-content-wrap" data-map-type="'.$map_type.'" data-map-x="'.trim($gps_x).'" data-map-y="'.trim($gps_y).'" data-map-address="'.$address.'" data-map-name="'.$name.'"><div id="'.$map_hashkey.'"></div></div>';
		}
		
		return $map_content;
	}

	public function get_editor_video($content) {
		if(!$content) return false;

		$pattern = '/{동영상\s*\:([^}]*)}/i';
		preg_match_all($pattern, $content, $matchs);
		return $matchs;
	}

	public function get_editor_sound($content) {
		if(!$content) return false;

		$pattern = '/{soundcloud\s*\:([^}]*)}/i';
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
		$content = preg_replace("/{동영상\s*\:([^}]*)}/i","",$content);
		return $content;
	}

	public function remove_editor_sound($content) {
		$content = preg_replace("/{soundcloud\s*\:([^}]*)}/i","",$content);
		return $content;
	}

	public function remove_editor_emoticon($content) {
		$content = preg_replace("/{이모티콘\s*\:([^}]*)}/i","",$content);
		return $content;
	}
	
	public function remove_editor_map($content) {
		$content = preg_replace("/{지도\s*\:([^}]*)}/i","",$content);
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
	
	// 게시글보기 썸네일 생성
	public function get_thumbnail($contents, $thumb_width=0) {
	    global $board, $config, $eyoom_board, $exif;
	
	    if (!$thumb_width) $thumb_width = $board['bo_image_width'];
	
	    // $contents 중 img 태그 추출
	    $matches = get_editor_image($contents, true);
	
	    if(empty($matches)) return $contents;
	
	    for($i=0; $i<count($matches[1]); $i++) {
	
	        $img = $matches[1][$i];
	        preg_match("/src=[\'\"]?([^>\'\"]+[^>\'\"]+)/i", $img, $m);
	        $src = $m[1];
	        preg_match("/style=[\"\']?([^\"\'>]+)/i", $img, $m);
	        $style = $m[1];
	        preg_match("/width:\s*(\d+)px/", $style, $m);
	        $width = $m[1];
	        preg_match("/height:\s*(\d+)px/", $style, $m);
	        $height = $m[1];
	        preg_match("/alt=[\"\']?([^\"\']*)[\"\']?/", $img, $m);
	        $alt = get_text($m[1]);
	
	        // 이미지 path 구함
	        $p = parse_url($src);
	        if(strpos($p['path'], '/'.G5_DATA_DIR.'/') != 0)
	            $data_path = preg_replace('/^\/.*\/'.G5_DATA_DIR.'/', '/'.G5_DATA_DIR, $p['path']);
	        else
	            $data_path = $p['path'];
	
	        $srcfile = G5_PATH.$data_path;
	
	        if(is_file($srcfile)) {
		        // EXIF 정보
		        if($eyoom_board['bo_use_exif']) {
				   $exif_info = $exif->get_exif_info($srcfile);
		        }
		        
	            $size = @getimagesize($srcfile);
	            if(empty($size))
	                continue;
	
	            // jpg 이면 exif 체크
	            if($size[2] == 2 && function_exists('exif_read_data')) {
	                $degree = 0;
	                $_exif = @exif_read_data($srcfile);
	                if(!empty($_exif['Orientation'])) {
	                    switch($_exif['Orientation']) {
	                        case 8:
	                            $degree = 90;
	                            break;
	                        case 3:
	                            $degree = 180;
	                            break;
	                        case 6:
	                            $degree = -90;
	                            break;
	                    }
	
	                    // 세로사진의 경우 가로, 세로 값 바꿈
	                    if($degree == 90 || $degree == -90) {
	                        $tmp = $size;
	                        $size[0] = $tmp[1];
	                        $size[1] = $tmp[0];
	                    }
	                }
	            }
	
	            // 원본 width가 thumb_width보다 작다면
	            if($size[0] <= $thumb_width)
	                continue;
	
	            // Animated GIF 체크
	            $is_animated = false;
	            if($size[2] == 1) {
	                $is_animated = is_animated_gif($srcfile);
	            }
	
	            // 썸네일 높이
	            $thumb_height = round(($thumb_width * $size[1]) / $size[0]);
	            $filename = basename($srcfile);
	            $filepath = dirname($srcfile);
	
	            // 썸네일 생성
	            if(!$is_animated)
	                $thumb_file = thumbnail($filename, $filepath, $filepath, $thumb_width, $thumb_height, false);
	            else
	                $thumb_file = $filename;
	                
	            if($thumb_file) {
		            if ($width) {
		                $thumb_tag = '<img src="'.G5_URL.str_replace($filename, $thumb_file, $data_path).'" alt="'.$alt.'" width="'.$width.'" height="'.$height.'"/>';
		            } else {
		                $thumb_tag = '<img src="'.G5_URL.str_replace($filename, $thumb_file, $data_path).'" alt="'.$alt.'"/>';
		            }
		
		            // $img_tag에 editor 경로가 있으면 원본보기 링크 추가
		            $img_tag = $matches[0][$i];
		            if(strpos($img_tag, G5_DATA_DIR.'/'.G5_EDITOR_DIR) && preg_match("/\.({$config['cf_image_extension']})$/i", $filename)) {
		                $imgurl = str_replace(G5_URL, "", $src);
		                $thumb_tag = '<a href="'.G5_BBS_URL.'/view_image.php?fn='.urlencode($imgurl).'" target="_blank" class="view_image">'.$thumb_tag.'</a>';
		            }
	            } else {
		            if ($width) {
		                $thumb_tag = '<img src="'.G5_URL.$data_path.'" alt="'.$alt.'" width="'.$width.'" height="'.$height.'"/>';
		            } else {
		                $thumb_tag = '<img src="'.G5_URL.$data_path.'" alt="'.$alt.'"/>';
		            }
		            $img_tag = $matches[0][$i];
	            }
	            
	            // EXIF 정보
	            if($exif_info && $eyoom_board['bo_use_exif']) {
		            $thumb_tag .= $exif_info;
		        }
	
	            $contents = str_replace($img_tag, $thumb_tag, $contents);
	        }
	    }
	    return $contents;
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
	
	// 신고 내역
	public function mb_yellow_card($mb_id, $bo_table, $wr_id) {
		global $g5;
		if(!$mb_id || !$bo_table || !$wr_id) return false;
		else {
			$sql = "select * from {$g5['eyoom_yellowcard']} where bo_table='{$bo_table}' and wr_id='{$wr_id}' and mb_id='{$mb_id}' limit 1";
			$info = sql_fetch($sql,false);
			return $info;
		}
	}
	
	// 별점 내역
	public function mb_rating() {
		global $g5;
		if(!$mb_id || !$bo_table || !$wr_id) return false;
		else {
			$sql = "select * from {$g5['eyoom_rating']} where bo_table='{$bo_table}' and wr_id='{$wr_id}' and mb_id='{$mb_id}' limit 1";
			$info = sql_fetch($sql,false);
			return $info;
		}
	}
	
	// 별점 정보 가져오기
	public function get_star_rating($info) {
		if(isset($info['rating_score']) && $info['rating_members'] > 0) {
			$rating['point'] = ceil(($info['rating_score']/$info['rating_members'])*10)/10;
			$rating['star'] = round($rating['point']);
			$rating['members'] = $info['rating_members'];
		} else {
			$rating['point'] = 0;
			$rating['star'] = 0;
			$rating['members'] = 0;
		}
		return $rating;
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
		$s_no = sql_insert_id();
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
	
	// 버전을 입력받아 버전스코어 점수를 리턴함
	public function version_score($version) {
		$ebv = explode('.', $version);
		$ebv = array_reverse($ebv);
		foreach($ebv as $k => $val) {
			$vscore[$k] = $val * pow(1000, $k);
		}
		return array_sum($vscore);
	}
	
	// sql set배열을 sql문으로 완성하기
	public function make_sql_set($source = array()) {
		$i=0;
		foreach ($source as $key => $val) {
			$set[$i] = "{$key} = '{$val}'";
			$i++;
		}
		if(is_array($set)) {
			return implode(',', $set);
		}
	}
	
	// 태그 정보 가져오기
	public function get_tag_info($bo_table, $wr_id) {
		global $g5, $theme;
		$sql = " select * from {$g5['eyoom_tag_write']} where tw_theme='{$theme}' and bo_table='{$bo_table}' and wr_id='{$wr_id}' ";
		return sql_fetch($sql, false);
	}
	
	// 연관태그 정보
	public function get_rel_tag($tag) {
		global $g5, $theme;
		
		$org_tag = str_replace('^','&',$tag);
		$tags = explode('*', $org_tag);
		if(is_array($tags)) {
			$tag_query = " and tw_theme = '{$theme}' ";
			$i=0;
			foreach($tags as $_tag) {
				$sch_tag[$i] = " ( INSTR(wr_tag, '".$_tag."') > 0 ) ";
				@sql_query("update {$g5['eyoom_tag']} set tg_scnt = tg_scnt+1, tg_score = tg_score+1 where tg_theme='{$theme}' and tg_word = '{$_tag}'");
				$i++;
			}
			$tag_query .= ' and ' . implode(' and ', $sch_tag);
		}
		$sql = "select wr_tag from {$g5['eyoom_tag_write']} as a where (1) {$tag_query}";
		$res = sql_query($sql);
		for($i=0;$row=sql_fetch_array($res);$i++) {
			$in_tag = explode(',', $row['wr_tag']);
			foreach($in_tag as $_tag) {
				$in_tags[trim($_tag)] = true;
			}
		}
		
		if(isset($in_tags)) {
			
			ksort($in_tags);
			$i=0;
			foreach($in_tags as $_tag => $val) {
				if(in_array($_tag, $tags)) continue;
				else if ($_tag) {
					$rel_tags[$i]['tag'] = $_tag;
					$rel_tags[$i]['href'] = G5_URL . "/tag/?tag=" . $tag . "*" . str_replace('&','^',$_tag);
					$i++;
				}
			}
		}
		$output['tag_query'] 	= $tag_query;
		$output['rel_tags'] 	= $rel_tags;
		
		return $output;
	}

}
?>