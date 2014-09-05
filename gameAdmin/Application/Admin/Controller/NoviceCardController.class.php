<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-19
// +----------------------------------------------------------------------
// | Describe: 后台生成新手卡控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;
class NoviceCardController extends CommonController{
	public function _initialize(){
		//判断用户权限
		if (!in_array('00500500', session('user_code_arr'))){
			$this->display('Public/noauth');exit;
		}
	}
	
	//新手卡生成页面
	public function index(){
		$this->assign('serverList',session('server_list'));//服务器列表
		$this->assign('today',date('Y-m-d',NOW_TIME));
		$this->display();
	}
	
	//生成新手卡
	public function createCard(){
		$codeType = I('codeType',1,'intval');//新手卡类型
		$num = I('num',0,'intval');//生成数量
		$serverId = I('serverId',1,'intval');//服务器id
		$sxGroup = I('sxGroup',1,'intval');//是否限制在有限期内使用
		$sxtime = I('sxtime');//过期时间
		$toolsId = I('toolsId',0,'intval');//礼包id
		
		if (empty($num)){
			$this->ajaxReturn(array('status'=>0,'info'=>'生成数量不对！'));
		}elseif (empty($toolsId)){
			$this->ajaxReturn(array('status'=>0,'info'=>'礼包id不对！'));
		}elseif ($sxGroup==2&&$sxtime<=date('Y-m-d H:i:s',NOW_TIME)){
			$this->ajaxReturn(array('status'=>0,'info'=>'有效期不对！'));
		}elseif ($sxtime!=0){
			$sxtime = strtotime($sxtime);
		}
		
		//生成随机字符串(新手卡号码)
		$key = 'xinshouka';
		$time = microtime(true);
		$codeArr = array();
		for($i = 0; $i<$num; $i++) {
			$code = md5($key.$time.$i);
			$first = strtoupper(substr($code, 0, 5));
			$second = substr($code, 5, 4);
			$codeArr[] = $codeType.$first.$second;
		}
		
		if ($serverId){
			$serverList[0] = array('g_id'=>$serverId);
		}else {
			$serverList = session('server_list');
		}
		
		$list = array (); // 激活码详细信息
		$info = '';//错误信息
		foreach ( $serverList as $k => $v ) {
			$data = array();
			$listTem = array();
			foreach ( $codeArr as $kc => $vc ) {
				$data [] = array (
						'sn_code' => $vc,
						'pack_type' => $codeType,
						'PlayerGUID' => 0,
						'gift_item_id' => $toolsId,
						'end_time' => $sxtime,
						'server_id' => $v ['g_id'] 
				);
				
				$list[] = $listTem[] = array (
						'code' => $vc,
						'type' => $codeType,
						'player_id' => 0,
						'ip' => $v ['g_id'],
						'item_id' => $toolsId,
						'end_time' => $sxtime,
						'operaor' => session ( 'username' ),
						'instime' => date ( 'Y-m-d H:i:s', NOW_TIME ) 
				);
			}
			$status = D('ActivityGift')->insertAllData($v['g_id'],$data);
			if (!$status){
				$info .= '服务器'.$v['g_id'].'添加失败;';continue;
			}
			M('new_gift')->addAll($listTem);
			//操作记录
			action_log(session('username'),$v['g_id'],9,"生成新手卡,类型：$codeType,数量：$num,礼包ID：$toolsId");
		}
		if (!empty($info)){
			$this->ajaxReturn(array('status'=>0,'info'=>$info));
		}else{
			$this->ajaxReturn(array('status'=>1,'info'=>'生成成功！','list'=>$list,'serverList'=>session('server_list')));
		}
	}
	
	//新手卡查询页面
	public function getCardList(){
		if (IS_POST){
            $condition = I('condition',1,'intval');//2为角色 1为激活码
            $codeText = I('codeText');//为空为查询全部
            $ctype = I('ctype',1,'intval');//激活码类型
            $serverId = I('serverId',1,'intval');//服务器id
            $enddate = I('enddate');//过期日期
            $curPage = I('curPage',1,'intval');//当前页
            $pageSize = I('pageSize',25,'intval');//页码大小
            if (empty($serverId)){
                $this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));exit;
            }
            $where = array();
            $where['server_id'] = $serverId;
            $where['pack_type'] = $ctype;
            if (!empty($codeText)){
                if($condition==2){
                    $where['PlayerGUID'] = $codeText;
                }elseif($condition==1){
                    $where['sn_code'] = $codeText;
                }
            }
            if(!empty($enddate)){
                $start = strtotime($enddate);
                $end = strtotime($enddate)+24*60*60;
                $where['end_time'] = array('between',array($start,$end));
            }

            //符合条件的充值记录
            $total = D('ActivityGift')->getTotal($serverId,$where);
            $totalPage = ceil($total/$pageSize);
            if ($curPage>$totalPage){
                $curPage = $totalPage;
            }
            $list = D('ActivityGift')->getPageData($serverId,$where,$curPage,$pageSize);

            if (!empty($list)){
                $page = new \Common\Controller\AjaxPageController($pageSize, $curPage, $total, "getCardList", "go","page");
                $pageHtml = $page->getPageHtml();
                $this->ajaxReturn(array('status'=>1,'info'=>'查询成功','list'=>$list,'pageHtml'=>$pageHtml,'serverList'=>session('server_list')));
            }else {
                $this->ajaxReturn(array('status'=>0,'info'=>'没有数据'));
            }

        }else {
            $this->assign('serverList',session('server_list'));//服务器列表
            $this->display();
        }
	}
}