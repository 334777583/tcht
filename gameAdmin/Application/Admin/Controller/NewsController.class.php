<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-13
// +----------------------------------------------------------------------
// | Describe: 后台发送公告控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;
class NewsController extends CommonController{
	public function _initialize(){
		//判断用户权限
		if (!in_array('00500200', session('user_code_arr'))){
			$this->display('Public/noauth');exit;
		}
	}
	
	public function index(){
		$this->assign('serverList',session('server_list'));//服务器列表
		$this->display();
	}
	
	public function sendNews(){
		$serverId = I('serverId',null,'intval');
		$type = I('type','','intval');
		$starttime = I('starttime');
		$endtime = I('endtime');
		$tasktime = I('tasktime');
		$interval = I('interval');
		$content = I('content');
		
		$serverList = array();
		if ($serverId){
			$serverList[0]['s_gid'] = $serverId;
		}elseif ($serverId==0){
			$serverList = M('servers') -> where('s_flag = 1') -> select();
		}
		
		$info = '';
		foreach ($serverList as $k=>$v){
			$nowDateTime = date('Y-m-d H:i:s');
			if ($starttime==0){
				$starttime = $nowDateTime;
			}
			//定时任务
			if ($tasktime!=0){
				//定时任务，开始时间为定时时间
				$starttime = $tasktime;
			}
			if ($endtime<$starttime){
				$endtime = $starttime;
			}
			
			$newsId = M ( 'news' )->add ( array (
					'n_ip' => $v['s_gid'],
					'n_status' => $type,
					'n_starttime' => $starttime,
					'n_endtime' => $endtime,
					'n_interval' => $interval*60,
					'n_content' => $content,
					'n_date' => date ( 'Y-m-d H:i:s' ),
					'n_operaor' => session('username'),
					'n_callstatus' =>0,
					'n_inserttime' => date ( 'Y-m-d H:i:s' )
			));
			//操作记录
			action_log(session('username'),$v['s_gid'],4,'发送公告（'.$content.'）');
			if (!$newsId){
				$info .= '服务器'.$v['s_gid'].'发送失败！';
				continue;
			}
			
			//当不是定时任务且开始时间小于等于当前，定时任务且定时时间小于等于当前，立即发送公告
			if (($tasktime==0&&$starttime<=$nowDateTime)||($tasktime!=0&&$tasktime<=$nowDateTime)){
				//倒计时
				if (substr_count($content, '{countdown}')>0){
					$countdown = ceil((strtotime($endtime)-time())/60).'分后';
					$content = str_replace('{countdown}',$countdown,$content);
				}
				$cmd = array();
				$cmd['cmd'] = 'sysbroadtext';
				$cmd['content'] = $content;
				$cmd['type'] = $type;
				$status = D('PhpCmd')->insertCMD($v['s_gid'],$cmd,$type);
				
				if ($status){
					//发送成功且不是循环发送，更改发送成功状态
					if ($interval==0){
						M ( 'news' )->where(array('n_id'=>$newsId))->save(array('n_callstatus'=>1));
					}
				}else {
					M ( 'news' )->where(array('n_id'=>$newsId))->save(array('n_callstatus'=>2));
				}
			}
			
		}
		
		if ($info==''){
			$this->ajaxReturn(array('status'=>1,'info'=>'添加成功'));
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>$info));
		}
	}
	
	//中止公告
	public function suspendTask(){
		$id = I('nid','0','intval');
		$serverId = M('news') -> where(array('n_id'=>$id)) ->getField('n_ip');
		$status = M('news') -> where(array('n_id'=>$id)) -> save(array('n_callstatus'=>-1));
		if ($status){
			//操作记录
			action_log(session('username'),$serverId,4,'中止公告（'.$id.'）');
			$this->ajaxReturn(array('status'=>1,'info'=>'中止成功'));exit;
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'中止失败'));exit;
		}
	}
	
	//获取已发布的公告
	public function getNewsList(){
		$serverId = I('serverId','0','intval');
		$curPage = I('curPage','1','intval');
		$pageSize = I('pageSize','10','intval');
		if (empty($serverId)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));exit;
		}
		
		$where['n_ip'] = $serverId;
		$total = M('news')->where($where)->count();
		
		$totalPage = ceil($total/$pageSize);
		if ($curPage>$totalPage){
			$curPage = $totalPage;//当前页大于总页数
		}
		
		$list = M('news')->where($where)->order('n_id desc')->page($curPage,$pageSize)->select();
		$page = new \Common\Controller\AjaxPageController($pageSize, $curPage, $total, "getNewsList", "go","page");
		$pageHtml = $page->getPageHtml();
		$this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$list,'pageHtml'=>$pageHtml,'serverList'=>session('server_list')));exit;
	}
}