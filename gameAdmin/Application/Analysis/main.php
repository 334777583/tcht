<?php
header ( "Content-type:text/html;charset=utf-8" );
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆ All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-25
// +----------------------------------------------------------------------

ini_set ( 'memory_limit', '-1' );//不限内存
ini_set ( 'max_execution_time', '-1' );//不限执行时间

require_once '../../analysis/config.php'; // 加载配置文件
require_once '../../analysis/function.php'; // 加载自定义函数
require_once '../../analysis/mysqli.php'; // 加载数据库类

$dbList = array (); // 所有服务器
$dbList = M ( $DBconfig ['game_admin'] )->fquery ( "select * from gamedb" );

if (! empty ( $dbList )) {
	writeFile ( "《====================start====================》" );
	foreach ( $dbList as $db ) {
		if (is_dir ( LOG_PATH . $db ['g_ip'] )) {
			writeFile ( "[INFO] [" . $db ['g_ip'] . " analysis start!]" );
			$starttime = microtime_float ();//分析开始时间
			
			
			
			$endtime = microtime_float ();//分析结束时间
			writeFile ( "[INFO] [" . $db ['g_ip'] . " analysis finish!time:" . ($endtime - $starttime) . "]" );
		} else {
			writeFile ( "[WARNING] [The server log file exist(" . $db ['g_ip'] . ")]" );
		}
	}
	
	pay_detail($DBconfig);//充值记录分析
	writeFile ( "《====================end====================》" );
} else {
	writeFile ( "[WARNING] [no db list!]" );
}