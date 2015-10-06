<?php
class shop
{
	public function __construct() {
	}

	// 쇼핑몰 분류관리의 메뉴 생성
	public function menu_create() {
		global $g5;

        // 1단계 분류 판매 가능한 것만
        $hsql = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where length(ca_id) = '2' and ca_use = '1' order by ca_order, ca_id ";
        $hresult = sql_query($hsql);
        $gnb_zindex = 999; // gnb_1dli z-index 값 설정용

        for ($i=0; $row=sql_fetch_array($hresult); $i++) {
            $gnb_zindex -= 1; // html 구조에서 앞선 gnb_1dli 에 더 높은 z-index 값 부여
            // 2단계 분류 판매 가능한 것만
            $sql2 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where LENGTH(ca_id) = '4' and SUBSTRING(ca_id,1,2) = '{$row['ca_id']}' and ca_use = '1' order by ca_order, ca_id ";
            $result2 = sql_query($sql2);
            $count = mysql_num_rows($result2);

			$menu[$i] = $row;
			$menu[$i]['href'] = G5_SHOP_URL.'/list.php?ca_id='.$row['ca_id'];
			$menu[$i]['count'] = $count;

			$loop = &$menu[$i]['submenu'];
			for ($j=0; $row2=sql_fetch_array($result2); $j++) {
				$row2['href'] = G5_SHOP_URL.'/list.php?ca_id='.$row2['ca_id'];
				$loop[$j] = $row2;
			}
			$menu[$i]['cnt'] = count($loop);
		}
		return $menu;
	}

	// 상품리스트 화면에서 상단 카테고리 네비
	public function get_navigation() {
		global $g5, $ca_id;

		if ($ca_id) {
			$navigation = "";
			$len = strlen($ca_id) / 2;
			for ($i=1; $i<=$len; $i++) {
				$code = substr($ca_id,0,$i*2);

				$sql = " select ca_name from {$g5['g5_shop_category_table']} where ca_id = '$code' ";
				$row = sql_fetch($sql);

				$sct_here = '';
				if ($ca_id == $code) // 현재 분류와 일치하면
					$sct_here = 'sct_here';

				if ($i != $len) // 현재 위치의 마지막 단계가 아니라면
					$sct_bg = 'sct_bg';
				else $sct_bg = '';

				$navigation .= '<a href="./list.php?ca_id='.$code.'" class="'.$sct_here.' '.$sct_bg.'">'.$row['ca_name'].'</a>';
			}
		}
		else $navigation = $g5['title'];

		return $navigation;
	}

	public function get_navi($ca_id) {
		global $g5;

		if ($ca_id) {
			$navigation = "";
			$len = strlen($ca_id) / 2;
			for ($i=1; $i<=$len; $i++) {
				$code = substr($ca_id,0,$i*2);

				$sql = " select ca_name from {$g5['g5_shop_category_table']} where ca_id = '$code' ";
				$row = sql_fetch($sql);

				$sct_here = '';
				if ($ca_id == $code) // 현재 분류와 일치하면
					$sct_here = 'active';

				$navigation .= '<li class="'.$sct_here.'"><a href="./list.php?ca_id='.$code.'">'.$row['ca_name'].'</a></li>';
				if($i == $len) {
					$nav['title'] = $row['ca_name'];
				}
			}
		} else {
			$nav['title'] = $g5['title'];
			$navigation = $g5['title'];
		}
		$nav['path'] = $navigation;

		return $nav; 
	}

	public function listcategory($type='') {
		global $g5, $ca_id;
		switch($type) {
			default :
				$listcategory = '';
				$ca_id_len = strlen($ca_id);
				$len2 = $ca_id_len + 2;
				$len4 = $ca_id_len + 4;

				$sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where ca_id like '$ca_id%' and length(ca_id) = $len2 and ca_use = '1' order by ca_order, ca_id ";
				$result = sql_query($sql);
				$i=0;
				while ($row=sql_fetch_array($result)) {

					$row2 = sql_fetch(" select count(*) as cnt from {$g5['g5_shop_item_table']} where (ca_id like '{$row['ca_id']}%' or ca_id2 like '{$row['ca_id']}%' or ca_id3 like '{$row['ca_id']}%') and it_use = '1'  ");

					$listcategory[$i]['href'] = "./list.php?ca_id=".$row['ca_id'];
					$listcategory[$i]['ca_name'] = $row['ca_name'];
					$listcategory[$i]['cnt'] = $row2['cnt'];
					$i++;
				}
				return $listcategory;
			break;
		}
	}

	// 상품상세보기 앵커 - 스코핑
	public function pg_anchor($anc_id) {
		global $tpl, $eyoom, $tpl_name, $default,$item_use_count, $item_qa_count, $item_relation_count;

		$tpl->define($anc_id, 'skin_'.$tpl_name.'/shop/' . $eyoom['shop_skin'] . '/pg_anchor.skin.html');
		$tpl->setScope($anc_id);
		$tpl->assign(array(
			'anc_id' => $anc_id,
			'default' => $default,
			'item_use_count' => $item_use_count,
			'item_qa_count' => $item_qa_count,
			'item_relation_count' => $item_relation_count,
		));
		$tpl->setScope();
	}

	public function item_icon($it) {

		global $g5;

		$icon = '<div class="rgba-banner-area">';
		// 품절
		if (is_soldout($it['it_id']))
			$icon .= '<div class="shop-rgba-dark rgba-banner">Soldout</div>';

		if ($it['it_type1'])
			$icon .= '<div class="shop-rgba-dark rgba-banner">Hit</div>';

		if ($it['it_type2'])
			$icon .= '<div class="shop-rgba-yellow rgba-banner">Good</div>';

		if ($it['it_type3'])
			$icon .= '<div class="shop-rgba-red rgba-banner">New</div>';

		if ($it['it_type4'])
			$icon .= '<div class="shop-rgba-green rgba-banner">Best</div>';

		if ($it['it_type5'])
			$icon .= '<div class="shop-rgba-purple rgba-banner">Sale</div>';

		// 쿠폰상품
		$sql = " select count(*) as cnt
					from {$g5['g5_shop_coupon_table']}
					where cp_start <= '".G5_TIME_YMD."'
					  and cp_end >= '".G5_TIME_YMD."'
					  and (
							( cp_method = '0' and cp_target = '{$it['it_id']}' )
							OR
							( cp_method = '1' and ( cp_target IN ( '{$it['ca_id']}', '{$it['ca_id2']}', '{$it['ca_id3']}' ) ) )
						  ) ";
		$row = sql_fetch($sql);
		if($row['cnt'])
			$icon .= '<div class="shop-rgba-orange rgba-banner">Coupon</div>';

		$icon .= '</div>';

		return $icon;
	}

	// 상품 파일 업로드
	public function it_file_upload($srcfile, $filename, $dir) {
		if($filename == '') return '';

		if(!is_dir($dir)) {
			@mkdir($dir, G5_DIR_PERMISSION);
			@chmod($dir, G5_DIR_PERMISSION);
		}

		$pattern = "/[#\&\+\-%@=\/\\:;,'\"\^`~\|\!\?\*\$#<>\(\)\[\]\{\}]/";
		$filename = preg_replace("/\s+/", "", $filename);
		$filename = preg_replace( $pattern, "", $filename);
		$filename = preg_replace_callback("/[가-힣]+/", create_function('$matches', 'return base64_encode($matches[0]);'), $filename);
		$filename = preg_replace( $pattern, "", $filename);
		upload_file($srcfile, $filename, $dir);
		$file = str_replace(G5_DATA_PATH.'/item/', '', $dir.'/'.$filename);

		return $file;
	}

}

?>