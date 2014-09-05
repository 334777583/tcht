<?php
/**
 * FileName: typelose.class.php
 * Description:道具消耗页面
 * Author: jan
 * Date:2013-11-6 上午11:36:42
 * Version:1.00
 */
class typelose{
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
			if(!in_array("00401600", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	/**
	 * 道具消耗页面
	 */
	public function show(){
		$ipList = parent::getGmList();
		foreach($name as $key => $value){
			$type[$key]['name'] = $value; 
			$type[$key]['state'] =$state[$key];
		}
		$this->assign("startdate",date("Y-m-d").' 00:00:00');
		$this->assign("enddate",date("Y-m-d H",strtotime('+ 1 hour')).':00:00');
		$this->assign("ystartdate",date("Y-m-d",strtotime("-7 days")).' 00:00:00');
		$this->assign("yenddate",date("Y-m-d H:i:s",strtotime("-1 days")));
		$this->assign("Sname",$type);
		$this->assign("code", $this->user["code"]);
		$this->assign("ipList",$ipList);
		$this->display("money/type_lose");
		
	}
}