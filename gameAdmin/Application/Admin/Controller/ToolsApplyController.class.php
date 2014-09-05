<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-7-22
// +----------------------------------------------------------------------
// | Describe: 后台道具申请控制器
// +----------------------------------------------------------------------
namespace Admin\Controller;
class ToolsApplyController extends CommonController{
	public function _initialize(){
		//判断用户权限
		if (!in_array('00500400', session('user_code_arr'))){
			$this->display('Public/noauth');exit;
		}
	}
	
	//申请页面
	public function applyList(){
		$this->assign('serverList',session('server_list'));//服务器列表
		$this->assign('today',date('Y-m-d H:i:s',NOW_TIME));
		$this->display();
	}
	
	//通过申请记录id，获取申请道具详细
	public function getApplyDetail(){
		$tid = I('tid','0','intval');
		if (empty($tid)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误,刷新后重试'));exit;
		}
		$apply = M('tools_ask')->find($tid);
		$tools = M('tools_list')->where(array('t_ta_id'=>$tid))->select();
		$this->ajaxReturn(array('status'=>1,'moneyList'=>$apply,'toolList'=>$tools));
	}
	
	//添加申请
	public function applyAdd(){
		$rolename = I('rolename');	//角色名
		$serverId = I('serverId');	//服务器id
		$reason = I('reason');	//发送原因
		$title = I('title');	//邮件标题
		$content = I('content');	//邮件内容
		$gold = I('gold');	//元宝（非绑定）
		$copper = I('copper');	//铜币（非绑定）
		$toolList = I('toolList');	//	物品列表
		$srole = I('srole');	//1为给特定用户发送，2为全服
		$minLv = I('minLv');		//全服发送时用户最小等级限制
		$maxLv = I('maxLv');	//全服发达时用户最大等级限制
		$tasktime = I('tasktime');	//定时发送时间
		$state = I('state');	//全服发送时目标用户：1全部，2离线，3在线
		$fakermb = I('fakermb');		//发送给特定用户时元宝是否统计入充值排行1为统计0为不统计
		$emailTime = I('emailTime');	//全服发送时有限期限（数量）
		$day = I('day');	//全服发送时有限期限（天，周，时）
		
		if (empty($title)){
			$title = '系统邮件';
		}
		if (empty($gold)&&empty($copper)&&empty($toolList)){
			$this->ajaxReturn(array('status'=>0,'info'=>'申请数据不能为空'));exit;
		}
		if (empty($tasktime)){
			$tasktime = date('Y-m-d H:i:s',NOW_TIME);
		}
		
		if ($srole==1&&!empty($rolename)){
			$rolenameArr = explode(';',$rolename);//需要发送的用户角色名一维数组
			$info = '';
			foreach ($rolenameArr as $v){
				$userStatus = array();//根据角色名判断用户是否存在
				$userStatus = D('PlayerTable')->getByRoleName($serverId,$v,false,'normal');
				if (empty($userStatus)){
					$info .= '<'.$v.'用户不存在,申请失败>';
					continue;
					//$this->ajaxReturn(array('status'=>0,'info'=>$v.'用户不存在,申请中断'));exit;
				}
				$data = array();//需要插入申请表数据
				$data = array(
						't_role_name' => $v,
						't_ip' => $serverId,
						't_reason'=> $reason,
						't_title'=> $title,
						't_content' => $content,
						't_gold'=> $gold,
						't_copper' => $copper,
						't_operaor' => session('username'),
						't_inserttime' => date("Y-m-d H:i:s",NOW_TIME),
						't_status' => 1,//状态（-1：取消申请; -2：正在处理（定时任务）;1：申请中；2：申请不通过；3：已通过但发送失败；4：已通过但发送成功）
						't_minlv' => 0,
						't_maxlv' => 0,
						't_tasktme'=>$tasktime,
						't_result'=>-1,//0：未发送 ，1：已发送
						'fakermb'=>$fakermb,
						't_state'=>1,//1:全服用户2：在线 3：不在线
						't_endtime' => 0
				);
				$toolId = M('tools_ask')->add($data);
				if (!$toolId){
					$info .= '<为用户'.$v.'申请失败>';
					continue;
					//$this->ajaxReturn(array('status'=>0,'info'=>'申请失败'));exit;
				}
				//插入申请物品
				if ($toolId && !empty ( $toolList )) {
					$tools = array();//申请物品数组
					foreach ( $toolList as $tool ) {
						$tools [] = array (
								't_ta_id' => $toolId,
								't_tid' => $tool ['toolId'],
								't_name' => $tool ['toolName'],
								't_num' => $tool ['toolNum'],
								't_bstatus' => $tool ['toolBind'],
								't_inserttime' => date ( "Y-m-d H:i:s",NOW_TIME ) 
						);
					}
					$toolsListId = M('tools_list')->addAll($tools);
					if (!$toolsListId){
						M('tools_ask')->where(array('t_id'=>$toolId))->delete();
						$info .= '<为用户'.$v.'申请物品失败>';
						continue;
						//$this->ajaxReturn(array('status'=>0,'info'=>'申请物品失败'));exit;
					}
				}
			}
			if (empty($info)){
				$this->ajaxReturn(array('status'=>1,'info'=>'申请成功，等待审批'));exit;
			}else {
				$this->ajaxReturn(array('status'=>0,'info'=>$info));exit;
			}
		}elseif ($srole==2){
			if ($day==3){
				$emailTime = $emailTime*3600+NOW_TIME;//时
			}elseif ($day==1){
				$emailTime = $emailTime*24*3600+NOW_TIME;//天
			}elseif ($day==2){
				$emailTime = $emailTime*7*24*3600+NOW_TIME;//周
			}
			//全服发送
			$data = array(
					't_role_name' => '全服',
					't_ip' => $serverId,
					't_reason'=> $reason,
					't_title'=> $title,
					't_content' => $content,
					't_gold'=> $gold,
					't_copper' => $copper,
					't_operaor' => session('username'),
					't_inserttime' => date("Y-m-d H:i:s",NOW_TIME),
					't_status' => 1,//状态（1：申请中；-2：正在处理（定时任务）2：申请不通过；3：已通过但发送失败；4：已通过但发送成功）
					't_minlv' => $minLv,
					't_maxlv' => $maxLv,
					't_tasktme'=>$tasktime,
					't_result'=>-1,//0：未发送 ，1：已发送
					'fakermb'=>0,
					't_state'=>$state,//1:全服用户2：在线 3：不在线
					't_endtime' => $emailTime
			);
			$toolId = M('tools_ask')->add($data);
			if (!$toolId){
				$this->ajaxReturn(array('status'=>0,'info'=>'申请失败'));exit;
			}
			//插入申请物品
			if ($toolId && !empty ( $toolList )) {
				$tools = array();//申请物品数组
				foreach ( $toolList as $tool ) {
					$tools [] = array (
							't_ta_id' => $toolId,
							't_tid' => $tool ['toolId'],
							't_name' => $tool ['toolName'],
							't_num' => $tool ['toolNum'],
							't_bstatus' => $tool ['toolBind'],
							't_inserttime' => date ( "Y-m-d H:i:s",NOW_TIME )
					);
				}
				$toolsListId = M('tools_list')->addAll($tools);
				if (!$toolsListId){
					M('tools_ask')->where(array('t_id'=>$toolId))->delete();
					$this->ajaxReturn(array('status'=>0,'info'=>'申请物品失败'));exit;
				}
			}
			//操作记录
			action_log(session('username'),$serverId,7,'道具申请');
			$this->ajaxReturn(array('status'=>1,'info'=>'申请成功，等待审批'));exit;
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误,刷新后重试'));exit;
		}
	}
	
	//取消申请
	public function applyCancel(){
		$tid = I('tid','0','intval');
		if (empty($tid)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误,刷新后重试'));exit;
		}
		$toolsAsk = M('tools_ask')->find($tid);
		if ($toolsAsk['t_operaor']!=session('username')){
			$this->ajaxReturn(array('status'=>0,'info'=>'对不起，您不是申请人不能取消'));exit;
		}
		$status = M('tools_ask')->save(array('t_id'=>$tid,'t_status'=>-1,'t_result'=>2,'CmCmdResult'=>'申请人取消'));
		if ($status){
			//操作记录
			action_log(session('username'),$toolsAsk['t_ip'],7,'取消道具申请（'.$tid.'）');
			$this->ajaxReturn(array('status'=>1,'info'=>'取消成功'));exit;
		}else {
			$this->ajaxReturn(array('status'=>0,'info'=>'取消失败'));exit;
		}
	}
	
	//获取申请列表数据
	public function getApplyData(){
		$serverId = I('serverId','1','intval');//服务器id
		if (empty($serverId)){
			$this->ajaxReturn(array('status'=>0,'info'=>'参数错误,刷新后重试'));exit;
		}
		
		//获取后端处理结果
		D('PhpCmd')->getCmdDelResult('tools_ask',array('t_status'=>4,'t_result'=>0),'t_id','t_ip','t_uid','t_result');
				
		$pageSize = I('pageSize','10','intval');
		$curPage = I('curPage','1','intval');
		
		$where['t_ip'] = $serverId;
		//-1表示删除-2：正在处理（定时任务）1：申请中；2：申请不通过；3：已通过但发送失败；4：已通过但发送成功
		//$where['t_status'] = array('gt',0);
		$total = M('tools_ask')->where($where)->count();
		$totalPage = ceil($total/$pageSize);
		if ($curPage>$totalPage){
			$curPage = $totalPage;//当前页大于总页数
		}
		$applyList = M('tools_ask')->where($where)->order('t_id desc')->page($curPage,$pageSize)->select();
		
		$page = new \Common\Controller\AjaxPageController($pageSize, $curPage, $total, "applyList", "go","page");
		$pageHtml = $page->getPageHtml();
		$this->ajaxReturn(array('status'=>1,'applyList'=>$applyList,'pageHtml'=>$pageHtml,'serverList'=>session('server_list')));
	}
	
	//获取道具列表数据
	public function getToolsList(){
		$list = array();	//道具ID与道具名称列表
		$total = 0;			//记录总数
		
		$type1_map = array(
				'1' => '道具',
				'2' => '装备',
				'3' => '宝石',
				'4' => '材料',
				'5' => '其他'
		);
			
		$type2_map = array(
				'1' => array(
						'1' => '消耗品类',
						'2' => '传送道具',
						'3' => '喇叭',
						'4' => '资源类',
						'7' => '战斗辅助',
						'8' => '行囊',
						'9' => '藏宝图',
						'10' => '任务类',
						'11' => '召唤类',
						'12' => '武将技能书',
						'13' => '武将配饰',
						'14' => '幻化坐骑',
						'15' => 'VIP卡',
						'16' => '武将卡',
						'17' => '礼包'
				),
		
				'2' => array(
						'1' => '头盔',
						'2' => '衣服',
						'3' => '护手',
						'4' => '腰带',
						'5' => '裤子',
						'6' => '鞋子',
						'7' => '武器',
						'8' => '戒指',
						'9' => '项链',
						'10' => '时装衣服',
						'11' => '时装武器',
				),
		
		
				'3' => array(
						'1' => '普通宝石',
						'2' => '特性宝石'
				),
		
				'4' => array(
						'1' => '装备强化材料',
						'2' => '装备洗炼材料',
						'3' => '宝石材料',
						'4' => '装备材料',
						'5' => '装备合成材料',
						'6' => '任务材料',
						'7' => '命格材料',
						'8' => '刷任务材料',
						'9' => '任命官职材料',
						'10' => '坐骑材料',
						'11' => '神兵材料',
						'12' => '武将材料',
						'13' => '寻访道具',
						'14' => '碎片类材料'
				)
		
		);
		
		$type3_map = array(
				'1' => array(
						'1' => array(
								'1' => '角色瞬回HP',
								'2' => '角色瞬回MP',
								'3' => '角色持续HP',
								'4' => '角色持续MP',
								'5' => '角色储蓄包HP',
								'6' => '角色储蓄包MP',
								'7' => '武将持续HP',
								'8' => '武将储蓄包HP',
								'9' => '属性BUFF',
								'10' => '属性DEBUFF',
								'11' => '资源BUFF'
						),
						'2' => array(
								'1' => '行军令',
								'2' => '回城卷',
								'3' => '英豪令',
								'4' => '帮主令',
								'5' => '国家令'
						),
						'3' => array(
								'1' => '服务器喇叭',
								'2' => '跨服喇叭',
								'3' => '走马灯喇叭'
						),
						'4' => array(
								'1' => '铜币',
								'2' => '绑定铜币',
								'3' => '元宝',
								'4' => '绑定元宝',
								'5' => '礼券',
								'6' => '经验',
								'9' => '灵魄'
						),
						'7' => array(
								'1' => '小强丸'
						),
						'8' => array(
								'1' => '扩容背包'
						),
						'9' => array(
								'1' => '世界宝藏',
								'2' => '国家宝藏',
								'3' => '副本宝藏',
								'4' => '普通宝藏',
								'5' => '世界藏宝图碎片',
								'6' => '国家藏宝图碎片',
								'7' => '副本藏宝图碎片'
						),
						'10' => array(
								'1' => '委托任务',
								'2' => '生成任务'
						),
						'11' => array(
								'1' => '召唤NPC',
								'2' => '召唤monster'
						),
						'12' => array(
								'1' => '撕裂',
								'2' => '嗜血',
								'3' => '反击',
								'4' => '连击',
								'5' => '反震',
								'6' => '噬灵',
								'7' => '通灵',
								'8' => '灭魂',
								'9' => '丧胆',
								'10' => '破甲',
								'11' => '散神',
								'12' => '蛮击',
								'13' => '活力',
								'14' => '焕神',
								'15' => '培元',
								'16' => '神力',
								'17' => '迅捷',
								'18' => '强体',
								'19' => '明智',
								'20' => '聚神'
						),
						'13' => array(
								'1' => '玉佩',
								'2' => '明珠',
								'3' => '护符',
								'4' => '令牌',
								'5' => '宝镜'
						),
						'14' => array(
								'1' => '战狼',
								'2' => '虬龙',
								'3' => '麒麟'
						),
						'15' => array(
								'1' => '体验卡（30分钟）',
								'2' => '1天卡',
								'3' => '周卡',
								'4' => '月卡',
								'5' => '半年卡'
						),
						'16' => '武将卡',
						'17' => array(
								'1' => '一般随机礼包',
								'2' => '特殊随机礼包'
						)
				),
		
				'2' => array(
						'1' => '头盔',
						'2' => '衣服',
						'3' => '护手',
						'4' => '腰带',
						'5' => '裤子',
						'6' => '鞋子',
						'7' => '武器',
						'8' => '戒指',
						'9' => '项链',
						'10' => '时装衣服',
						'11' => '时装武器'
				),
		
		
				'3' => array(
						'1' => array(
								'1' => '力量',
								'2' => '敏捷',
								'3' => '体质',
								'4' => '智力',
								'5' => '精神'
						),
						'2' => array(
								'1' => '生命',
								'2' => '法力',
								'3' => '物攻',
								'4' => '物防',
								'5' => '法攻',
								'6' => '法防',
								'7' => '命中',
								'8' => '闪避',
								'9' => '暴击',
								'10' => '免爆'
						)
				),
		
				'4' => array(
						'1' => array(
								'1' => '装备强化石1',
								'2' => '装备强化石2',
								'3' => '装备强化石3',
								'4' => '装备强化石4',
								'5' => '装备强化石5',
								'6' => '装备强化石6',
								'7' => '装备强化石7',
								'8' => '装备强化石8',
								'9' => '装备强化石9',
								'10' => '装备强化石10',
								'11' => '装备强化石11',
								'12' => '装备强化石12',
								'21' => '1级强化幸运符',
								'22' => '2级强化幸运符',
								'23' => '3级强化幸运符',
								'24' => '4级强化幸运符',
								'25' => '5级强化幸运符',
								'26' => '6级强化幸运符',
								'27' => '7级强化幸运符',
								'28' => '8级强化幸运符',
								'29' => '9级强化幸运符',
								'30' => '10级强化幸运符',
								'31' => '11级强化幸运符',
								'32' => '12级强化幸运符'
						),
						'2' => array(
								'1' => '属性洗炼石1',
								'2' => '属性洗炼石2',
								'3' => '属性洗炼石3',
								'4' => '属性洗炼石4',
								'5' => '洗炼锁'
						),
						'3' => array(
								'1' => '合成保护符',
								'2' => '1级纯炼砂',
								'3' => '2级纯炼砂',
								'4' => '3级纯炼砂',
								'5' => '4级纯炼砂',
								'6' => '5级纯炼砂',
								'7' => '6级纯炼砂',
								'8' => '7级纯炼砂',
								'9' => '8级纯炼砂'
						),
						'4' => array(
								'1' => '1、2、3、4级灵珠',
								'2' => '精炼水晶',
								'3' => '精炼灵石',
								'4' => '熔炼符'
						),
						'5' => array(
								'1' => '火云碎片',
								'2' => '天星碎片'
						),
						'6' => array(
								'1' => '任务所需道具'
						),
						'7' => array(
								'1' => '占星石',
								'2' => '七星灯'
						),
						'8' => array(
								'1' => '更改品质',
								'2' => '不更改品质'
						),
						'9' => array(
								'1' => '任命皇后',
								'2' => '任命亲卫',
								'3' => '元帅任命官职材料',
								'4' => '丞相任命官职材料'
						),
						'10' => array(
								'1' => '坐骑进阶丹',
								'2' => '坐骑破魂丹',
								'3' => '孟婆汤',
								'4' => '坐骑灵魄',
									
						),
						'11' =>  array(
								'1' => '天灵丹',
								'2' => '五行丹'
						),
						'12' => array(
								'1' => '武将资质丹',
								'3' => '武将经验丹',
								'4' => '武将转生丹',
								'5' => '武将成长丹',
								'6' => '技能符文',
								'7' => '技能封印符',
								'8' => '忘魂丹',
								'9' => '技能魂石',
								'10' => '星晶',
								'11' => '重铸玄铁',
								'12' => '武将继承丹'
						),
						'13' => array(
								'1' => '寻贤令',
								'2' => '寻访令'
						),
						'14' => array(
								'1' => '装备原生碎片',
								'2' => '精炼水晶碎片',
								'3' => '精炼灵石碎片',
								'4' => '灵珠碎片',
								'5' => '宝石原生碎片',
								'6' => '普通宝石碎片',
								'7' => '特性宝石碎片',
								'8' => '武将原生碎片',
								'9' => '技能魂石碎片',
								'10' => '寻贤令碎片',
								'11' => '寻访令碎片',
								'12' => '坐骑原生碎片',
								'13' => '占星石碎片',
								'14' => '七星灯碎片'
						)
				)
		
		);
		
		$type1 = I('type1');
		$type2 = I('type2');
		$type3 = I('type3');
		$searchKey = I('searchKey');
		$pageSize = I('pageSize',10,'intval');
		$curPage = I('curPage',1,'intval');
		
		$where_sql  = '(t_type1 != -1 and t_type2 != -1 and t_type3 != -1) and ';	//-1为无用道具
		
		if($type1) {
			$where_sql .= "t_type1 = {$type1} and ";
		}
		
		if($type2) {
			$where_sql .= "t_type2 = {$type2} and ";
		}
		
		if($type3) {
			$where_sql .= "t_type3 = {$type3} and ";
		}
		
		
		if($searchKey) {
			if(is_numeric($searchKey)) {
				$where_sql .= 't_code like "%'.$searchKey.'%"';
			}else {
				$where_sql .= 't_name like "%'.$searchKey.'%"';
			}
		}
		
		$where_sql = rtrim($where_sql, ' and ');
		
		$total = M('tools_detail')->where($where_sql)->count();
		$totalPage = ceil($total/$pageSize);
		if ($curPage>$totalPage){
			$curPage = $totalPage;
		}
		$page = new \Common\Controller\AjaxPageController($pageSize, $curPage, $total, "getToolsList", "go1","page1");
		$pageHtml = $page->getPageHtml();
		
		$list = M('tools_detail')->where($where_sql)->page($curPage,$pageSize)->select();
		$result = array(
				'status'=>1,
				'list' => $list,
				'type1_map' => $type1_map,
				'type2_map' => $type2_map,
				'type3_map' => $type3_map,
				'pageHtml' => $pageHtml
		);
		$this->ajaxReturn($result);
	}
}