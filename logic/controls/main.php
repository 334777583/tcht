<?php
/**
   * FileName		: main.php
   * Description	: 分析日记入口文件
   * Author	    : zwy
   * Date			: 2014-6-14
   * Version		: 1.00
   */
ini_set('memory_limit','-1');
ini_set('max_execution_time', '-1');

require('config.inc.php');
require('function.inc.php');
require('dbmysqli.class.php');

//所有服务器
$GameBase = D('game_base');
$dbList = $GameBase -> table('gamedb') -> where('g_flag = 1') -> select();
unset($GameBase);

$GLOBALS['date'] = date('Y-m-d', strtotime('-1 day'));				//默认分析前一天数据
$GLOBALS['datetime'] = date('Y-m-d H:i:s', strtotime('-1 day'));

if(!empty($dbList)) {
	
	foreach($dbList as $db) {
		
		if(is_dir(CATALOG.$db['g_ip'])) {
			$starttime = microtime_float();
			writeFile("[". $db['g_ip'] . " start!]");
			$Database = D(DATABASE_PREIFX.$db['g_id']);				//连接的数据库句柄
			
			$path = CATALOG . $db['g_ip'] . '/' . $GLOBALS['date'] . '/';	//日志文件所在目录路径
			log_analyse($Database, $path);					//日志文件分析开始
			
			water_log_analyse_01($Database, $path,$db['g_id']);
			water_log_analyse($Database, $path);//流水日志分析
			
			data_analyse($Database,$db['g_id']);						//数据分析开始
			
			shop_consume($Database, $path);					//商城消费概况
			money_trade($Database, $path);						//货币交易记录
			action_count($Database, $path);						//活动统计
			task_count($Database, $path);						//任务统计
			refin_count($Database, $path);						//炼化统计
			handle_count($Database, $path);						//其他操作统计
			consume_count($Database, $path);						//元宝消费统计
			chongzhi_count();									//充值数据分析
			
			$endtime = microtime_float();
			writeFile("[". $db['g_ip'] . " finish!time:".($endtime-$starttime)."]");
		}else{
			writeFile("[The server log file exist :". $db['g_ip'] . "]");
		}
	}

}