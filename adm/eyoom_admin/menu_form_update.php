<?php
$sub_menu = '800300';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

auth_check($auth[$sub_menu], "r");

switch($_POST['mode']) {
	case "update":
		if($_POST['me_code'] === '1') $_POST['me_code'] = '';

		if($_POST['subme_name']) {
			// 서브메뉴 추가하기
			$subme_info = $thema->get_menu_link($_POST['subme_link']);
			$subme_path = $_POST['me_path'] ? $_POST['me_path'].' > '.$_POST['subme_name']:$_POST['subme_name'];

			$length = strlen($_POST['me_code'])+3;
			$where = " me_theme='{$_POST['theme']}' and me_code like '$_POST[me_code]%' and length(me_code)=$length";
			$row = sql_fetch("select max(me_code) as max from {$g5['eyoom_menu']} where $where");
			$max = $row['max'];
			if (!$max) $max = $_POST['me_code']."000"; 
			$me_code = sprintf("%0{$length}s",$max+1);

			$row2 = sql_fetch("select max(me_order) as max from {$g5['eyoom_menu']} where $where");
			$me_order = $row2['max'] + 1;

			if(!$_POST['subme_use_nav']) {
				$_me_code = str_split($_POST['me_code'],3);
				$row3 = sql_fetch("select * from {$g5['eyoom_menu']} where me_theme='{$_POST['theme']}' and me_code='{$_me_code[0]}'");
				$me_use_nav = $row3['me_use_nav'];
				if(!$me_use_nav) $me_use_nav = 'y';
			} else {
				$me_use_nav = $_POST['subme_use_nav'];
			}

			$set = "
				me_theme	= '{$_POST['theme']}',
				me_code		= '{$me_code}',
				me_order	= '{$me_order}',
				me_icon		= '{$_POST['subme_icon']}',
				me_name		= '{$_POST['subme_name']}',
				me_path		= '{$subme_path}',
				me_type		= '{$subme_info['me_type']}',
				me_pid		= '{$subme_info['me_pid']}',
				me_link		= '{$subme_info['me_link']}',
				me_target	= '{$_POST['subme_target']}',
				me_use		= '{$_POST['subme_use']}',
				me_use_nav	= '{$me_use_nav}'
			";
			$insert = "insert into {$g5['eyoom_menu']} set $set";
			sql_query($insert,false);

		} else {
			// 메뉴 수정하기
			$me_info = $thema->get_menu_link($_POST['me_link']);

			// 출력순서 중복값 예외처리
			if($_POST['me_order'] != $_POST['me_order_prev']) {
				$_code = substr($_POST['me_code'],0,-3);
				if($_code) $where = " and me_code like '{$_code}%' ";
				else $where = " and length(me_code)=3 ";
				$where .= " and me_order = '{$_POST['me_order']}' ";
				$row = sql_fetch("select me_id from {$g5['eyoom_menu']} where me_theme='{$_POST['theme']}' $where ", false);
				if($row['me_id']) alert("이미 사용중인 출력순서 번호입니다.");
			}

			if(!$_POST['me_use_nav']) {
				$_me_code = str_split($_POST['me_code'],3);
				$row3 = sql_fetch("select * from {$g5['eyoom_menu']} where me_theme='{$_POST['theme']}' and me_code='{$_me_code[0]}'");
				$me_use_nav = $row3['me_use_nav'];
				if(!$me_use_nav) $me_use_nav = 'y';
			} else {
				$me_use_nav = $_POST['me_use_nav'];
				$sql = "update {$g5['eyoom_menu']} set me_use_nav='{$me_use_nav}' where me_theme='{$_POST['theme']}' and me_code like '$_POST[me_code]%'";
				sql_query($sql,false);
			}

			$set = "
				me_order	= '{$_POST['me_order']}',
				me_icon		= '{$_POST['me_icon']}',
				me_name		= '{$_POST['me_name']}',
				me_path		= '{$_POST['me_path']}',
				me_type		= '{$me_info['me_type']}',
				me_pid		= '{$me_info['me_pid']}',
				me_link		= '{$me_info['me_link']}',
				me_target	= '{$_POST['me_target']}',
				me_use		= '{$_POST['me_use']}',
				me_use_nav	= '{$me_use_nav}'
			";

			$update = "update {$g5['eyoom_menu']} set $set where me_theme='{$_POST['theme']}' and me_code='{$_POST['me_code']}'";
			sql_query($update,false);

			// 메뉴명이 바뀐경우
			if($_POST['me_name'] != $_POST['me_name_prev']) {
				$sql = "select me_id, me_path, me_code from {$g5['eyoom_menu']} where me_theme='{$_POST['theme']}' and me_code like '$_POST[me_code]%'";
				$res = sql_query($sql,false);
				$depth = strlen($_POST['me_code'])/3 - 1;
				for($i=0;$row=sql_fetch_array($res);$i++) {
					unset($path,$_path);
					$path = explode(">",$row['me_path']);
					foreach($path as $key => $path_name) {
						if($key == $depth) $path_name = $_POST['me_name'];
						$_path[$key] = trim($path_name);
					}
					$new_path = implode(" > ",$_path);
					sql_query("update {$g5['eyoom_menu']} set me_path='{$new_path}' where me_id='{$row['me_id']}'");
				}
			}

			// 보이기, 감추기 서브에도 일괄적용
			if($_POST['me_use'] == 'n') {
				$sql = "update {$g5['eyoom_menu']} set me_use = '{$_POST['me_use']}' where me_theme='{$_POST['theme']}' and me_code like '{$_POST['me_code']}%'";
				sql_query($sql,false);
			}
		}
		$back = "./menu_list.php?thema={$_POST['theme']}&id={$_POST['me_code']}";
		$msg = "메뉴설정을 적용하였습니다.";
		break;

	case "delete":
		if(!$_POST['me_code']) alert("잘못된 접근입니다.");
		if(!$_POST['theme']) alert("잘못된 접근입니다.");
		$sql = "delete from {$g5['eyoom_menu']} where me_theme='{$_POST['theme']}' and me_code like '{$_POST['me_code']}%'";
		sql_query($sql,false);
		$back = "./menu_list.php?thema={$_POST['theme']}";
		$msg = "선택한 메뉴 및 하위메뉴를 삭제하였습니다.";
		break;
}


?>
<script>
alert("<?php echo $msg;?>");
parent.document.location.href='<?php echo $back;?>';
</script>