<?php
/**
   * FileName		: playerTrad.class.php
   * Description	: 玩家装备操作查询
   * Author	    : zwy
   * Date			: 2014-11-5
   * Version		: 1.00
   */
class playertrad{
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
			if(!in_array("00301200", $this->user["code"])){
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
		$this->display("user/playertrad");
	}
	
	
}

 