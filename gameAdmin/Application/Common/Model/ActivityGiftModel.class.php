<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-8-19
// +----------------------------------------------------------------------
// | Describe:新手卡模型
// +----------------------------------------------------------------------
namespace Common\Model;
use Think\Model;
class ActivityGiftModel extends Model{
    protected $_scope = array (
        'normal' => array (
            'field' => array (
               'id','gift_item_id','pack_type','sn_code','end_time','server_id','PlayerGUID'
            ),
        )
    );
	/**
	 * 插入新手卡
	 * @param number $serverId		服务器id
	 * @param array $data 	插入数据（二维数组）
	 * @return int
	 */
	public function insertAllData($serverId=1,$data=array()){
		$gameDatabase = C('GAME_DATA_PREFIX').$serverId;//数据库配置下标
		$obj = $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
		$status = $obj->addAll($data);
		return $status;
	}

    /**
     * 根据条件获取分页数据
     * @param number $serverid		服务器id
     * @param array $where			条件
     * @param number $curPage		当前页
     * @param number $pageSize	页码
     * @param string $filed		命令范围
     * @param string $order		排序
     * @return array
     */
    public function getPageData($serverid=1,$where=array(),$curPage=1,$pageSize=15,$filed='normal',$order='id asc'){
        $gameDatabase = C('GAME_DATA_PREFIX').$serverid;//数据库配置下标
        $obj = $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
        $data = $obj->scope($filed)->where($where)->order($order)->page($curPage,$pageSize)->select();
        return $data;
    }

    /**
     * 根据条件获取总记录数
     * @param int $serverid
     * @param array $where
     * @return int
     */
    public function getTotal($serverid,$where){
        $gameDatabase = C('GAME_DATA_PREFIX').$serverid;//数据库配置下标
        $obj = $this->db($gameDatabase,getDbConfig(C($gameDatabase)));//切换数据库
        $count = $obj->where($where)->count();
        return $count;
    }
}