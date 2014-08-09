<?php
/**
 * FileName: gmgrow.class.php
 * Description:用户成长日志页面
 * Author: jan
 * Date:2013-11-6 上午11:36:42
 * Version:1.00
 */
class gmgrowAction extends Common {
	/**
	 * 登录用户信息
	 */
	private $user;

	/**
	 * 初始化数据
	 */
	public function init(){
		$userobj = D("sysuser");
		if($this->user = $userobj->isLogin()){
			if(!in_array("00401000", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	/**
	 * 热启动页面
	 */
	public function show(){
		$ipList = parent::getGmList();
		$sy_name = "GM命令,数据库,掉落,道具,邮件,npc";
		$sy_state = "admincommond,database,drop,item,mail,npc";
		$name = explode(",",$sy_name);
		$state = explode(",",$sy_state);
		foreach($name as $key => $value){
			$type[$key]['name'] = $value; 
			$type[$key]['state'] =$state[$key];
		}
		$this->assign("startDate",date("Y-m-d",strtotime('-1 day')));
		$this->assign("endDate",date("Y-m-d"));
		$this->assign("Sname",$type);
		$this->assign("code", $this->user["code"]);
		$this->assign("ipList",$ipList);
		$this->display("gmtools/gm_grow");
	}
}