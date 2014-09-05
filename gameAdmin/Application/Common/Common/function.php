<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-7-22
// +----------------------------------------------------------------------
// | Describe: 系统公共函数库
// +----------------------------------------------------------------------

/**
 * 原样输出print_r的内容
 * @param unknown $content 待print_r的内容
 */
function pre($content) {
	echo '<pre>';
	print_r($content);
	echo '</pre>';
}

function firePHP($content){
	import('Vendor.FirePHP.FirePHP');
	$fb = FirePHP::getInstance(true); 
	$fb->error(json_encode($content), "error");
}

/**
 * 数组排序--冒泡排序
 * @param array $arr	要排序的数组
 * @param string $sort	要排序的下标
 * @param string $order		升序还是降序，默认降序
 * @return array $arr	返回数组
 */
function array_sort($arr=array(),$sort='',$order='desc'){
	$num = count($arr);
	for($i = 1; $i < $num; $i ++) {
		for($j = $num - 1; $j >= $i; $j --){
			if (empty($sort)&&$order=='desc'){
				$status = $arr [$j] > $arr [$j - 1];
			}elseif (empty($sort)&&$order=='asc'){
				$status = $arr [$j] < $arr [$j - 1];
			}elseif ($order=='desc'){
				$status = $arr [$j][$sort] > $arr [$j - 1][$sort];
			}elseif ($order=='asc'){
				$status = $arr [$j][$sort] < $arr [$j - 1][$sort];
			}
			if ($status) {
				$x = $arr [$j];
				$arr [$j] = $arr [$j - 1];
				$arr [$j - 1] = $x;
			}
		}
	}
	return $arr;
}

/**
 * 把配置组合成pdo模式
 * @param array $config
 * @return string
 */
function getDbConfig($config=array()){
	return 'mysql://'.$config['user'].':'.$config['password'].'@'.$config['host'].':'.$config['port'].'/'.$config['database'];
}

/**
 * 检验一个字符串是否是日期格式
 * @param string $date
 * @return boolean
 */
function check_date($date) {
	$arrDate = explode ( "-", $date );
	if (count ( $arrDate ) != 3) {
		return false;
	} else {
		$year = $arrDate [0];
		$month = $arrDate [1];
		$day = $arrDate [2];
		if (checkdate ( $month, $day, $year )) {
			return true;
		} else {
			return false;
		}
	}
} 

/**
 * 记录后台用户操作行为
 * @param string $userName
 * @param int $serverId
 * @param int $type
 * @param string $remark
 */
function action_log($userName,$serverId,$type,$remark=''){
	$data = array (
			'serverid' => $serverId,
			'type' => $type,
			'remark' => $remark,
			'operate' => $userName,
			'datetime'=>date('Y-m-d H:i:s',NOW_TIME)
	);
	M('action_log')->add($data);
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
	if(function_exists("mb_substr"))
		$slice = mb_substr($str, $start, $length, $charset);
	elseif(function_exists('iconv_substr')) {
		$slice = iconv_substr($str,$start,$length,$charset);
		if(false === $slice) {
			$slice = '';
		}
	}else{
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("",array_slice($match[0], $start, $length));
	}
	return $suffix ? $slice.'...' : $slice;
}
