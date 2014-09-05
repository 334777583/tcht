<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-7-22
// +----------------------------------------------------------------------
// | Describe:后台用户登录控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
class PublicController extends Controller{
	//登录静态页面
	public function login(){
		if (session('user_code')) {
			$this->redirect('Index/index');exit;
		}
		$this->display();
	}
	//登录检测
	Public function checkLogin(){
		if (!IS_POST) {
			$this->error('参数不对');
		}
		$username = I('username','','trim');
		$password = I('password','','trim');
		if (empty($username)||empty($password)) {
			$this->error('请认真填写用户名和密码');
		}
		$userCode = D('Sysuser')->checkUser($username,$password);
		if (is_array($userCode)) {
			session('username',$username);
			session('user_code',$userCode);
			//操作记录
			action_log(session('username'),0,15,'登录后台');
			$this->redirect('Index/index');exit;
		}else {
			switch ($userCode){
				case -1:
					$this->error('用户名不存在');
					break;
				case -2:
					$this->error('密码错误');
					break;
				case -3:
					$this->error('没有权限');
					break;
			}
		}
	}
	//退出登录
	public function loginOut(){
		session(null);
		$this->redirect('Public/login');exit;
	}
}