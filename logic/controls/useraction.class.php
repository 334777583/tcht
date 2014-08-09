<?php
/**
 * FileName: useraction.class.php
 * Description:行为分析
 * Author: xiaoliao
 * Date:2013-9-24 18:19:57
 * Version:1.00
 */
class useraction{
	/**
	 * 服务器IP
	 * @var string
	 */
	public $ip;
	
	
	/**
	 * 用户数据
	 * @var Array
	 */
	public $user;
	
	/**
	 * 结束时间
	 * @var string
	 */
	private $enddate;
	
	/**
	 * 开始时间
	 * @var string
	 */
	private $startdate;
	
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00401100', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		
		$this->ip =  get_var_value('ip') == NULL? -1 : get_var_value('ip');
		$this->startdate = get_var_value("startDate") == NULL?date("Y-m-d",strtotime("-7 day")):date("Y-m-d",strtotime(get_var_value("startDate")));
		$this->enddate =  get_var_value('endDate') == NULL? '' : get_var_value('endDate');
		
	
	}
	

	/**
	 * 获取行为分析数据
	 */
	public function getResult() {
		// print_r($_POST);
		$point = D('game'.$this->ip);
		//show columns from table_name from database_name
		//$result = $point->fquery("SELECT * FROM {$_POST['table']} WHERE time BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		
		/*
		$result = array();
		if ($_POST['table'] == 'xitongrenwu') {
			$result = $point->fquery("SELECT SUM(xuanshangrwwc) 悬赏任务完成,SUM(xuanshangadd) 悬赏增加,SUM(qingjiaorwwc) 清剿任务完成,SUM(qingjiaoadd) 清剿增加 FROM {$_POST['table']} WHERE time BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['table'] == 'jhhb') {
			$result = $point->fquery("SELECT SUM(chuangdangjhtg) 闯荡江湖过关,SUM(chuangdangjhchzh) 闯荡江湖重置,SUM(gumuqytg) 古墓奇缘通过,SUM(gumuqychzh) 古墓奇缘重置,SUM(wentaowltg) 文韬武略通过,SUM(wentaowljs) 文韬武略加速,SUM(hsongchg) 护送出关,SUM(taihushz) 太湖水贼 FROM {$_POST['table']} WHERE time BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['table'] == 'teshewf') {
			$result = $point->fquery("SELECT SUM(shenglongdingxljq) 神龙鼎香料聚气,SUM(nxgxuanxiu) 凝香阁选秀,SUM(nxglueduo) 凝香阁掠夺,SUM(nxggunli) 凝香阁鼓励,SUM(nxgmeiren) 凝香阁美人技术升级,SUM(nxgaixin) 凝香阁爱心小店兑换,SUM(nxgjiasu) 凝香阁加速,SUM(nxgshuaxin) 凝香阁刷新,SUM(qiankunshj) 乾坤升级,SUM(xinghunshj) 星魂升级,SUM(xinfashj) 心法升级,SUM(qiyu) 奇遇,SUM(wulinzhb) 武林争霸,SUM(zhuoyuejj) 卓越进阶,SUM(zhuoyuexil) 卓越洗练 FROM {$_POST['table']} WHERE time BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['table'] == 'rchxw') {
			$result = $point->fquery("SELECT SUM(mianfeichm) 免费冲脉,SUM(yuanbaochm) 元宝冲脉,SUM(zuoqijinjie) 坐骑进阶,SUM(zuoqijinengjj) 坐骑技能进阶,SUM(zhuangbeiqh) 装备强化,SUM(linghunbshhc) 灵魂宝石合成,SUM(banghjx) 帮会捐献,SUM(banghuiqifu) 帮会祈福,SUM(bangzhan) 帮战,SUM(bhjinengyanfa) 帮会技能研发,SUM(baitan) 摆摊,SUM(baitangoumai) 摆摊购买,SUM(zhufuyousj) 祝福油升级,SUM(chaojizhufyjs) 超级祝福油升级,SUM(zhuizongchouren) 追踪仇人,SUM(haoyoutj) 好友添加,SUM(fenjie) 分解,SUM(maiwu) 卖物 FROM {$_POST['table']} WHERE time BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['table'] == 'gmxw') {
			$result = $point->fquery("SELECT SUM(chxqwjgm) 促销区玩家购买,SUM(shangchenggm) 商城购买,SUM(shangdiangm) 商店购买 FROM {$_POST['table']} WHERE time BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}
		// print_r($result);
		$arr1 = array();

		foreach ($result[0] as $key => $value) {
			$arr1[] = "\"name\":\"".$key."\",\"num\":".$value;
			// echo $key.'<br/>'.$value.'<br/>';
			
		}
		// print_r($arr1);

		echo $str = '[{'.implode('},{', $arr1).'}]';
	*/
	}
	
	
	/**
	 * 日常副本
	 */
	public function getcarbon(){
		$point = D('game'.$this->ip);
		$list = $point -> table('scen_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result =$point->field('sum(zhx) as 温酒斩华雄 ,sum(ddg) as 董府地宫 ,sum(dlb) as 血战吕布单人 ,sum(slb) as  血战吕布组队,sum(ddq) as  千里走单骑单人,sum(sdq) as  千里走单骑组队,sum(sxy) as 火烧新野城 ,sum(djt) as 点将台 ,sum(cbz) as 赤壁之战,sum(zlq) as 玲珑棋局 ,sum(dkc) as  空城计单人,sum(skc) as  空城计组队')->table('scen_count')->where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
	}
	
	/**
	 * 日常任务
	 */
	public function gettask(){
		$point = D('game'.$this->ip);
		$list = $point -> table('task_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point -> field('sum(hs) as 护送任务,sum(tf) as 讨伐任务,sum(xb) as 巡边任务,sum(xs) as 悬赏任务,sum(kg) as 考古任务,sum(sj) as 收集物质,sum(ct) as 国家刺探')->table('task_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		
	}
	
	/**
	 * 日常活动
	 */
	public function getact(){
		$point = D('game'.$this->ip);
		$list = $point -> table('scen_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result =$point->field('sum(nm_player) as 南蛮入侵（玩家数） ,sum(nm_num) as 南蛮入侵（完成任务数） ,sum(sm_player) as 疯狂赛马（玩家数） ,sum(zj_player) as  煮酒论英雄（玩家数）,sum(mk_player) as  魔窟秘境（玩家数）,sum(tx_player) as 铁血战场')->table('scen_count')->where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
	}	
	
	/**
	 * 横扫千军
	 */
	public function getsweep(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point -> field('sum(gmtz) as 购买挑战次数,sum(guwu) as 鼓舞次数,sum(qccd) as 清除CD次数,sum(tzcs) as 挑战次数')->table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 点将台
	 */
	public function getpoint(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point -> field('sum(djtzj) as 战将台购买次数,sum(djt) as 战将挑战次数')->table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 摸金
	 */
	public function getgold(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point ->field('sum(mjone) as 春秋摸金1次,sum(mjten) as 春秋摸金10次,sum(mjfifty) as 春秋摸金50次,sum(dzmjone) as 大周古墓摸金1次,sum(dzmjten) as 大周古墓摸金10次,sum(dzmjfifty) as 大周古墓摸金50次')-> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 钱庄
	 */
	public function getbank(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point -> field('sum(tzyk) as 投资月卡钱庄次数,sum(tzqz) as 投资升级钱庄次数')->table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 挑战
	 */
	public function getdekaron(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point -> field('sum(tzgj) as 挑战鬼将精英次数, sum(tzsj) as挑战神将boss次数')->table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 炼化
	 */
	public function getsmelt(){
		$point = D('game'.$this->ip);
		$list = $point -> table('item_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point ->field('sum(zb) as 炼化装备次数,sum(bs) as 炼化宝石次数,sum(wj) as 炼化武将次数,sum(yp) as 炼化药品次数,sum(other) as 炼化其他次数 ')-> table('item_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 宝石
	 */
	public function getgem(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point -> field('sum(bssq) as 宝石镶嵌次数,sum(bsrh) as 宝石融合次数, sum(bszh) as 宝石转换次数, sum(bscl) as 宝石纯炼次数')->table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 打造
	 */
	public function getcreate(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point ->field('sum(qh) as 强化次数, sum(xl) as 洗煤次数,sum(fj) as 分解次数,sum(sj) as 升级次数,sum(rl) as 熔炼次数,sum(jl) as 精炼次数')-> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 神兵
	 */
	public function getmagic(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point ->field('sum(sbjs) as 神兵晋升次数,sum(sbzl) as 神兵注灵次数, sum(sbwx) as 神兵五行变化次数, sum(sbcd) as 神兵聚灵清除CD次数, sum(sbjl) as 神兵聚灵次数')-> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 武将
	 */
	public function getpie(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point ->field('sum(wjlg) as 武将成长次数,sum(wjjn) as武将技能刷新次数, sum(wjps) as 武将配饰升星次数,sum(wjzs) as 武将转生次数,sum(wjjc) as 武将继承次数,sum(bsbf) as 武将兵书拜访次数,sum(bsxf) as 武将兵书寻访次数')-> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 星宿
	 */
	public function getstar(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point ->field('sum(xxcd) as 星宿清除冷却CD次数, sum(xxdl) as 星宿点亮次数, sum(xxzx) as 星宿占星次数, sum(gxsh) as 观星收获次数, sum(gxnt) as 观星逆天改命次数')-> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 坐骑
	 */
	public function gethorse(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point ->field('sum(zjdy) as 坐骑丹药喂养次数,sum(zjdj) as 坐骑道具喂养次数,sum(zjtp) as 坐骑突破内丹次数,sum(zjjn) as 坐骑技能升级次数,sum(zjhh) as 坐骑幻化次数')-> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 国家
	 */
	public function getcity(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point ->field('sum(gjsd) as 国家商户购买次数,sum(gjml) as 国家谋略学习次数')-> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 邮件
	 */
	public function getemail(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point -> field('sum(yjfs) as 邮件发送次数')->table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 队伍
	 */
	public function getteam(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point -> field('sum(dw) as 创建队伍次数')->table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 好友
	 */
	public function getfriend(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point ->field('sum(shcs) as 送花次数,sum(hytj) as 好友添加次数,sum(hysc) as 好友删除次数,sum(hyzf) as 好友祝福次数')-> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 其他
	 */
	public function getother(){
		$point = D('game'.$this->ip);
		$list = $point -> table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->select();
		$result = $point -> field('sum(qdlj) as 签到领奖次数,sum(bc1) as 补偿50领取次数,sum(bc2) as 补偿100领取次数,sum(scgm) as 商城购买次数')->table('active_count')-> where(array('a_createtime >='=>$this->startdate,'a_createtime <='=>$this->enddate)) ->find();
		foreach($result as $key =>$value){
			$arr1[]=array('name'=>$key,'num'=>$value);
		}
		echo json_encode(array(
						'startDate'=>$this->startdate,
						'endDate'=>$this->enddate,
						'list'=>$list,
						'result'=>$arr1
						));
		exit;
		
	}
	
	/**
	 * 全部
	 */
	public function getall(){
		$point = D('game'.$this->ip);
		$list = $point -> table('')-> where(array(''=>$this->startdate,''=>$this->enddate)) ->select();
		echo json_encode(array(
						''=>$this->startdate,
						''=>$this->enddate,
						''=>$list
						));
		
	}
		
	
}