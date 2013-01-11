<?php
class pagination {
	public function __construct() {
	}

	public function calculate_pages($total_rows, $rows_per_page, $page_num, $amountofblocks=5) {
		$arr = array();
		// calculate last page
		$last_page = ceil($total_rows / $rows_per_page);
		// make sure we are within limits
		$page_num = (int)$page_num;
		if ($page_num < 1) {
			$page_num = 1;
		} elseif ($page_num > $last_page) {
			$page_num = $last_page;
		}
		$upto = ($page_num - 1) * $rows_per_page;
		if ($upto<0)$upto=0;
		$arr['limit'] = 'LIMIT ' . $upto . ',' . $rows_per_page;
		$arr['current'] = $page_num;
		if ($page_num == 1) $arr['previous'] = $page_num; else
			$arr['previous'] = $page_num - 1;
		if ($page_num == $last_page) $arr['next'] = $last_page; else
			$arr['next'] = $page_num + 1;
		$arr['last'] = $last_page;
		$arr['info'] = 'Page (' . $page_num . ' of ' . $last_page . ')';
		$arr['pages'] = $this->get_surrounding_pages($page_num, $last_page, $arr['next'], $amountofblocks);
		return $arr;
	}

	function get_surrounding_pages($page_num, $last_page, $next, $amountofblocks) {
		$arr = array();
		$show = $amountofblocks; // how many boxes
		// at first
		if ($page_num == 1) {
			// case of 1 page only
			if ($next == $page_num) return array(1);
			for ($i = 0; $i < $show; $i++) {
				if ($i == $last_page) break;
				$arr[] = array("p"=>$i + 1);
			}
			return $arr;
		}
		// at last
		if ($page_num == $last_page) {
			$start = $last_page - $show;
			if ($start < 1) $start = 0;
			for ($i = $start; $i < $last_page; $i++) {
				$arr[] = array("p"=>$i + 1);
			}
			return $arr;
		}

		// at middle
		$start = $page_num - $show / 2;
		if ($start < 1) $start = 0;
		if ($last_page - $page_num == 1) {
			if (floor($start)>0) $arr[] = array("p"=> floor($start));
		}
		for ($i = $start; $i < $page_num; $i++) {
			if (floor($i + 1) > 0) $arr[] = array("p"=> floor($i + 1));
		}

		for ($i = ($page_num + 1); $i < ($page_num + $show / 2 + 1); $i++) {
			if ($i == ($last_page + 1)) break;
			$arr[] = array("p"=> floor($i));
		}

		$a = array();
		foreach ($arr as $item){
			if (count($a)< $show) $a[] = $item;
		}
		$arr = $a;

		$max = ($show - $last_page > 0)? $last_page : $show;
		$sides = floor(($show - 1) / 2);

		if ($page_num > $last_page - floor(($show-1) / 2)){
			/*$arr['bingo_last']= array(
				"side" => $sides,
				"max"  => $max,
				"count"=> count($arr),
				"diff" => $max - count($arr)
			);*/
			$add = array();

			$g = 0;
			for ($i = $arr[0]['p']-1; $i >= 1; $i--) {
				if ($g++ < $max - count($arr))	$add[] = array("p"=> floor($i));
			}
			$add = array_reverse($add);
			$bleh = array();
			//$arr['bingo_last']['add'] = $add;
			foreach ($add as $item)$bleh[] = $item;
			foreach ($arr as $item)$bleh[] = $item;

//test_array($bleh);
			$arr = $bleh;
		}
		if ($page_num < floor(($show-1) / 2)){
/*
			$arr['bingo_first'] = array(
				"side" => $sides,
				"max"  => $max,
				"count"=> count($arr),
				"diff" => $max - count($arr)
			);*/
			for ($i = count($arr)+1; $i <= $max; $i++) {
				$arr[] = array("p"=> floor($i));
			}

		}


		//test_array($a);
		return $arr;
	}
}
