<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆ All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-21
// +----------------------------------------------------------------------
// | Describe: 系统公用配置文件
// +----------------------------------------------------------------------
return array (
		// 后台分析数据库配置前缀
		'GAME_ANALYSIS_PREFIX' => 'game',
		
		// 日记文件所在路径
		'GAME_LOG_PATH' => WEB_ROOT . '/data/192.168.0.64/2014-07-29/',
		
		// 游戏数据库配置前缀
		'GAME_DATA_PREFIX' => 'gamedatabase',
		
		// 充值数据库
		'chongzhi' => array (
				"host" => "127.0.0.1",
				"database" => "chongzhi",
				"user" => "root",
				"password" => "",
				'port' => 3306 
		),
		
		//后台分析数据保存数据库配置
		'game1' => array (
				"host" => "127.0.0.1",
				"database" => "game1",
				"user" => "root",
				"password" => "",
				'port' => 3306 
		),
		'game2' =>array (
				"host" => "127.0.0.1",
				"database" => "game2",
				"user" => "root",
				"password" => "",
				'port' => 3306 
		),
		'game3' => array (
				"host" => "127.0.0.1",
				"database" => "game3",
				"user" => "root",
				"password" => "",
				'port' => 3306 
		),
		'game64' => array (
				"host" => "127.0.0.1",
				"database" => "game64",
				"user" => "root",
				"password" => "",
				'port' => 3306 
		),
		
		//游戏后端数据库配置
		'gamedatabase1' => array (
				"host" => "127.0.0.1",
				"database" => "gamedatabase1",
				"user" => "root",
				"password" => "",
				'port' => 3306 
		),
		'gamedatabase2' => array (
				"host" => "127.0.0.1",
				"database" => "gamedatabase2",
				"user" => "root",
				"password" => "",
				'port' => 3306 
		),
		'gamedatabase3' => array (
				"host" => "127.0.0.1",
				"database" => "gamedatabase3",
				"user" => "root",
				"password" => "",
				'port' => 3306
		),
		'gamedatabase64' => array (
				"host" => "192.168.0.11",
				"database" => "game",
				"user" => "phptest",
				"password" => "phptest",
				'port' => 3306
		),
	
	    /* 模块相关配置 */
	    'DEFAULT_MODULE' => 'Admin',
		'MODULE_DENY_LIST' => array (
				'Common',
				'Analysis' 
		),
		// 'MODULE_ALLOW_LIST' => array('Home','Admin'),
		
		/* 系统数据加密设置 */
		'DATA_AUTH_KEY' => 'd:m-jUFkr]ee\Q|0./CJA6RyBi(PMaYeW)KT`N,sZS<', // 默认数据加密KEY
		
		/* 调试配置 */
		'SHOW_PAGE_TRACE' => true,

	   	/* 全局过滤配置 */
	    'DEFAULT_FILTER' => 'trim', // 全局过滤函数
		
		/* SESSION 和 COOKIE 配置 */
		'SESSION_PREFIX' => 'tpgame', // session前缀
		'COOKIE_PREFIX' => 'tpgame'  // Cookie前缀 避免冲突
);