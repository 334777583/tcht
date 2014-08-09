<?php
/**
   * FileName		: wing.class.php
   * Description	: 元宝消费比例
   * Author	    : zwy
   * Date			: 2014-6-6
   * Version		: 1.00
   */
class wing{
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
			if(!in_array("00200500", $this->user["code"])){
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
		$this->assign('startdate',date('Y-m-d',strtotime('-1 day')));
		$this->display("money/wing");
	}
	
	
}

 