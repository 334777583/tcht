<?php
header("Content-type:text/html;charset=utf-8");
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-7-22
// +----------------------------------------------------------------------

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

/**
 * 系统调试设置
 * 项目正式部署后请设置为false
*/
define('APP_DEBUG', false );

/**
 * 根目录
 */
define('WEB_ROOT',dirname(__FILE__));

/**
 * 应用目录设置
 * 安全期间，建议安装调试完成后移动到非WEB目录
*/
define ( 'APP_PATH', WEB_ROOT.'/Application/' );

/**
 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define ( 'RUNTIME_PATH', WEB_ROOT.'/Runtime/' );

/**
 * 引入核心入口
 * ThinkPHP亦可移动到WEB以外的目录
*/
require_once WEB_ROOT.'/ThinkPHP/ThinkPHP.php';