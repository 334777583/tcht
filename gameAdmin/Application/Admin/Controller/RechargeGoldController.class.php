<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆ All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-26
// +----------------------------------------------------------------------
// | Describe: 后台发送元宝控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;

class RechargeGoldController extends CommonController {
	public function _initialize() {
		// 判断用户权限
		if (! in_array ( '00100600', session ( 'user_code_arr' ) )) {
			$this->display ( 'Public/noauth' );
			exit ();
		}
	}
	public function index() {
		$this->assign ( 'serverList', session ( 'server_list' ) ); // 服务器列表
		$this->display ();
	}
	
	public function sendGold(){
		$player = D('PlayerTable')->getByRoleName($toolsAsk['t_ip'],$toolsAsk['t_role_name'],false,'normal');
		$chargeList = array (
				'ServerID' => $toolsAsk['t_ip'],
				'PlayerGUID' => $player[0]['GUID'],
				'RMB' => 0,
				'FakeRMB'=>$toolsAsk['t_gold'],
				'Charge_time' => NOW_TIME
		);
		D('ChargeList')->addData($toolsAsk['t_ip'],$chargeList);
		
		$cm11 = array();
		$cm11['cmd'] = "charge";
		$cm11['GUID'] = $player[0]['GUID'];
		$cm11['time'] = NOW_TIME;
		D('PhpCmd')->insertCMD($toolsAsk['t_ip'],$cm11,5);
	}
}
 