<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆ All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-26
// +----------------------------------------------------------------------
// | Describe: 充值记录统计查询控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;

class RechargeStatisticsController extends CommonController {
	public function _initialize() {
		// 判断用户权限
		if (! in_array ( '00100300', session ( 'user_code_arr' ) )) {
			$this->display ( 'Public/noauth' );
			exit ();
		}
	}
	
	//获取统计数据
	public function  getData(){
		$type = I('type',1,'intval');	//1：开服到截止时间，2:区间
		$startDate = I('startDate');//区间开始时间
		$endDate = I('endDate');//区间截止时间
		$finshDate = I('finshDate');//截止时间
		$startDate .= ' 00:00:00';
		$endDate .= ' 23:59:59';
		$finshDate .= ' 23:59:59';
		$db = I('db');
		
		if (empty($db)||!is_array($db)){
			$this->ajaxReturn(array('status'=>0,'info'=>'请选择服务器'));exit;
		}
		$where = array();//查询条件
		$where['c_sid'] = array('in',$db);
		$where['c_state'] = 2;//充值成功
		if ($type == 1) {
			if (!empty($finshDate)){
				$where['c_time'] = array('elt',$finshDate);
			}
		} else if ($type == 2) {
			if (!empty($startDate)&&!empty($endDate)){
				$where['c_time'] = array('between',array($startDate,$endDate));
			}else {
				$this->ajaxReturn(array('status'=>0,'info'=>'日期错误'));exit;
			}
		}
		//符合条件的所有充值记录
		$payList = D('ChongZhi')->getAll($where);
		
		//结果数组
		$list = array ();
		$serverList = session('server_list');
		foreach ( $payList as $k => $v ) {
			$date = '';
			if (!isset ( $list [$v ['c_sid']] )) {
				$date = substr($v['c_time'], 0,10);//充值日期
				$list [$v ['c_sid']] = array (
						'server' => $serverList[$v ['c_sid']]['g_name'], // 服务器名称
						'open_date' => $date, // 开服日期
						'query_date'=> array('start'=>$date,'end'=>$date),//查询日期
						'pay_day' => 1, // 充值天数
						'pay_people' => array($v['c_openid']), // 充值人数
						'first_day_pay_people' => array($v['c_openid']), // 首日充值人数
						'first_day_pay_gold' => $v['c_price']*$v['c_num'], // 首日充值元宝数量
						'first_day_pay_amt' => $v['c_amt'], // 首日充值q点
						'first_day_payamt_coins'=>$v['payamt_coins'], // 首日充值游戏币
						'first_day_pubacct_payamt_coins' => $v['pubacct_payamt_coins'],  // 首日充值金、银、铜券
						'first_pay_gold'=> $v['c_price']*$v['c_num'], // 首充元宝
						'pay_gold' => $v['c_price']*$v['c_num'], // 充值总元宝
						'pay_amt' => $v['c_amt'], // 充值q点
						'payamt_coins'=>$v['payamt_coins'], // 充值游戏币
						'pubacct_payamt_coins' => $v['pubacct_payamt_coins']  // 充值金、银、铜券
								);
			} else {
				$date = substr($v['c_time'], 0,10);//充值日期
				$list[$v['c_sid']]['query_date']['end'] = $date;
				//首日充值统计
				if ($date==$list[$v['c_sid']]['open_date']){
					$list[$v['c_sid']]['first_day_pay_people'][] = $v['c_openid'];
					$list[$v['c_sid']]['first_day_pay_gold'] += $v['c_price']*$v['c_num'];
					$list[$v['c_sid']]['first_day_pay_amt'] += $v['c_amt'];
					$list[$v['c_sid']]['first_day_payamt_coins'] += $v['payamt_coins'];
					$list[$v['c_sid']]['first_day_pubacct_payamt_coins'] += $v['pubacct_payamt_coins'];
				}else {
					$list[$v['c_sid']]['pay_day'] = ceil((strtotime($date)-strtotime($list[$v['c_sid']]['open_date']))/(24*60*60))+1;
				}
				$list[$v['c_sid']]['pay_people'][] = $v['c_openid'];
				$list[$v['c_sid']]['pay_gold'] += $v['c_price']*$v['c_num'];
				$list[$v['c_sid']]['pay_amt'] += $v['c_amt'];
				$list[$v['c_sid']]['payamt_coins'] += $v['payamt_coins'];
				$list[$v['c_sid']]['pubacct_payamt_coins'] += $v['pubacct_payamt_coins'];
			}
		}
		
		foreach ($list as $k=>$v){
			$list[$k]['first_day_pay_people'] = count(array_unique($list[$k]['first_day_pay_people']));
			$list[$k]['pay_people'] = count(array_unique($list[$k]['pay_people']));
			$list[$k]['query_date'] = implode('=>', $list[$k]['query_date']);
		}
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$list));
	}
	
	//静态页面
	public function index() {
		$this->assign ( 'serverList', session ( 'server_list' ) ); // 服务器列表
		$this->assign ( 'today', date ( 'Y-m-d', NOW_TIME ) );
		$this->display ();
	}
}
 