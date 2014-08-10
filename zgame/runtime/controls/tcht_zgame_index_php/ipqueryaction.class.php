<?php
/**
   * FileName		: ip.class.php
   * Description	: ip查询
   * Author	    : zwy
   * Date			: 2014-6-6
   * Version		: 1.00
   */
class ipqueryAction extends Common {
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
			if(!in_array("00502000", $this->user["code"])){
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
		$this->assign('startdate',date('Y-m-d'));
		$this->display("gmtools/ip");
	}
	
	
}

 