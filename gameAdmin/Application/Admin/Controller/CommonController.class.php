<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-7-22
// +----------------------------------------------------------------------
// | Describe: 后台公共控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller{
	/**
	 * 权限检测
	 */
	public function _initialize(){
		if (!session('user_code')) {
			$this->redirect('public/login');exit;
		}
		//服务器列表
		if (!session('server_list')){
			$serverList = M('gamedb')->order('g_id asc')->where(array("g_flag"=>1))->select();
			$tem = array();
			foreach ($serverList as $v){
				$tem[$v['g_id']] = $v;//用于模板友好显示服务器名称
			}
			session('server_list',$tem);
		}
	}
	
	/**
	 * 空方法
	 */
	public function _empty(){        
		$this->error('你所请求的方法不存在');
	}
}