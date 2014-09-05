<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆ All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-25
// +----------------------------------------------------------------------
define ( 'ANALYSIS_FILE', './analysis.log' ); // 记录程序执行状态日记文件路径
define('LOG_PATH', '../data/');//后端生成日记所在目录
define("JSON_PATH",  '../Public/json/');		//装备属性等json文件路径

define("INSERT_NUM", 5000);//每隔多少条记录插一次数据库（默认5000）

define('GAME_ANALYSIS_PREFIX', 'game');//后台分析数据库配置前缀
define('GAME_DATA_PREFIX', 'gamedatabase');//游戏数据库配置前缀
                                              
// define('NOW_TIME', time());//当前时间戳（正式）
define ( 'NOW_TIME', strtotime ( '2014-08-14 12:12:12' ) ); // 当前时间戳（测试）
define ( 'DATE', date ( 'Y-m-d', NOW_TIME-24*60*60 ) ); // 前一天日期
define ( 'DATE_TIME', date ( 'Y-m-d H:i:s', NOW_TIME-24*60*60 ) ); // 前一天时间

// 数据库配置
$DBconfig = array (
		'game_admin' => array (
				'db_user' => 'root',
				'db_password' => '',
				'db_host' => '127.0.0.1',
				'db_name'=>'game_admin',
				'db_port'=>3306
		),
		'chongzhi' => array (
				'db_user' => 'root',
				'db_password' => '',
				'db_host' => '127.0.0.1',
				'db_name'=>'chongzhi',
				'db_port'=>3306
		),
		'game1'=>array(
				'db_user' => 'root',
				'db_password' => '',
				'db_host' => '127.0.0.1',
				'db_name'=>'game1',
				'db_port'=>3306
		),
		'game2'=>array(
				'db_user' => 'root',
				'db_password' => '',
				'db_host' => '127.0.0.1',
				'db_name'=>'game2',
				'db_port'=>3306
		),
		'game3'=>array(
				'db_user' => 'root',
				'db_password' => '',
				'db_host' => '127.0.0.1',
				'db_name'=>'game3',
				'db_port'=>3306
		)
);