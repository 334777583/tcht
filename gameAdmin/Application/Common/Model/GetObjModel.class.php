<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-26
// +----------------------------------------------------------------------
// | Describe: 玩家角色信息模型
// +----------------------------------------------------------------------
namespace Common\Model;
use Think\Model;
class GetObjModel extends Model{
	public function getObj($serverId=1){
		$gameDatabase = C('GAME_ANALYSIS_PREFIX').$serverId;//数据库配置下标
		return $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
	}
}