<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆ All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-25
// +----------------------------------------------------------------------

/**
 * 充值记录分析
 */
function pay_detail(&$DBconfig) {
	$starttime = microtime_float (); // 开始时间
	writeFile ( "[INFO] [insert into pay_detail start!]" );
	
	$ChongZhi = M ( $DBconfig ['chongzhi'] );
	$payServerList = $ChongZhi->fquery ( "select c_sid from chongzhi group by c_sid" );
	if (! empty ( $payServerList )) {
		$start = date ( 'Y-m-d H:i:s', strtotime ( DATE ) );
		$end = date ( 'Y-m-d H:i:s', strtotime ( DATE ) + 24 * 60 * 60 );
		foreach ( $payServerList as $pay ) {
			$serverId = $pay ['c_sid'];
			$sql = "select * from chongzhi where c_state=2 and c_time between '{$start}' and '{$end}' and c_sid=$serverId";
			$payDetail = $ChongZhi->fquery ( $sql );
			if (empty ( $payDetail )) {
				writeFile ( "[WARNING] [There is no pay data where serverId=$serverId!]" );
				continue;
			}
			
			$inserSql = "insert into pay_detail(p_result,p_ser,p_pt,p_acc,p_playid,p_order,p_money,p_type,p_acctime,p_reason,p_creatdate,p_insertdate) values ";
			foreach ( $payDetail as $k => $v ) {
				$inserSql .= "(";
				$inserSql .= "0,"; // 结果
				$inserSql .= $v ['c_sid'] . ","; // 服务器
				$inserSql .= "'".$v ['c_pf'] . "',"; // 平台
				$inserSql .= "'".$v ['c_openid'] . "',"; // 账号
				$inserSql .= $v ['c_pid'] . ","; // 玩家ID
				$inserSql .= "'".$v ['token_id'] . "',"; // 订单号
				$inserSql .= $v ['c_price'] * $v ['c_num'] . ","; // 充值金额
				$inserSql .= "'".$v ['c_pf'] . "',"; // 渠道
				$inserSql .= $v ['c_ts'] . ","; // 订单时间
				$inserSql .= "0,"; // 原因
				$inserSql .= "'".$v ['c_time'] . "',"; // 生成日期
				$inserSql .= "'".DATE . "'"; // 插入时间
				$inserSql .= "),";
			}
			
			$inserSql = trim ( $inserSql, ',' );
			$status = M ( $DBconfig [GAME_ANALYSIS_PREFIX . $serverId] )->rquery ( $inserSql );
			if (empty ( $status )) { // 添加失败
				writeFile ( "[ERROR] [insert into pay_detail fail where serverId=$serverId!]" );
			} else {
				writeFile ( "[INFO] [Success insert pay_detail where serverId=$serverId!]" );
			}
		}
	} else {
		writeFile ( "[WARNING] [No pay data!]" );
	}
	
	$endtime = microtime_float (); // 结束时间
	writeFile ( "[INFO] [insert into pay_detail finish!time:" . ($endtime - $starttime) . "]" );
}

/**
 * 实例化数据库对象
 *
 * @param array $DBconfig        	
 * @return object $db	数据库对象
 */
function M($DBconfig) {
	$db = new DB ( $DBconfig ['db_name'], $DBconfig ['db_host'], $DBconfig ['db_user'], $DBconfig ['db_password'], $DBconfig ['db_port'] );
	return $db;
}

/**
 * 写文件
 *
 * @param string $str        	
 * @param string $mode        	
 * @return boolean
 */
function writeFile($str, $mode = 'a+') {
	$str = '[' . date ( 'Y-m-d H:i:s' ) . '] ' . $str . "\r\n";
	$oldmask = @umask ( 0 );
	$fp = @fopen ( ANALYSIS_FILE, $mode );
	@flock ( $fp, 3 );
	if (! $fp) {
		return false;
	} else {
		@fwrite ( $fp, $str );
		@fclose ( $fp );
		@umask ( $oldmask );
		return true;
	}
}

/**
 * ription: 获取当前时间戳（精确到秒）
 *
 * @name : microtime_float
 * @return : float
 */
function microtime_float() {
	list ( $usec, $sec ) = explode ( " ", microtime () );
	return (( float ) $usec + ( float ) $sec);
}