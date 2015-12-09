<?php
$sub_menu = '800100';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');
auth_check($auth[$sub_menu], "r");

$g5['title'] = '그누포인트 압축하기';

if($_POST['w'] == 'u') {
	// 테이블 백업
	if($_POST['backup_use'] == 'y') {
		$backup_point_table = G5_TABLE_PREFIX . 'point_' . date('YmdHis');
		
		$sql = "DROP TABLE IF EXISTS {$backup_point_table}";
		sql_query($sql);
		
		$sql = "
			CREATE TABLE IF NOT EXISTS {$backup_point_table} (
				po_id int(11) NOT NULL auto_increment,
				mb_id varchar(20) NOT NULL default '',
				po_datetime datetime NOT NULL default '0000-00-00 00:00:00',
				po_content varchar(255) NOT NULL default '',
				po_point int(11) NOT NULL default '0',
				po_use_point int(11) NOT NULL default '0',
				po_expired tinyint(4) NOT NULL default '0',
				po_expire_date date NOT NULL default '0000-00-00',
				po_mb_point int(11) NOT NULL default '0',
				po_rel_table varchar(20) NOT NULL default '',
				po_rel_id varchar(20) NOT NULL default '',
				po_rel_action varchar(255) NOT NULL default '',
				PRIMARY KEY  (po_id),
				KEY index1 (mb_id,po_rel_table,po_rel_id,po_rel_action),
				KEY index2 (po_expire_date)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8
		";
		sql_query($sql);
		
		$sql = "INSERT INTO {$backup_point_table} SELECT * FROM {$g5['point_table']}";
		sql_query($sql);
	}
	
	$zdate 	= $_POST['zip_date'];
	$limit 	= $_POST['zip_count'];
	$items	= $_POST['zip_item'];
	
	$pdate = $zdate . '000000';
	
	$where = " po_datetime < date_format({$pdate}, '%Y-%m-%d 23:59:59') ";
	$sql = "
		SELECT mb_id, count(po_point) as cnt, sum(po_point) as sum 
		FROM {$g5['point_table']} 
		WHERE {$where}
		GROUP BY mb_id HAVING cnt > '{$items}'
		ORDER BY cnt desc
	";
	if($limit != 0) $sql .= " LIMIT {$limit} ";
	
	$result = sql_query($sql);
	
	for($i=0, $pcnt = 0, $mcnt = 0; $row = sql_fetch_array($result); $i++) {
		$w = $where . " and mb_id = '{$row['mb_id']}' ";
		
		// 압축대상 내역은 삭제처리
		$delete = "DELETE FROM {$g5['point_table']} WHERE {$w}";
		sql_query($delete);
		
		// 압축 레코드 입력
		insert_point($row['mb_id'], $row['sum'], date("Y년 m월 d일") . ' 포인트내역 압축', '@pointzip', $row['mb_id'], $member['mb_id'].'-'.uniqid(''));
		
		// 압축한 건수를 더함
		$pcnt += $row['cnt'];
		$mcnt++;
	}
}

include_once(G5_PATH.'/head.sub.php');
?>
<link rel="stylesheet" href="./css/calendar.css">
<script src="./js/calendar.js"></script>
<div id="wrapper" style="min-width:100%;">

    <div id="container" style="min-width:100%;">

        <h2>그누포인트 압축하기</h2>
		<link rel="stylesheet" href="./css/eyoom_admin.css">
		<form name="fpointzip" action="./pointzip_form.php" onsubmit="return fpointzip_check(this)" method="post">
		<input type="hidden" name="w" value="u">
		<section id="anc_scf_info">
		<div class="tbl_frm01 tbl_wrap">
			<table>
			<caption>압축조건 설정</caption>
			<colgroup>
				<col class="grid_3">
				<col>
			</colgroup>
			<tbody>
			<?php if(!$_POST['w']) {?>
			<tr>
				<th scope="row"><label for="backup_use">테이블 백업</label></th>
				<td>
					<label for="backup_use"><input type="checkbox" name="backup_use" value="y" id="backup_use" checked="checked"> 사용 (체크시, <?php echo $g5['point_table']; ?> 테이블을 <?php echo $g5['point_table']; ?>_YmdHis 형식으로 백업)</label>
				</td>
			</tr>
			<?php } else {?>
			<tr>
				<th scope="row"><label for="backup_use">처리상태</label></th>
				<td>
					압축회원수 : <?php echo number_format($mcnt);?> 명 (압축회원수가 0이 될때까지 적용하시면 됩니다.)<br>
					압축된 포인트 내역 : <?php echo number_format($pcnt);?> 건<br>
				</td>
			</tr>
			<?php }?>
			<tr>
				<th scope="row"><label for="zip_date">압축일자 지정</label></th>
				<td>
					<input type="text" name="zip_date" id="zip_date" class="frm_input" size="15" value="<?php if($w == 'u') echo $zdate; else echo date('Ymd', strtotime('-1day')); ?>" onclick="calendar(event);">
					일을 포함한 이전 포인트 내역을 압축합니다.
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="zip_count">단위 압축회원수</label></th>
				<td>
					<input type="text" name="zip_count" id="zip_count" class="frm_input" size="5" value="<?php if($w == 'u') echo $limit; else echo '200' ?>"> 명 (DB에 부하를 줄 수 있기 때문에 적당히 숫자를 조절해 주세요.)<br><br>
					전체 일괄작업시, 숫자 '0' 입력
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="zip_item">포인트 내역수</label></th>
				<td>
					포인트 내역이 <input type="text" name="zip_item" id="zip_item" class="frm_input" size="5" value="<?php if($w == 'u') echo $items; else echo '10' ?>"> 건 이상인 회원만 압축하기(최소 10건 이상)
				</td>
			</tr>
			</table>

		</section>

		<div class="btn_confirm01 btn_confirm">
			<input type="submit" value="압축하기" class="btn_submit" accesskey="s">
			<a href="javascript:self.close();">창닫기</a>
		</div>
		</form>
	</div>
</div>
<script>
function fpointzip_check(f) {
	if($("#zip_date").val().length != '8') {
		alert('압축일자를 정확히 입력해 주세요.');
		f.zip_date.focus();
		return false;
	}
	var yday = '<?php echo date("Ymd");?>';
	if(parseInt($("#zip_date").val()) >= parseInt(yday)) {
		alert('압축일자는 오늘 이전의 날짜만 입력 가능합니다..');
		f.zip_date.focus();
		return false;
	}
	if($("#zip_count").val() == '') {
		alert('단위 압축회원수를 입력해 주세요.');
		f.zip_count.focus();
		return false;
	}
	if($("#zip_item").val() == '' || parseInt($("#zip_item").val()) < 10) {
		alert('포인트 내역수는 10보다 큰 정수여야 합니다.');
		f.zip_item.focus();
		return false;
	}
	if(!confirm("정말로 그누포인트를 압축하실 건가요?")) return false;
}
</script>
</body>
</html>
