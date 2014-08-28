<?php
/**
   * FileName		: killboss.class.php
   * Description	: 玩家装备操作查询
   * Author	    : zwy
   * Date			: 2014-8-8
   * Version		: 1.00
   */
class killboss{
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
			if(!in_array("00301000", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	/**
	 * 玩家装备操作查询
	 */
	public function show(){
		$ipList = parent::getIpList();
		$this->assign("ipList",$ipList);
		$this->assign('startdate',date('Y-m-d'));
		$this->display("user/killboss");
	}
	
	
}

 