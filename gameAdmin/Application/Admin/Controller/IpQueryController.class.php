<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆ All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-23
// +----------------------------------------------------------------------
// | Describe: 后台查询游戏用户登录ip控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;

class IpQueryController extends CommonController {
	public function _initialize() {
		// 判断用户权限
		if (! in_array ( '00502000', session ( 'user_code_arr' ) )) {
			$this->display ( 'Public/noauth' );
			exit ();
		}
	}
	public function index() {
		$this->assign ( 'serverList', session ( 'server_list' ) ); // 服务器列表
		$this->assign('today',date('Y-m-d',NOW_TIME));
		$this->display ();
	}
	
	/**
	 * 获取数据
	 */
	public function getIpData() {
		$serverId = I ( 'serverId' ,1,'intval');
		$startDate = I ( 'startdate' );
		
		if (APP_DEBUG){
			$loginLogFilePath = C('GAME_LOG_PATH') . 'log-type-2.log';
		}else {
			$dbInfo = M ( 'gamedb' )->where (array('g_id'=>$serverId) )->find ();
			$path = C('GAME_LOG_PATH') . $dbInfo ['g_ip'] . '/' . $startDate; // 日志文件所在目录路径
			$loginLogFilePath = $path . '/log-type-2.log';
		}
		if (!is_file($loginLogFilePath)){
			$this->ajaxReturn(array('status'=>0,'info'=>'文件不存在!'.$loginLogFilePath));exit;
		}
		
		$fp = fopen ( $loginLogFilePath, "r" ); // 读取日志文件
		$ipArr = array ();
		while ( ! feof ( $fp ) ) {
			$line = fgets ( $fp, 2048 );
			if (! empty ( $line )) {
				$INFO = trim ( substr ( $line, 21 ) );
				$INFO = str_replace ( "'", '"', $INFO );
				$arr = json_decode ( $INFO, true );
				if (is_array ( $arr )) {
					if (isset ( $ipArr [$arr ['ip']] )) {
						$ipArr [$arr ['ip']] ['count'] ++;//登录次数
						$ipArr [$arr ['ip']] ['playid'] [] = $arr ['playid'];//id数量
						$ipArr [$arr ['ip']] ['account'] [] = $arr ['account'];//账号数量
					} else {
						$ipArr [$arr ['ip']] ['count'] = 1;
						$ipArr [$arr ['ip']] ['playid'] [] = $arr ['playid'];
						$ipArr [$arr ['ip']] ['account'] [] = $arr ['account'];
					}
				}
			}
		}
		fclose ( $fp ); // 关闭文件指针

		//汇总
		$summary = array('ip_num'=>0,'account_num'=>0,'playerid_num'=>0,'login_num'=>0);
		
		$i = 0;
		$list = array ();
		foreach ( $ipArr as $k => $v ) {
			$playerid = array_unique ( $v ['playid'] );
			$account = array_unique ( $v ['account'] );
			sort($account);
			$list [$i] ['ip'] = $k;
			$list [$i] ['playerid'] = count ( $playerid );
			$list [$i] ['accountnum'] = count ( $account );
// 			$list [$i] ['account'] = '';
			$list[$i]['account'] = $account;
			$list [$i] ['count'] = $v ['count'];
			$i ++;
			
			$summary['ip_num'] ++;
			$summary['account_num'] += count($account);
			$summary['playerid_num'] += count($playerid);
			$summary['login_num'] += $v['count'];
		}
		
		//排序
		$num = count ( $list );
		for($i = 1; $i < $num; $i ++) {
			for($j = $num - 1; $j >= $i; $j --) {
				// 如果是从大到小的话，只要在这里的判断改成if($b[$j]>$b[$j-1])就可以了
				if ($list [$j] ['account'] > $list [$j - 1] ['account']) { 
					$x = $list [$j];
					$list [$j] = $list [$j - 1];
					$list [$j - 1] = $x;
				}
			}
		}
		$this->ajaxReturn(array('status'=>1,'list'=>$list,'summary'=>$summary,'info'=>'查询成功'));exit;
	}
	
	//查询玩家当天登录过的ip
	public function getPlayerIp() {
		$serverId = I ( 'serverId' ,1,'intval');
		$startDate = I ( 'startdate' );
		$rolename = I ( 'rolename' );
		
// 		$dbInfo = M ( 'gamedb' )->where (array('g_id'=>$serverId) )->find ();
// 		$path = C('GAME_LOG_PATH') . $dbInfo ['g_ip'] . '/' . $startDate . '/'; // 日志文件所在目录路径
// 		$loginLogFilePath = $path . 'log-type-2.log';
		$loginLogFilePath = C('GAME_LOG_PATH') . 'log-type-2.log';
		if (!is_file($loginLogFilePath)){
			$this->ajaxReturn(array('status'=>0,'info'=>'文件不存在!'.$loginLogFilePath));exit;
		}
		
		$userStatus = D('PlayerTable')->getByRoleName($serverId,$rolename,true);
		
		$playeridArr = array();//符合查询条件的玩家id一维数组
		$player = array();//玩家信息数组
		
		foreach ($userStatus as $k => $v ) {
			$playeridArr [] = $v ['GUID'];
			$player [$v ['GUID']] ['playerid'] = $v ['GUID'];
			$player [$v ['GUID']] ['rolename'] = $v ['RoleName'];
		}
		
		$fp = fopen ( $loginLogFilePath, "r" ); // 读取日志文件
		$ipArr = array ();
		while ( ! feof ( $fp ) ) {
			$line = fgets ( $fp, 2048 );
			if (! empty ( $line )) {
				$INFO = trim ( substr ( $line, 21 ) );
				$INFO = str_replace ( "'", '"', $INFO );
				$arr = json_decode ( $INFO, true );
				if (is_array ( $arr ) && in_array ( $arr ['playid'], $playeridArr )) {
					if (isset ( $player [$arr ['playid']] ['ip'] )) {
						$player [$arr ['playid']] ['ip'] = $player [$arr ['playid']] ['ip'] . '、' . $arr ['ip'];
					} else {
						$player [$arr ['playid']] ['ip'] = $arr ['ip'];
					}
					$player [$arr ['playid']] ['account'] = $arr ['account'];
				}
			}
		}
		fclose ( $fp ); // 关闭文件指针
		
		//去除无效数据
		foreach ($player as $k=>$v){
			if(empty($v['account'])){
				unset($player[$k]);
			}
		}
		
		sort ( $player );
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$player));
	}
}
	