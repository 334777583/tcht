<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-22
// +----------------------------------------------------------------------
// | Describe: 后台GM设置控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;
class ServerEditorController extends CommonController{
	public function _initialize(){
		//判断用户权限
		if (!in_array('00501100', session('user_code_arr'))){
			$this->display('Public/noauth');exit;
		}
	}
	
	//后台服务器列表查询页面
	public function index(){
		$serverList = M ( 'servers' )->where ( array (
				's_flag' => 1 
		) )->select ();
		$this->assign ( 'serverList', $serverList ); // 服务器列表
		$this->display ();
	}
	
	// 编辑前获取信息
	public function getById() {
		$sid = I ( 'sid', 0, 'intval' );
		$data = M ( 'servers' )->find ( $sid );
		if (empty ( $data )) {
			$this->ajaxReturn ( array (
					'status' => 0,
					'info' => '参数错误，刷新后重试'
			) );
		} else {
			$this->ajaxReturn ( array (
					'status' => 1,
					'info' => '查询成功',
					'list' => $data
			) );
		}
	}
	
	// 修改服务器信息
	public function dbEditor() {
		$data ['s_id'] = I ( 'sid', 0, 'intval' );
		$data ['s_ip'] = I ( 'ip' );
		$data ['s_name'] = I ( 'name' );
		$data ['s_domain'] = I ( 'domain' );
		$data['s_gid'] = I('gid',0,'intval');
		$data['s_port'] = I('port');
		$status = M ( 'servers' )->save ( $data );
		if ($status) {
			// 操作记录
			action_log ( session ( 'username' ), 0, 10, '修改服务器（' . $data ['s_id'] . '）' );
			$this->ajaxReturn ( array (
					'status' => 1,
					'info' => '修改成功'
			) );
		} else {
			$this->ajaxReturn ( array (
					'status' => 0,
					'info' => '修改失败'
			) );
		}
	}
	
	// 删除服务器信息
	public function dbDelete() {
		$sid = I ( 'sid', 0, 'intval' );
		$status = M ( 'servers' )->where ( array (
				's_id' => $sid
		) )->save ( array (
				's_flag' => 2
		) );
		if ($status) {
			// 操作记录
			action_log ( session ( 'username' ), 0, 10, '删除服务器（' . $sid . '）' );
			$this->ajaxReturn ( array (
					'status' => 1,
					'info' => '删除成功'
			) );
		} else {
			$this->ajaxReturn ( array (
					'status' => 0,
					'info' => '删除失败'
			) );
		}
	}
}