<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆ All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-9-2
// +----------------------------------------------------------------------
// | Describe: 商城消费统计查询控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;

class GameShopController extends CommonController {
	public function _initialize() {
		// 判断用户权限
		if (! in_array ( '00200200', session ( 'user_code_arr' ) )) {
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
	
	//图表展示静态页面
	public function chartShow(){
		$this->assign ( 'serverList', session ( 'server_list' ) ); // 服务器列表
		$this->assign ( 'today', date ( 'Y-m-d', NOW_TIME ) ); // 默认显示今天的数据
		$this->display ();
	}
	
	//获取商城消费统计数据（总）
	public function getData(){
		$serverId = I("serverId",1,'intval');//服务器
		$startdate = I('startdate');//查询开始时间
		$enddate = I('enddate');//查询结束时间
		$moneyType = I('moneyType',1,'intval');//货币类型
		
		//开始日期为空，默认为开服日期
		if (empty($startdate)){
			$tem =  D('GetObj')->getObj($serverId)->table("item")->order('i_id asc')->find();
			$startdate = $tem['i_date'];
			unset($tem);
		}else {
			$startdate = $startdate.' 00:00:00';
		}
		
		$enddate = $enddate.' 23:59:59';
		
		if (empty($serverId)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));exit;
		}

		$goodsData = array();//按物品统计
		$dateData = array();//按日期统计
		$goodsPeople = array();//按物品统计时保存每件物品的购买人数
		$goodsPeople1 = array();//按日期统计时保存每件物品的购买人数
		$goodsIds = array();//按物品统计时保存物品id，用于查询物品名称(一维数组)
		$total = array('num'=>0,'price'=>0,'people'=>array());
		
		//如果区间中包含当天，获取当天数据
		if ($enddate>date('Y-m-d H:i:s',NOW_TIME)){
			if (APP_DEBUG){
				$filePath = C('GAME_LOG_PATH') . 'log-type-6.log';
			}else {
				$dbInfo = M ( 'gamedb' )->where (array('g_id'=>$serverId) )->find ();
				$path = C('GAME_LOG_PATH') . $dbInfo ['g_ip'] . '/' . date('Y-m-d',NOW_TIME); // 日志文件所在目录路径
				$filePath = $path . '/log-type-6.log';
			}
			if (is_file($filePath)){
				//{'playid':'640002968','commodityid':'430105','price':'5','price_type':'3','num':'1','activity':'1' ,'time':'1406585626'}
				$fp = fopen ( $filePath, "r" ); // 读取日志文件
				while ( ! feof ( $fp ) ) {
					$line = fgets ( $fp, 2048 );
					if (! empty ( $line )) {
						$INFO = trim ( substr ( $line, 21 ) );
						$INFO = str_replace ( "'", '"', $INFO );
						$arr = json_decode ( $INFO, true );
						if (is_array ( $arr ) && $arr['price_type']==$moneyType) {
								
							$goodsPeople[$arr['commodityid']][] = $arr['playid'];//购买物品对应的玩家id一维数组
							$goodsIds[] = $arr['commodityid'];//所有购买物品id的一维数组
							
							$total['num'] += $arr['num'];//购买物品总数量
							$total['price'] += $arr ['price'] * $arr ['num'];//购买物品总元宝数
							$total['people'][] = $arr['playid'];//所有购买物品玩家id一维数组
								
							if (!isset($goodsData[$arr['commodityid']])){
								$goodsData[$arr['commodityid']] = array(
										'goodsid'=>$arr['commodityid'],
										'price'=>$arr['price'],
										'num'=>$arr['num']
								);
							}else {
								$goodsData[$arr['commodityid']]['num'] += $arr['num'];
							}
		
							$date = date('Y-m-d',$arr['time']);
							if (!isset($dateData[$date])){
								$dateData [$date] = array (
										'date'=>$date,
										'people' => array ($arr ['playid']),
										'num' => $arr ['num'],
										'price' => $arr ['price'] * $arr ['num']
								);
							}else {
								$dateData[$date]['people'][] = $arr['playid'];
								$dateData[$date]['num'] += $arr['num'];
								$dateData[$date]['price'] += $arr['price']*$arr['num'];
							}
		
						}
					}
				}
				fclose ( $fp ); // 关闭文件指针
				$dateData[$date]['people'] = count(array_unique($dateData[$date]['people']));
			}
		}
		
		//历史数据
		$where = array();
		$where['i_date'] = array('between',array($startdate,$enddate));
		$where['i_type'] = $moneyType;
		
		//购买数据
		$tem = D('GetObj')->getObj($serverId)->table("item")->where($where)->order('i_id desc')->select();
		foreach ($tem as $k=>$v){
			$goodsPeople[$v['i_shopid']][] = $v['i_playid'];//购买物品对应的玩家id一维数组
			
			$date = substr($v['i_date'], 0,10);
			$goodsPeople1[$date][] = $v['i_playid'];//某天购买玩家id一维数组
		}
		
		//获取对应购买人数
		foreach ($goodsPeople  as $k=>$v){
			$goodsPeople[$k] = count(array_unique($goodsPeople[$k]));
		}
		foreach ($goodsPeople1  as $k=>$v){
			$goodsPeople1[$k] = count(array_unique($goodsPeople1[$k]));
		}
		
		foreach ($tem as $k=>$v){
			
			$goodsIds[] = $v['i_shopid'];
			$total['people'][] = $v['i_playid'];
			$total['num'] += $v['i_num'];
			$total['price'] += $v ['i_price'] * $v ['i_num'];
			
			if (!isset($goodsData[$v['i_shopid']])){
				$goodsData[$v['i_shopid']] = array(
						'goodsid'=>$v['i_shopid'],
						'price'=>$v['i_price'],
						'num'=>$v['i_num']
				);
			}else {
				$goodsData[$v['i_shopid']]['num'] += $v['i_num'];
			}

			$date = substr($v['i_date'], 0,10);
			if (!isset($dateData[$date])){
				$dateData [$date] = array (
						'date'=>$date,
						'num' => $v ['i_num'],
						'price' => $v ['i_price'] * $v ['i_num'] 
				);
			}else {
				$dateData[$date]['num'] += $v['i_num'];
				$dateData[$date]['price'] += $v['i_price']*$v['i_num'];
			}
			
			unset($tem[$k]);
		}
		
		//物品名称
		$goodsIds = array_unique($goodsIds);
		$goodsName = D('GetObj')->getObj($serverId)->table("tools_detail")->where(array('t_code'=>array('in',$goodsIds)))->select();
		foreach ($goodsName as $k=>$v){
			$goodsNameArr[$v['t_code']] = $v['t_name'];
		}
		
		foreach ($goodsData as $k=>$v){
			$goodsData[$k]['people'] = $goodsPeople[$v['goodsid']];
			$goodsData[$k]['goodsname'] = $goodsNameArr[$v['goodsid']];
		}
		
		foreach ($dateData as $k=>$v){
			if (isset($goodsPeople1[$k])){
				$dateData[$k]['people'] = $goodsPeople1[$k];
			}
		}
		
		sort($goodsData);
		sort($dateData);
		$goodsData = array_sort($goodsData,'num');//按物品购买数量降序排序
		$total['people'] = count(array_unique($total['people']));
		$this->ajaxReturn ( array (
				'status' => 1,
				'info' => '查询成功',
				'goodsdata' => $goodsData,
				'datedata' => $dateData ,
				'total'=>$total
		) );
		exit ();
	}
	
	//获取图表数据
	public function getChartData(){
		$serverId = I("serverId",1,'intval');//服务器
		$date = I('date');//查询时间
		$moneyType = I('moneyType',1,'intval');//货币类型
		
		$list = array();
		$goodsIds = array();
		if ($date==date('Y-m-d',NOW_TIME)){
			if (APP_DEBUG){
				$filePath = C('GAME_LOG_PATH') . 'log-type-6.log';
			}else {
				$dbInfo = M ( 'gamedb' )->where (array('g_id'=>$serverId) )->find ();
				$path = C('GAME_LOG_PATH') . $dbInfo ['g_ip'] . '/' . date('Y-m-d',NOW_TIME); // 日志文件所在目录路径
				$filePath = $path . '/log-type-6.log';
			}
			if (is_file($filePath)){
				//{'playid':'640002968','commodityid':'430105','price':'5','price_type':'3','num':'1','activity':'1' ,'time':'1406585626'}
				$fp = fopen ( $filePath, "r" ); // 读取日志文件
				while ( ! feof ( $fp ) ) {
					$line = fgets ( $fp, 2048 );
					if (! empty ( $line )) {
						$INFO = trim ( substr ( $line, 21 ) );
						$INFO = str_replace ( "'", '"', $INFO );
						$arr = json_decode ( $INFO, true );
						if (is_array ( $arr ) && $arr['price_type']==$moneyType) {
							
							$goodsIds[] = $arr['commodityid'];
							if (!isset($dateData[$arr['commodityid']])){
								$list [$arr['commodityid']] = array (
										'num' => $arr ['num'],
										'goodsid'=>$arr['commodityid']
								);
							}else {
								$list[$arr['commodityid']]['num'] += $arr['num'];
							}
			
						}
					}
				}
				fclose ( $fp ); // 关闭文件指针
			}
			
		}else {
			$where = array();
			$where['left(i_date,10)'] = $date;
			$where['i_type'] = $moneyType;
			
			$tem = D('GetObj')->getObj($serverId)->table("item")
			->field("sum(i_num) as num,i_shopid as goodsid")
			->where($where)->group('i_shopid')->select();
			
			foreach ($tem as $k=>$v){
				$goodsIds[] = $v['goodsid'];//物品id一维数组，用于查询物品名称
				$list[$v['goodsid']]['num'] = $v['num'];
				$list[$v['goodsid']]['goodsid'] = $v['goodsid'];
			}
		}
		
		//获取物品名称
		$goodsIds = array_unique($goodsIds);
		$goods =  D('GetObj')->getObj($serverId)->table("tools_detail")->where(array('t_code'=>array('in',$goodsIds)))->select();
		foreach ($goods as $k=>$v){
			$goodsArr[$v['t_code']] = $v['t_name'];
		}
		
		//如果物品id不存在物品表内，则显示其它
		foreach ($list as $k=>$v){
			if (isset($goodsArr[$k])){
				$data[$k]['country'] = $goodsArr[$k];
				$data[$k]['litres'] = $v['num'];
			}else {
				$data['others']['country'] = '其它';
				$data['others']['litres'] += $v['num'];
			}
		}
		sort($data);
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$data));
	}
}