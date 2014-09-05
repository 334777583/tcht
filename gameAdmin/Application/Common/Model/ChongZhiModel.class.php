<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 ☆★☆★☆     All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五星（☆★☆★☆）<2329697501@qq.com>
// +----------------------------------------------------------------------
// | Date: 2014-7-22
// +----------------------------------------------------------------------
// | Describe: 充值模型
// +----------------------------------------------------------------------
namespace Common\Model;
use Think\Model;
class ChongZhiModel extends Model{
	protected $tableName = 'chongzhi';
	protected $_scope = array (
			'normal' => array (
					'field' => array (
							'c_id','c_openid','c_pf','c_price','c_num','c_billno','token_id','payamt_coins',
							'pubacct_payamt_coins','c_amt','c_ts','c_sid','c_state','c_time','c_pid','c_level','c_times'
					),
			),
	);
	protected $obj;
	
	/**
	 * 通过条件获取分页数据
	 * @param unknown $where
	 * @param number $curPage
	 * @param number $pageSize
	 * @param string $order
	 * @return unknown
	 */
	public function getPageData($where=array(),$curPage=1,$pageSize=15,$order='c_id asc'){
		$this->obj = $this->db('chongzhi',getDbConfig(C('chongzhi')));//切换数据库
		$data = $this->obj->scope('normal')->where($where)->order($order)->page($curPage,$pageSize)->select();
		return $data;
	}
	
	/**
	 * 根据条件获取总记录数
	 * @param unknown $where
	 * @return unknown
	 */
	public function getTotal($where){
		$this->obj = $this->db('chongzhi',getDbConfig(C('chongzhi')));//切换数据库
		$count = $this->obj->where($where)->count();
		return $count;
	}
	
	/**
	 * 通过sql查询
	 * @param unknown $sql
	 * @return unknown
	 */
	public function getBySql($sql){
		$this->obj = $this->db('chongzhi',getDbConfig(C('chongzhi')));//切换数据库
		$data = $this->obj->query($sql);
		return $data;
	}
	
	/**
	 * 根据条件获取所有数据
	 * @param array $where
	 * @return array 
	 */
	public function getAll($where=array(),$order='c_id asc'){
		$this->obj = $this->db('chongzhi',getDbConfig(C('chongzhi')));//切换数据库
		$data = $this->obj->scope('normal')->where($where)->order($order)->select();
		return $data;
	}
	
	//todo
	public function getOne(){
		
	}
}