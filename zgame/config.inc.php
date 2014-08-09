<?php

/**
 * FileName: config.inc.php
 * Description: 项目配置文件
 * Author: kim
 * Date: 2013-3-11 11:55:13
 * Version: 1.00
 **/
define("DEBUG", 0);				      								//开启调试模式 1 开启 0 关闭
define("ISWRITE", 1);				      							//开启SQL日志模式
define("DRIVER","mysqli");				      						//数据库的驱动，本系统支持pdo(默认)和mysqli两种
//define("DSN", "mysql:host=localhost;dbname=brophp"); 				//如果使用PDO可以使用，不使用则默认连接MySQL
// define("HOST", "localhost");			      						//数据库主机
// define("USER", "phpuser");                               				//数据库用户名
// define("PASS", "yfphpweb2013@)!#");                                  				//数据库密码
define("HOST", "127.0.0.1");			      						//数据库主机
define("USER", "tcht");                               				//数据库用户名
define("PASS", "tcht_fuck");                                  				//数据库密码
define("DBNAME","game_base");			      						//数据库名
define("TABPREFIX", "");                           					//数据表前缀
define("PORT",3311);
if(!defined("CSTART")) define("CSTART", 0);                         //缓存开关 1开启，0为关闭
define("CTIME", 60*60*1);                          					//缓存时间
if(!defined("TPLPREFIX")) define("TPLPREFIX", "html");      		//模板文件的后缀名
if(!defined("TPLSTYLE"))  define("TPLSTYLE", "default");    		//默认模板存放的目录

//$memServers = array("localhost", 11211);	     					//使用memcache服务器
/*
如果有多台memcache服务器可以使用二维数组
$memServers = array(
		array("www.lampbrother.net", '11211'),
		array("www.brophp.com", '11211'),
		...
	);
*/
define("GNAME","game");			      								//游戏服前缀
define("B_LOGIC","http://203.195.158.150/tcht/logic/index.php");
//define("FURL","http://yxht.aofyx.com/analysis/ajax_shell.php");			//即时文件生成程序路径
define("FURL","http://203.195.158.150/log/jishi2bu/ajax.php");					//即时文件生成程序路径
define('DOWNLOAD','http://203.195.158.150/tcht/zgame/index.php/data/download/f/');
define("UPLOAD","/data/html/ht/tcht/zgame/public/uploads/");	
define("PORT",'3311');

