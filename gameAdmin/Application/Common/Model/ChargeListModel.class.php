<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-5
// +----------------------------------------------------------------------
// | Describe: 玩家角色信息模型
// +----------------------------------------------------------------------
namespace Common\Model;
use Think\Model;
class ChargeListModel extends Model{
	public function addData($serverid=1,$data=array()){
		$gameDatabase = C('GAME_DATA_PREFIX').$serverid;//数据库配置下标
		$obj = $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
		return $obj->add($data);
	}
}