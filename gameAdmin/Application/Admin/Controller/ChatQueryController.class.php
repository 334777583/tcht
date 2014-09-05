<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-23
// +----------------------------------------------------------------------
// | Describe: 后台查看游戏聊天记录控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;
class ChatQueryController extends CommonController{
	public function _initialize(){
		//判断用户权限
		if (!in_array('00501900', session('user_code_arr'))){
			$this->display('Public/noauth');exit;
		}
	}
	
	public function index(){
		$this->assign('serverList',session('server_list'));//服务器列表
		$this->assign('today',date('Y-m-d',NOW_TIME));
		$this->display();
	}
	
	//从日记文件获取聊天记录
	public function getChatContents(){
		$serverId = I('serverId',1,'intval');//服务器id
		$chatnum = I('chatnum',100,'intval');//查看记录条数
		$chatType = I('chatType');//查看聊天类型
		$start = I('start',0,'intval');//开始行数（从文件底部开始算起）
		$date = I('startdate');//查看日期
		if ($date>date('Y-m-d')||empty($serverId)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));exit;
		}
		
		if (APP_DEBUG){
			$chatFilePath = C('GAME_LOG_PATH').'log-type-18.log';//测试路径
		}else {
			$dbInfo = M('gamedb')->where(array('g_id'=>$serverId))->find();
			$path = C('GAME_LOG_PATH') . $dbInfo['g_ip'] . '/' . $date;	//日志文件所在目录路径
			$chatFilePath = $path.'/log-type-18.log';
		}
		
		if (!is_file($chatFilePath)){
			$this->ajaxReturn(array('status'=>0,'info'=>'文件不存在!'.$chatFilePath));exit;
		}
		
		$channel = array('私聊','队伍','帮派','世界','喇叭','系统','中央屏幕','好友聊天','陌生人消息','场景聊天','国家聊天','传闻(强化)','组队招募广播');
		
		$fp = fopen($chatFilePath, "r");
		$pos = -2;
		$t = " ";//结束标识符
		$data = array();//结果数组
		$arr = array();
		$i = 0;//已经读取的行数
		while ($chatnum > 0) {
			while ($t != "\n") {
				$a = fseek($fp, $pos, SEEK_END);
				$t = fgetc($fp);
				$pos --;
				if ($a==-1){
					break;
				}
			}
			if ($a==-1){
				break;
			}
			$t = " ";
			$linedata = fgets ( $fp, 2048 );
			if (! empty ( $linedata )) {
				$i++;
				if ($i<=$start){
					continue;
				}
				$INFO = trim ( substr ( $linedata, 21 ) );
				$INFO = strip_tags($INFO);//过虑html、php代码
				$INFO = str_replace ( "'", '"', $INFO );//转换成json格式
				$arr = json_decode ( $INFO, true );
				//过滤帮派欢迎词
				if (is_array($arr)&&in_array($arr['chat_type'], $chatType)){
					if ($arr['chat_type']==2){
						$match1 = "/(很高兴认识你，以后去打BOSS下多人副本记得叫上我哦！~){1}/";
						$match2 = "/(哟，新人啊，以后由我照着你了。){1}/";
						$match3 = "/(我正在想要送你鲜花 ，你先给爷笑一个。){1}/";
						if (preg_match($match1,$arr['content'])){
							continue;
						}elseif (preg_match($match2,$arr['content'])){
							continue;
						}elseif (preg_match($match3,$arr['content'])){
							continue;
						}
					}
					$data[] = $arr;
					//todo  查看玩家冻结状态
// 					$userIds[] = $arr['playid'];//所有聊天玩家id的一维数组
				}
			}
			$chatnum--;
		}
		fclose ($fp);
		
		$list = array();
		foreach ($data as $k=>$v){
			$list[$k] = $v;
			$list[$k]['channel'] = $channel[$v['chat_type']];
			$list[$k]['date'] = date('Y-m-d H:i:s',$v['time']);
			
			//查看玩家冻结状态
			$status = M('freeze')->where(array('f_role_id'=>$v['playid']))->order('f_id desc')->find();
			if ($status&&$status['f_status'] ==1){
				$list[$k]['status'] = 1;
			}else {
				$list[$k]['status'] = 0;
			}
		}
		$this->ajaxReturn(array('status'=>1,'list'=>$list,'info'=>'查询成功'));exit;
	}
	
	//冻结并下线
	public function killPlayer(){
		$serverId = I('serverId',0,'intval');
		$roleid = I('roleid');
		$rolename = I('rolename');
		
		$userStatus = D('PlayerTable')->getByRoleName($serverId,$rolename,false,'join');
		if (!$userStatus){
			$this->ajaxReturn(array('status'=>0,'info'=>$rolename."用户不存在,处理失败"));exit;
		}
		
		$status0 = D('ForbidLogin')->addData($serverId,array('Account'=>$userStatus[0]['account'],'forbid_time'=>NOW_TIME+315360000));
		if ($status0){
			$data0 = array (
					"f_uid" => $status0,
					"f_ip" => $serverId,
					"f_role_id" => $roleid,
					"f_role_name" => $rolename,
					"f_status" => 1,
					"f_time" => date ( 'Y-m-d H:i:s', 315360000 + NOW_TIME ),
					"f_secends" => 315360000,
					"f_reason" => '在聊天监控中，查到玩家发送非法信息',
					"f_operaor" => session('username'),
					"f_callstatus" => 2,
					"f_roleStatus" => 1,
					"f_inserttime" => date ( "Y-m-d H:i:s", NOW_TIME )
			);
			M ( 'freeze' )->add ( $data0 );
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'冻结失败，刷新后重试'));exit;
		}
		
		$cmdArr['cmd'] = "kickplayer";
		$cmdArr['name'] = $rolename;
		$cmdArr['info'] = '玩家因发布不文明信息，强制下线';
		$status1 = D('PhpCmd')->insertCMD($serverId,$cmdArr);
		if ($status1){
			$data1 = array(
					'f_uid' 		=> $status1,
					"f_ip"			=> $serverId,
					"f_role_id"		=> $roleid,
					"f_role_name"	=> $rolename,
					"f_status"		=> 1,
					"f_time" 		=> date("Y-m-d H:i:s",NOW_TIME),
					"f_reason" 		=> '在聊天监控中，查到玩家发送非法信息',
					"f_operaor" 	=> session('username'),
					"f_callstatus" 	=>2,
					"f_roleStatus" 	=>0,
					"f_inserttime" 	=> date("Y-m-d H:i:s",NOW_TIME)
			);
			M ('offline')->add($data1);
			$this->ajaxReturn(array('status'=>1,'info'=>'已冻结'));exit;
		}else {
			$this->ajaxReturn(array('status'=>1,'info'=>'冻结成功，下线失败'));exit;
		}
	}
}
	