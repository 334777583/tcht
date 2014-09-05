<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆ All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-26
// +----------------------------------------------------------------------
// | Describe: 充值记录对比查询控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;

class RechargeContrastController extends CommonController {
	public function _initialize() {
		// 判断用户权限
		if (! in_array ( '00100400', session ( 'user_code_arr' ) )) {
			$this->display ( 'Public/noauth' );
			exit ();
		}
	}
	
	//查询静态页面
	public function index() {
		$this->assign ( 'serverList', session ( 'server_list' ) ); // 服务器列表
		$this->assign ( 'today', date ( 'Y-m-d', NOW_TIME ) ); // 默认显示今天的数据
		$this->display ();
	}
	
	//获取充值数据
	public function getData(){
		$db = I('db');
		$startDate = I('startDate');
		$endDate = I('endDate');
		$startDate .= ' 00:00:00';
		$endDate .= ' 23:59:59';
		
		$where = array();//查询条件
		$where['c_sid'] = array('in',$db);
		$where['c_state'] = 2;//充值成功
		$where['c_time'] = array('between',array($startDate,$endDate));
		//符合条件的所有充值记录
		$payList = D('ChongZhi')->getAll($where);
		
		$list = array();//结果数组$list[$date][$serverId]
		foreach ($payList as $k=>$v){
			$date = substr($v['c_time'], 0,10);
			if (!isset($list[$date][$v['c_sid']])){
				$list[$date][$v['c_sid']] = array(
						'pay_gold'=>$v['c_price']*$v['c_num'],//当天充值总元宝
						'pay_people'=>array($v['c_openid']),//当天充值用户
						'pay_count'=>1,//当天充值次数
				);
			}else {
				$list[$date][$v['c_sid']]['pay_gold'] += $v['c_price']*$v['c_num'];
				$list[$date][$v['c_sid']]['pay_people'][]= $v['c_openid'];
				$list[$date][$v['c_sid']]['pay_count'] ++;
			}
		}
		
		$datePayAll = array();//某天所有符合条件服务器充值总和（元宝）
		foreach ($list as $k=>$v){
			foreach ($v as $k1=>$v1){
				if (isset($datePayAll[$k])){
					$datePayAll[$k] += $v1['pay_gold'];
				}else {
					$datePayAll[$k] = $v1['pay_gold'];
				}
				$list[$k][$k1]['pay_people'] = count(array_unique($list[$k][$k1]['pay_people']));//充值人数（账号）
			}
		}
		
		$serverList = session('server_list');//服务器列表
		
		$thread = '';//表头
		$tbody = '';//内容
		$thread .= "<tr>";
		$thread .= "<th width='100px'>日期</th>";
		$thread .= "<th>合计(元宝)</th>";
		
		$start = strtotime($startDate);
		$end = strtotime($endDate);
		$tem = 1;//控制表头添加
		for ($i=$start;$i<=$end;$i+=24*3600){
			$dateTem = date('Y-m-d',$i);
			$tbody .= "<tr>";
			$tbody .= "<td>".$dateTem."</td>";
			if (isset($datePayAll[$dateTem])){
				$tbody .= "<td>".$datePayAll[$dateTem]."</td>";
			}else {
				$tbody .= "<td>0</td>";
			}
			
			foreach ($db as $sid){
				if ($tem==1){
					$thread .= "<th>".$serverList[$sid]['g_name']."</th>";
				}
				if (isset($list[$dateTem][$sid])){
					$tbody .= "<td>".$list[$dateTem][$sid]['pay_gold'].'*'.$list[$dateTem][$sid]['pay_people'].'*'.$list[$dateTem][$sid]['pay_count']."</td>";
				}else {
					$tbody .= "<td>无记录</td>";
				}
			}
			$tem++;
		}
		$tbody .= "</tr>";
		$thread .= "</tr>";
		
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','tbody'=>$tbody,'thread'=>$thread));
	}
}
 