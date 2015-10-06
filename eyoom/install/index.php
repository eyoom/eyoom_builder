<?php
include_once ('../../config.php');
include_once ('./install.head.php');
$dbconfig_file = '../..'.G5_DATA_PATH.'/'.G5_DBCONFIG_FILE;
include_once($dbconfig_file);

$theme_path = '../theme/';

$handle = @opendir($theme_path);
while ($file = @readdir($handle)) {
	if($file == '.'||$file == '..') continue;
	$result_array[] = $file;
}
@closedir($handle);

if(is_writable($theme_path) && count($result_array) > 0) {
	foreach($result_array as $key => $dir) {
		if($dir == 'basic') $theme_dir['basic'] = true;
		if($dir == 'pc_basic') $theme_dir['pc_basic'] = true;
	}
	$theme_count = count($theme_dir);
	if($theme_count == 2) {
		$ins_type = 'c'; //choice
	} else if($theme_count == 1) {
		if(isset($theme_dir['basic']) && $theme_dir['basic']) $ins_type = 'b'; // basic
		if(isset($theme_dir['pc_basic']) && $theme_dir['pc_basic']) $ins_type = 'p'; // pc_basic
	} else {
?>
<h1>이윰빌더를 설치하기 위해서는 테마가 필요합니다.</h1>

<div class="ins_inner">
    <p>이윰빌더(Eyoom Builder)를 설치하시려면 반드시 테마를 다운로드 하시고, 서버에 업로드하셔야 합니다.</p>
</div>
<div class="ins_inner">
    <div class="inner_btn">
        <a href="../../">다시 시도하기</a>
    </div>
</div>
<?php
		exit;
	}
?>
<h1>EYOOM BUILDER <b>INSTALLATION</b></h1>

<form action="./install.php" method="post">
<div class="ins_inner">
    <p>
        <strong>이윰빌더는 영카트5 기반의 CMS(Contents Management System)입니다. </strong><br><br>
		<ul>
			<li>이윰빌더는 영카트5 원본을 전혀 수정하지 않기 때문에 향후 영카트5의 업그레이드가 용이합니다.</li>
			<li>또한 이윰빌더는 프로그램영역과 디자인 영역이 분리되어 있어 테마의 수정, 변경 및 제작을 쉽게 하실 수 있습니다.</li>
			<li>이윰빌더의 라이센스는 영카트5의 라이센스에 종속됩니다.</li>
		</ul>

		<span class='uninstall'>&gt;&gt; 설치를 원하지 않으신다면 <?php echo G5_EXTEND_PATH.'/xeyoom.extend.php';?> 파일 및 업로드하신 Eyoom Builder 관련 파일들을 삭제하시면 됩니다.</span><br><br>

		<?php if($ins_type == 'c') { ?>
		<p>
		<strong>중요 [기본테마 선택하기]</strong><br>
		<select name="ins_theme">
			<option value='b'>베이직 테마 [반응형웹 테마]</option>
			<option value='p'>PC 베이직 테마 [PC/Mobile지원 테마]</option>
		</select>
		<br><br>
		</p>
		<?php } else { ?>
		<input type="hidden" name="ins_theme" value="<?php echo $ins_type; ?>">
		<?php } ?>

		<div class="caution"><b>※ 알림</b> <br>
		- 이윰빌더를 재설치하는 경우, 기존 테이블 정보를 그대로 유지할지 초기화시킬지 선택해 주세요.<br>
		- 처음 설치 시, 아래 체크박스를 체크하지 말고 바로 [설치하기] 를 진행해 주세요.
		</div>
		<div style="border:1px solid #ddd; padding:20px;background:#eee;margin:10px 0;">
			<input type="checkbox" name="table_rest" id="table_reset" value="y"> <label for="table_reset">이윰빌더 테이블 (<?php echo G5_TABLE_PREFIX;?>eyoom_xxxx)의 정보를 초기화합니다. (<b style='color:#f30;'>중요</b> : 체크 시, 이윰빌더용 테이블이 초기화됩니다. 반드시 이윰빌더 관련 테이블을 백업하신 후 진행해 주세요.)</label>
		</div>
    </p>
</div>

<div class="ins_inner">
    <div class="inner_btn">
        <input type="submit" value="설치하기">
    </div>
</div>

</form>

<?php
} else {
?>
<h1>EYOOM BUILDER <b>INSTALLATION</b></h1>

<div class="ins_inner">
    <p>
        <strong>이윰빌더는 영카트5 기반의 CMS(Contents Management System)입니다. </strong><br><br>

		<div class="caution">
		<?php 
			if(count($result_array) == 0) {
		?>
		- 먼저 이윰빌더 베이직 테마를 업로드해 주셔야 합니다.<br>
		- 이윰빌더 베이직 테마는 <a href="http://eyoom.net/shop/list.php?ca_id=1010" target="_blank">이윰넷 > 이유몰 > 테마</a> 에서 최신 버전의 Basic 테마를 자유롭게 다운로드 받으실 수 있습니다.<br><br>
		<?php
			}
			if(!is_writable($theme_path)) {
		?>
		- 베이직 테마를 업로드하셨다면 테마폴더(테마폴더내의 모든 하위 폴더 및 파일까지)의 권한을 0707 변경하셔야 합니다.<br>
		- 아래의 명령을 실행시키시고 브라우저를 새로고침해 주세요.
		</div>
		<div style="border:1px solid #ddd; padding:20px;background:#eee;margin:10px 0;">
			$> chmod 707 -R eyoom/theme <span style="color:#999;">또는</span> chmod uo+rwx -R eyoom/theme<br />
		</div>
		<?php
			}
		?>
    </p>
</div>

<?php
}
include_once ('./install.tail.php');
?>