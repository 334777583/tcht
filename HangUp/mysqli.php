<?php
class DB {
	protected $dbhost = '';//主机ip
	protected $dbname = '';//数据库名称
	protected $dbusername = '';//用户名
	protected $dbpassword = '';//用户密码
	protected $setnames = 'utf8';
	protected $sql = '';
	protected $tabName = '';//表名
	protected $where = '';
	protected $group = '';
	protected $order = '';
	protected $limit = '';
	protected $port = 3306;
	protected $fieldList;
	protected $field;
	protected $obj;
	public $error;//错误信息
	protected $path='./log/databaseError.log';//错误日记路径
	public function __construct($dbname = '', $host = '', $user = '', $pass = '', $port = 3306) {
		$this->dbname = $dbname;
		$this->dbhost = $host;
		$this->dbusername = $user;
		$this->dbpassword = $pass;
		$this->port = $port;
		if (! empty ( $this->obj )) {
			return $this->obj;
		}
		$this->connect ();
	}
	protected function connect() {
		$openconn = @new mysqli ( $this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname, $this->port );
		if (mysqli_connect_errno ()) {
			writeFile("Connect failed: ".mysqli_connect_error (), $this->path);
			$this->error = "Connect failed: ".mysqli_connect_error ();
		}else {
			$this->query ( $openconn, "SET NAMES '" . $this->setnames . "'" );
			$this->obj = $openconn;
		}
	}
	protected function query($sql, $handle) {
		$this->clearfield ();
		return mysqli_query ( $sql, $handle );
	}
	protected function returnSql() {
		$array_result = "";
		$db_result = $this->query ( $this->obj, $this->sql );
		if ($db_result) {
			$array_result = mysqli_fetch_array ( $db_result, MYSQLI_ASSOC );
			mysqli_free_result ( $db_result );
			return $array_result;
		} else {
			writeFile('Error : '.$this-> obj -> error.';Error SQL：'.$this->sql, $this->path);
			$this->error = 'SQL Error <br>'.$this->sql .'<br> Error : '.$this-> obj -> error;
			return false;
		}
	}
	protected function executeSql() {
		$result = $this->query ( $this->obj, $this->sql );
		
		if (! $result) {
			writeFile('Error : '.$this-> obj -> error.';Error SQL：'.$this->sql, $this->path);
			$this->error = "SQL Error :" . $this -> sql." Error : ".$this-> obj -> error;
			return false;
		} else {
			return true;
		}
	}
	protected function sumcount() {
		$result = $this->query ( $this->obj, $this->sql );
		
		if (! $result) {
			writeFile('Error : '.$this-> obj -> error.';Error SQL：'.$this->sql, $this->path);
			$this->error = "SQL Error :" . $this -> sql." Error : ".$this-> obj -> error;
			return false;
		} else {
			$r = mysqli_fetch_array ( $result, MYSQLI_ASSOC );
			return $r ['count'];
		}
	}
	protected function insertSql() {
		$result = $this->query ( $this->obj, $this->sql );
		
		if (! $result) {
			writeFile('Error : '.$this-> obj -> error.';Error SQL：'.$this->sql, $this->path);
			$this->error = "SQL Error :" . $this -> sql ." Error : ".$this-> obj -> error;
			return false;
		} else {
			return mysqli_insert_id ( $this->obj );
		}
	}
	protected function arraySql() {
		$array_result = "";
		$db_result = $this->query ( $this->obj, $this->sql );
		if ($db_result) {
			$i = 0;
			while ( $row = mysqli_fetch_array ( $db_result, MYSQLI_ASSOC ) ) {
				$array_result [$i] = $row;
				$i ++;
			}
			mysqli_free_result ( $db_result );
			return $array_result;
		} else {
			writeFile('Error : '.$this-> obj -> error.';Error SQL：'.$this->sql, $this->path);
			$this->error = "SQL Error :" . $this->sql . "   Error : " . $this->obj->error;
			return false;
		}
	}
	private function setTable() {
		$result = $this->query ( $this->obj, "desc {$this->tabName}" );
		$fields = array ();
		if ($result) {
			while ( $row = mysqli_fetch_array ( $result ) ) {
				if ($row ["Key"] == "PRI") {
					$fields ["pri"] = strtolower ( $row ["Field"] );
				} else {
					$fields [] = strtolower ( $row ["Field"] );
				}
			}
			if (! array_key_exists ( "pri", $fields )) {
				$fields ["pri"] = array_shift ( $fields );
			}
		}
		if (empty($fields)){
			$this->field = '*';
		}else {
			$this->fieldList = $fields;
		}
	}
	private function updte_result() {
		$db_result = $this->query ( $this->obj, $this->sql );
		if (! $db_result) {
			writeFile('Error : '.$this-> obj -> error.';Error SQL：'.$this->sql, $this->path);
			$this->error = 'SQL ERROR : <br>'.$this->sql .'<br> Error :'.$this-> obj -> error;
			return false;
		}
		return mysqli_affected_rows ( $this->obj );
	}
	public function __call($name, $arguments) {
		writeFile('your call function ' . $name . ' and arguments ' . $arguments . ' is not exist ' , $this->path);
		$this->error =  'your call function ' . $name . ' and arguments ' . $arguments . ' is not exist ' ;
	}
	public function __toString() {
		return 'this is Mysqli class';
	}
	public function __destruct() {
		if ($this->obj != null) {
			$this->obj->close ();
			unset ( $this );
		}
	}
	public function return_sql() {
		return $this->sql;
	}
	
	/**
	 * FunctionName: table
	 * Description: 设置表名
	 * Return: Object
	 * @param string Date: 2012年12月19日14:59:10
	 */
	public function table($name) {
		$this->tabName = $name;
		$this->setTable ();
		return $this;
	}
	
	/**
	 * FunctionName: where
	 * Description: 设置where
	 * Return: Object
	 * @param string Date: 2012年12月19日14:59:04   	
	 */
	public function where($where) {
		if (is_array ( $where )) {
			$this->where = " where ";
			foreach ( $where as $key => $value ) {
				if (strpos ( $key, " " )) {
					$this->where .= $key . "'" . $value . "'" . " and ";
				} else {
					$this->where .= $key . "=" . "'" . $value . "'" . " and ";
				}
			}
			$this->where = rtrim ( $this->where, " and " );
		} else {
			$this->where = ' where ' . $where;
		}
		return $this;
	}
	
	/**
	 * FunctionName: field
	 * Description: 设置field
	 * Author: xiaochengcheng
	 * Return: Object
	 * @param string Date: 2013-4-8 16:07:40 
	 */
	public function field($field) {
		$this->field = $field;
		return $this;
	}
	
	/**
	 * FunctionName: where
	 * Description: 设置where
	 * Return: Object
	 * @param string Date: 2012年12月19日14:59:04
	 */
	public function order($order) {
		$this->order = ' ORDER BY ' . $order;
		return $this;
	}
	
	/**
	 * FunctionName: group
	 * Description: 设置group
	 * Return: Object
	 * @param string Date: 2012年12月19日14:59:04
	 */
	public function group($group) {
		$this->group = ' GROUP BY ' . $group;
		return $this;
	}
	
	/**
	 * FunctionName: where
	 * Description: 设置where
	 * Return: Object
	 * @param String Date: 2012年12月19日14:59:04
	 */
	public function limit() {
		$limit = func_get_args ();
		if (count ( $limit ) == 2) {
			$this->limit = " LIMIT {$limit[0]},{$limit[1]}";
		} elseif (count ( $limit ) == 1) {
			$this->limit = ' LIMIT ' . $limit;
		}
		return $this;
	}
	
	/**
	 * FunctionName: query
	 * Description: 发送一条数据库语句
	 * Return: Array 多维数组 
	 * @param String Date: 2012年12月19日14:59:04 
	 */
	public function fquery($sql) {
		$this->sql = $sql;
		return $this->arraySql ();
	}
	public function rquery($sql) {
		$this->sql = $sql;
		return $this->executeSql ();
	}
	
	/**
	 * FunctionName: insert
	 * Description: 插入数据库 
	 * Return: Boolean 
	 * @param : Array Date: 2012年12月19日14:59:04 
	 */
	public function insert($array = null) {
		if ($array == null) {
			return false;
		}
		$str = '';
		foreach ( $array as $val ) {
			$str .= ($str == '') ? '"' . $val . '"' : ',"' . $val . '"';
		}
		$this->sql = "INSERT INTO {$this->tabName} (" . implode ( ',', array_keys ( $array ) ) . ") VALUES (" . $str . ")";
		
		return $this->insertSql ();
	}
	
	/**
	 * FunctionName: insert_s
	 * Description: 插入多条数据 
	 * Return: Boolean 
	 * @param 	: Array Date: 2012年12月19日14:59:04 
	 */
	function insert_s($arr_key = NULL, $arr_val = NULL) {
		if ($arr_key == null || $arr_val == NULL) {
			return false;
		}
		$str = '';
		foreach ( $arr_key as $val ) {
			$str .= ($str == '') ? '' . $val . '' : ',' . $val . '';
		}
		
		$string = '';
		$i = 0;
		if (count ( $arr_val ) % count ( $arr_key ) != 0) {
			return false;
		}
		$len = count ( $arr_val ) - 1;
		if (count ( $arr_key ) <= 1) {
			foreach ( $arr_val as $val ) {
				if ($string == '') {
					$string .= '("' . $val . '"';
				} else if ($len == $i) {
					$string .= '),("' . $val . '")';
				} else {
					$string .= '),("' . $val . '"';
				}
				$i ++;
			}
		} else {
			foreach ( $arr_val as $val ) {
				if (($i % count ( $arr_key )) == 0) {
					if ($string == '') {
						$string .= '("' . $val . '"';
					} else {
						$string .= '),("' . $val . '"';
					}
				} else {
					if ($len == $i) {
						$string .= ',"' . $val . '")';
					} else {
						$string .= ',"' . $val . '"';
					}
				}
				$i ++;
			}
		}
		$this->sql = "INSERT INTO {$this->tabName} (" . $str . ") VALUES " . $string;
		return $this->insertSql ();
	}
	
	/**
	 * FunctionName: delete
	 * Description: 删除动作 
	 * Return: Boolean 
	 * @param : Null Date: 2012年12月19日14:59:04 
	 */
	public function delete() {
		$this->sql = "DELETE FROM {$this->tabName}{$this->where}{$this->order}{$this->limit}";
		return $this->executeSql ();
	}
	
	/**
	 * FunctionName: delete
	 * Description: 查询动作 
	 * Return: Array多维数组 
	 * @param : Null Date: 2012年12月19日14:59:04 
	 */
	public function select() {
		if (empty ( $this->field )) {
			$this->sql = "SELECT " . implode ( ',', $this->fieldList ) . " FROM {$this->tabName}{$this->where}{$this->group}{$this->order}{$this->limit}";
		} else {
			$this->sql = "SELECT " . $this->field . " FROM {$this->tabName}{$this->where}{$this->group}{$this->order}{$this->limit}";
		}
		// echo $this->sql."<br/>";
		return $this->arraySql ();
	}
	
	/**
	 * FunctionName: total
	 * Description: 按指定的条件获取结果集中的记录数 
	 * Return: Array多维数组 
	 * @param : Null Date: 2012年12月19日14:59:04 
	 */
	public function total() {
		$count = 0;
		$this->sql = "SELECT count(*) as count FROM {$this->tabName}{$this->where}{$this->group}{$this->order}{$this->limit}";
		$result = $this->arraySql ();
		if (isset ( $result [0] ["count"] )) {
			$count = $result [0] ["count"];
		}
		return $count;
	}
	
	/**
	 * FunctionName: find
	 * Description: 查询动作 
	 * Return: Array一维数组 
	 * @param : Null Date: 2012年12月19日14:59:04 
	 */
	public function find() {
		if (empty ( $this->field )) {
			$this->sql = "SELECT " . implode ( ',', $this->fieldList ) . " FROM {$this->tabName}{$this->where}{$this->group}{$this->order}{$this->limit}";
		} else {
			$this->sql = "SELECT " . $this->field . " FROM {$this->tabName}{$this->where}{$this->group}{$this->order}{$this->limit}";
		}
		return $this->returnSql ();
	}
	
	/**
	 * FunctionName: r_count
	 * Description: 查询动作 
	 * Return: Array一维数组 
	 * @param : Null Date: 2012年12月19日14:59:04 
	 */
	public function r_count() {
		$this->sql = "SELECT COUNT(" . $this->fieldList [0] . ") AS count FROM {$this->tabName}{$this->where}";
		return $this->sumcount ();
	}
	
	/**
	 * FunctionName: update
	 * Description: 更新动作 
	 * Return: Interge 返回影响条数 
	 * @param : Array Date: 2012年12月19日14:59:04 
	 */
	public function update($array = null) {
		if ($array == null) {
			return false;
		}
		$val = '';
		if (is_array ( $array )) {
			foreach ( $array as $key => $val2 ) {
				$val .= ($val == '') ? $key . '="' . $val2 . '"' : ', ' . $key . '="' . $val2 . '"';
			}
		} else {
			$val = $array;
		}
		$this->sql = "UPDATE {$this->tabName} SET {$val}{$this->where}";
		
		return $this->updte_result ();
	}
	
	/**
	 * 返回错误信息
	 */
	public function getError(){
		return $this->error;
	}
	
	/**
	 * FunctionName: clearfield
	 * Description: 清空变量 
	 * Date: 2012年12月19日14:59:04
	 */
	public function clearfield() {
		$this->where = "";
		$this->order = "";
		$this->group = "";
		$this->limit = "";
		$this->field = "";
		$this->fieldList = "";
	}
}