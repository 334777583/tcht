<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-12
// +----------------------------------------------------------------------
// | Describe: 禁止玩家登录模型
// +----------------------------------------------------------------------

namespace Common\Model;
use Think\Model;
class ForbidLoginModel extends Model{
	//冻结用户
	public function addData($serverid=1,$data=array()){
		$gameDatabase = C('GAME_DATA_PREFIX').$serverid;//数据库配置下标
		$obj = $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
		return $obj->add($data);
	}
	//解冻用户
	public function delete($serverid=1,$account=''){
		$gameDatabase = C('GAME_DATA_PREFIX').$serverid;//数据库配置下标
		$obj = $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
		$sql = "delete from forbid_login where Account='{$account}'";
		return $obj->execute($sql);
	}
}