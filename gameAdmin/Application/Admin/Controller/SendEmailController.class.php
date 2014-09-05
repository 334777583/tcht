<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-18
// +----------------------------------------------------------------------
// | Describe: 后台发送邮件控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Admin\Model\PhpCmdModel;
class SendEmailController extends CommonController{
	public function _initialize(){
		//判断用户权限
		if (!in_array('00500300', session('user_code_arr'))){
			$this->display('Public/noauth');exit;
		}
	}
	
	//发送邮件
	public function sendEmail(){
		$rolename = I('rolename');	//角色名
		$serverId = I('serverId');	//服务器id
		$reason = I('reason');	//发送原因
		$title = I('title');	//邮件标题
		$content = I('content');	//邮件内容
		$gold = I('gold');	//元宝（绑定）
		$copper = I('copper');	//铜币（绑定）
		$srole = I('srole');	//1为给特定用户发送，2为全服
		$minLv = I('minLv');		//全服发送时用户最小等级限制
		$maxLv = I('maxLv');	//全服发达时用户最大等级限制
		$tasktime = I('tasktime');	//定时发送时间
		$state = I('state');	//全服发送时目标用户：1全部，2离线，3在线
		$emailTime = I('emailTime');	//全服发送时有限期限（数量）
		$day = I('day');	//全服发送时有限期限（天，周，时）
		
		if (empty($title)){
			$title = '系统邮件';
		}
		if (empty($tasktime)){
			$tasktime = date('Y-m-d H:i:s',NOW_TIME);
		}
		if($content==''&&$gold==0&&$copper==0){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误,刷新后重试'));exit;
		}
		if (empty($content)){
			$content = '系统邮件';
		}
		
		if ($srole==1&&!empty($rolename)){
			$rolenameArr = explode(';',$rolename);//需要发送的用户角色名一维数组
			$info = '';
			foreach ($rolenameArr as $v){
				$userStatus = array();//根据角色名判断用户是否存在
				$userStatus = D('PlayerTable')->getByRoleName($serverId,$v,false,'normal');
				if (empty($userStatus)){
					$info .= '<'.$v.'用户不存在,发送失败>';
					continue;
				}
				
				//定时任务，插入任务表，非定时任务或者定时时间小于当前立即发送到服务器
				if ($tasktime==0||$tasktime<=date('Y-m-d H:i:s',NOW_TIME)){
					$cmdArr = array();
					$cmdArr['time'] = NOW_TIME;
					$cmdArr['cmd'] = 'sendmail';
					$cmdArr['name'] = $v;
					$cmdArr['title'] = $title;
					$cmdArr['content'] = $content;
					if ($gold){
						$cmdArr['money'][] =  array('type'=>4,'count'=>$gold);
					}
					if ($copper){
						$cmdArr['money'][] =  array('type'=>2,'count'=>$copper);
					}
					$phpCmdId = D('PhpCmd')->insertCMD($serverId,$cmdArr,3);
					if (empty($phpCmdId)){
						$info .= '<给'.$v.'用户,发送失败>';
						continue;
					}
				}else {
					$taskId = M('timer')->add(array(
							'runtime'=>$tasktime,
							'task'=>'email',
							'createtime'=>date('Y-m-d H:i:s',NOW_TIME),
							'state'=>0
					));
					$phpCmdId = 0;
				}
				
				$data = array();//需要插入申请表数据
				$data = array (
						'e_ip' => $serverId,
						'e_uid' => $phpCmdId,
						'e_name' => $v,
						'e_time' => date ( 'Y-m-d H:i:s' ,NOW_TIME),
						'e_reason' => $reason,
						'e_title' => $title,
						'e_content' => $content,
						'e_status' => 1,
						'e_tasktime' => $tasktime,
						'e_result' => 0,
						'e_operaor' => session('username'),
						'e_gold' => $gold,
						'e_copper' => $copper 
				);
				$emailId = M('email')->add($data);
				if (!$emailId){
					$info .= '<给用户'.$v.'发送失败>';
					continue;
				}
			}
			if (empty($info)){
				$this->ajaxReturn(array('status'=>1,'info'=>'发送成功'));exit;
			}else {
				$this->ajaxReturn(array('status'=>1,'info'=>$info));exit;
			}
		}elseif ($srole==2){
			if ($day==3){
				$emailTime = $emailTime*3600+NOW_TIME;//时
			}elseif ($day==1){
				$emailTime = $emailTime*24*3600+NOW_TIME;//天
			}elseif ($day==2){
				$emailTime = $emailTime*7*24*3600+NOW_TIME;//周
			}
			
			//定时任务，插入任务表，非定时任务或者定时时间小于当前立即发送到服务器
			if ($tasktime==0||$tasktime<=date('Y-m-d H:i:s',NOW_TIME)){
				if ($state==1){
					$cmdArr = array();
					$cmdArr['time'] = NOW_TIME;
					$cmdArr['cmd'] = 'sendmailtoall';
					$cmdArr['title'] = $title;
					$cmdArr['content'] = $content;
					$cmdArr['level_min'] = $minLv;
					$cmdArr['level_max'] = $maxLv;
					$cmdArr['out_date'] = $emailTime;
					if ($gold){
						$cmdArr['money'][] =  array('type'=>4,'count'=>$gold);
					}
					if ($copper){
						$cmdArr['money'][] =  array('type'=>2,'count'=>$copper);
					}
					$phpCmdId = D('PhpCmd')->insertCMD($serverId,$cmdArr,3);
					if (empty($phpCmdId)){
						$info .= '<发送失败>';
						continue;
					}
				}elseif ($state!=1){
					if ($state==2){
						$userList = D('PlayerTable')->getAll($serverId,array('bOnline'=>1,'ServerId'=>$serverId));
					}elseif ($state==3){
						$userList = D('PlayerTable')->getAll($serverId,array('bOnline'=>0,'ServerId'=>$serverId));
					}
					
					foreach ($userList as $v){
						$cmdArr = array();
						$cmdArr['time'] = NOW_TIME;
						$cmdArr['cmd'] = 'sendmail';
						$cmdArr['name'] = $v['RoleName'];
						$cmdArr['title'] = $title;
						$cmdArr['content'] = $content;
						if ($gold){
							$cmdArr['money'][] =  array('type'=>4,'count'=>$gold);
						}
						if ($copper){
							$cmdArr['money'][] =  array('type'=>2,'count'=>$copper);
						}
						$phpCmdId = D('PhpCmd')->insertCMD($serverId,$cmdArr,3);
					}
				}
			}else {
				$taskId = M('timer')->add(array(
						'runtime'=>$tasktime,
						'task'=>'email',
						'createtime'=>date('Y-m-d H:i:s',NOW_TIME),
						'state'=>0
				));
				$phpCmdId = 0;
			}
			
			switch ($state){
				case 1:
					$name = '全服';
					break;
				case 2:
					$name = '在线用户';
					break;
				case 3:
					$name = '离线用户';
					break;
			}
			
			//全服发送
			$data = array (
					'e_ip' => $serverId,
					'e_uid' => $phpCmdId,
					'e_name' => $name,
					'e_time' => date ( 'Y-m-d H:i:s', NOW_TIME ),
					'e_reason' => $reason,
					'e_title' => $title,
					'e_content' => $content,
					'e_status' => 1,
					'e_tasktime' => $tasktime,
					'e_result' => 0,
					'e_operaor' => session ( 'username' ),
					'e_gold' => $gold,
					'e_copper' => $copper 
			);
			$toolId = M('email')->add($data);
			if (!$toolId){
				$this->ajaxReturn(array('status'=>0,'info'=>'发送失败'));exit;
			}
			//操作记录
			action_log(session('username'),$serverId,3,'发送邮件');
			$this->ajaxReturn(array('status'=>1,'info'=>'发送成功'));exit;
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误,刷新后重试'));exit;
		}
	}
	
	//获取已发送记录
	public function getEmailList(){
		$serverId = I('serverId','0','intval');
		$curPage = I('curPage','1','intval');
		$pageSize = I('pageSize','10','intval');
		if (empty($serverId)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));exit;
		}
		
		//获取后端处理结果
		D('PhpCmd')->getCmdDelResult('email',array('e_result'=>0,'e_uid'=>array('gt',0)),'e_id','e_ip','e_uid','e_result');
		
		$where['e_ip'] = $serverId;
		$where['e_status'] = 1;
		$total = M('email')->where($where)->count();
		
		$totalPage = ceil($total/$pageSize);
		if ($curPage>$totalPage){
			$curPage = $totalPage;//当前页大于总页数
		}
		
		$list = M('email')->where($where)->order('e_id desc')->page($curPage,$pageSize)->select();
		$page = new \Common\Controller\AjaxPageController($pageSize, $curPage, $total, "getNewsList", "go","page");
		$pageHtml = $page->getPageHtml();
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$list,'pageHtml'=>$pageHtml,'serverList'=>session('server_list')));exit;
	}
	
	//静态页面
	public function index(){
		$this->assign('serverList',session('server_list'));//服务器列表
		$this->assign('today',date('Y-m-d H:i:s',NOW_TIME));
		$this->display();
	}
}