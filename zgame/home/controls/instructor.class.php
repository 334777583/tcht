<?php
/**
   * FileName		: dailyreport.class.php
   * Description	: 游戏指导员设置
   * Author	    : zwy
   * Date			: 2014-9-26
   * Version		: 1.00
   */
class instructor{
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
			if(!in_array("00502100", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	/**
	 * 充值等级分布
	 */
	public function show(){
		$ipList = parent::getIpList();
		$this->assign("ipList",$ipList);
		$this->display("gmtools/instructor");
	}
	
	
}

 