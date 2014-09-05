<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-21
// +----------------------------------------------------------------------
// | Describe: 后台用Admin模块配置文件
// +----------------------------------------------------------------------

return array(
	//'配置项'=>'配置值'
	'WEB_title'=>'本地测试后台tp',
		
	//用户密码加密工具
	'USER_PASSWORD_KEY'=>'!@#@#',
		
	//游戏登录入口
	'GAME_LOGIN_ENTRANCE'=>'http://s2.app1101514477.qqopenapp.com/index.php',

	//超级用户id
	'SUPER_ADMIN_ID'=>1,
		
	//默认数据库配置信息
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => 'game_admin', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => '', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => '', // 数据库表前缀 

    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
    ),
);