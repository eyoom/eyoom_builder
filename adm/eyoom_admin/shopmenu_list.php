<?php
$sub_menu = '800400';
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');
auth_check($auth[$sub_menu], "r");

$g5['title'] = '이윰환경설정';
include_once (G5_ADMIN_PATH.'/admin.head.php');

include './eyoom_theme.php';
$me_id = $_GET['id'];

$frm_submit = '
<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s">
    <a href="'.G5_URL.'">메인으로</a>
</div>
';
?>
<link rel="stylesheet" href="./css/eyoom_admin.css">
<link rel="stylesheet" type="text/css" href="./js/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="./js/themes/icon.css">
<link rel="stylesheet" type="text/css" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.css">
<script type="text/javascript" src="./js/jquery.easyui.min.js"></script>
<form name="ftheme" action="./menu_list_update.php" onsubmit="return ftheme_check(this)" method="post" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="mode" id="mode" value="skin">
<input type="hidden" name="theme" id="theme" value="<?php if($_theme) echo $_theme; else echo $theme;?>">
<input type="hidden" name="ref" id="ref" value="menu_list.php">

<?php include './theme_manager.php'; // 테마 메니저 ?>

<h2 class="h2_frm">[<b style='color:#f30;'><?php echo $_theme;?></b> 테마] 쇼핑몰 메뉴설정 <span class='exp'>테마별로 다른 메뉴를 구성하실 수 있습니다.</span></h2>
</form>
<section id="anc_scf_info">
    <div class="tbl_frm01 tbl_wrap">

		<table>
		<caption>메뉴설정</caption>
		<colgroup>
			<col class="grid_6">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<td scope="row" valign="top">
				<div style="margin:5px 0;">
					<a href="#" class="easyui-linkbutton" onclick="collapseAll()">전체닫기</a>
					<a href="#" class="easyui-linkbutton" onclick="expandAll()">전체열기</a>
					<div style="margin-top:5px;">
				    <select id="target_theme" name="target_theme">
					    <option value="">:: 선택 ::</option>
					    <?php 
						    if(is_array($exist_theme)) {
							    foreach($exist_theme as $k => $tm) {
								    if($tm == $_theme) continue;
								    echo '<option value="'.$tm.'">'.$tm.'</option>';
							    }
						    }
					    ?>
				    </select>테마로
				    <a href="javascritp:;" class="easyui-linkbutton" onclick="return clone_menu()">메뉴복사</a>
					</div>
				</div>
				<div style="margin:10px 0;"></div>
				<div class="easyui-panel" style="padding:5px">
					<ul id="menu" class="easyui-tree" data-options="url:'./menu_json.php?thema=<?php echo $_theme;?>&me_code=<?php echo $_id?>&me_shop=1',method:'get',animate:true,lines:true"></ul>
				</div>
			</td>
			<td valign="top">
				<h2 class="h2_frm"><b style='color:#f30;'>메뉴 등록/수정/삭제</b> <span class='exp' style='margin-left:10px;color:#777;font-weight:normal;font-size:11px;'>입력후 반드시 아래 적용하기 버튼을 누르세요</span></h2>
				<iframe id="ifrm_menu" name="ifrm_menu" src="shopmenu_form.php?thema=<?php echo $_theme;?><?php if($me_id) echo "&id=".$me_id;?>" scrolling="no" style="overflow-x:hidden;overflow:auto;width:100%;min-height:950px" frameborder=0></iframe>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
</section>
<script type="text/javascript">
	$(function(){
		$('#menu').tree('expandAll');
		$('#menu').tree({
			dnd: false,
			onDrop: function(targetNode, source, point){
				var targetId = $('#menu').tree('getNode', targetNode).id;
				var targetOrder = $('#menu').tree('getNode', targetNode).order;
				$.ajax({
					url: '...',
					type: 'post',
					dataType: 'json',
					data: {
						id: source.id,
						targetId: targetId,
						point: point
					}
				});
			},
			onClick: function(source){
				var url='shopmenu_form.php?thema=<?php echo $_theme;?>&id='+source.id;
				document.getElementById("ifrm_menu").contentWindow.location.href = url;
			}
		});
	});
	function collapseAll(){
		$('#menu').tree('collapseAll');
	}
	function expandAll(){
		$('#menu').tree('expandAll');
	}
	function clone_menu() {
		var theme = '<?php echo $_theme;?>';
		var target = $("#target_theme option:selected").val();
		if(!target) {
			alert('메뉴를 복사할 테마를 선택해 주세요.');
			return false;
		}
		if(confirm("선택한 ["+target+"]테마의 기존 설정된 쇼핑몰 메뉴는 모두 사라집니다.\n\n계속 진행할까요?")) {
			var url = "./menu_clone.php";
			$.post(url, {'theme':theme,'target':target,'me_shop':1}, function(data) {
				if(data.menu_clone == 'ok') {
					alert('정상적으로 쇼핑몰 메뉴를 복사하였습니다.');
					document.location.href = "./shopmenu_list.php?thema="+target;
				}
			},"json");
		} else return false;
	}
</script>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
