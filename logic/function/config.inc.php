<?php

/**
 * FileName: config.inc.php
 * Description: 项目配置文件
 * Author: kim
 * Date: 2013-3-11 11:55:13
 * Version: 1.00
 **/
define("DEBUG", 0);				      								//开启调试模式 1 开启 0 关闭
define("ISWRITE", 1);				      							//开启日志模式
define("DRIVER","mysqli");				      						//数据库的驱动，本系统支持mysql(默认)和mysqli两种
//define("DSN", "mysql:host=localhost;dbname=brophp"); 				//如果使用PDO可以使用，不使用则默认连接MySQL
define("HOST", "localhost");			      						//数据库主机
define("USER", "phpuser");                               				//数据库用户名
define("PASS", "yfphpweb2013@)!#");                                  				//数据库密码
// define("HOST", "183.60.41.229");			      						//数据库主机
// define("USER", "wm_phpuser");                               				//数据库用户名
// define("PASS", "wm_49_php_user_2014_8Hs3yTHGH");                                  				//数据库密码
define("DBNAME","game_base");			      						//数据库名
define("LOPATH","/data0/yanfa/php_home/76wan/");
define("JURL","http://yxht.aofyx.com/log/jishi2bu/json.php");				//即时json数据路径
define("TPATH", "/data0/yanfa/php_home/log/tools/");												//道具配置文件路径
define("ITEM", "/data0/yanfa/php_home/zhanshen/game/");
define("LPATH", "/data0/yanfa/php_home/log/");


$t_conf = array(
					"192.168.0.14" => array("ip" => "192.168.0.14","db"=>"troh_game", "user" => "php", "password" => "phE5sR#p", "port" => "3306"),
					"192.168.0.141" => array("ip" => "192.168.0.141","db"=>"game1", "user" => "root", "password" => "", "port" => "3306"),
					"s1" => array("ip" => "119.120.92.55","db"=>"game", "user" => "wm_phpuser", "password" => "wm_49_php_user_2013_8Hs3yTHGH", "port" => "3308")
					// "zs" => array("ip" => "203.195.182.47","db"=>"game", "user" => "jianhh12", "password" => "12wm_zhans_2014_tc_jianhh", "port" => "3307")
				
				);

$gm_db = array("ip" => "10.10.10.228", "db"=>"game", "user" => "zhanshen", "password" => "ebdIOG58wsYvmEWC", "port" => "3306");	

$chongzhi = array("ip"=> "10.10.10.228", "db"=>"chongzhi","user" => "wm_reader", "password" => "wm_49_php_user_2013_8Hs3yTHGH",'port'=>3307
				);
							
