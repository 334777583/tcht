<?php
/**
 * FileName		: statistical.class.php
 * Description	: 全服用户活跃统计
 * Author	    : zwy
 * Date			: 2014-10-8
 * Version		: 1.00
 */
class statistical{
    /**
     * 登录用户信息
     */
    private $user;

    /**
     * 初始化数据
     */
    public function __construct(){
        if(!$this->user = autoCheckLogin::isLogin()){
            echo 'not available!';
            exit();
        }else{
            if(!in_array('00100800', $this->user['code'])){
                echo 'not available!';
                exit();
            }
        }
    }

    /**
     * 获取数据
     */
    public function getData(){
        $startDate = get_var_value('startDate');
        $endDate = get_var_value('endDate');

        $startDate = $startDate.' 00:00:00';
        $endDate = $endDate.' 23:59:59';

        $startTime = strtotime($startDate);
        $endTime = strtotime($endDate);

        //日活跃用户数	当日登陆的openID总数量	（若一个玩家在多个服登陆，算1个）
        $list = array();
        $serverList = D('game_base')->table('gamedb')->where('g_flag = 1')->select();
        foreach($serverList as $k=>$v){
            $sql1 = "select account,time as date from type_jump where time between '{$startDate}' and '{$endDate}'";
            $account = D('game'.$v['g_id'])->fquery($sql1);
            if(empty($account)){
                continue;
            }
            foreach($account as $v1){
                if(!isset($list[$v1['date']])){
                    $list[$v1['date']] = array();
                }
                $list[$v1['date']][] = $v1['account'];
            }
        }
        if(empty($list)){
            echo 1;exit;
        }

        //每日充值统计
        $Sql  = "SELECT sum(c_price*c_num) as money,left(c_time,10) as date from chongzhi WHERE c_ts between $startTime and $endTime and c_state=2 group by date";
        $chongzhi = D('chongzhi')->fquery($Sql);
        if(!empty($chongzhi)){
            $pay = array();
            foreach($chongzhi as $k=>$v){
                $pay[$v['date']] = $v['money'];
            }
        }

        global $account_list;
        $AccountListObj = F($account_list['db'], $account_list['ip'], $account_list['user'], $account_list['password'], $account_list['port']);

        //新用户数
        $where = "first_create_time between $startTime and $endTime";
        $newPlayer = $AccountListObj->table("account_list")->where($where)->select();
        if(!empty($newPlayer)){
            $newPlayerDate = array();
            foreach ($newPlayer as $k=>$v){
                $date = date('Y-m-d',$v['first_create_time']);
                if(!isset($newPlayerDate[$date])){
                    $newPlayerDate[$date] = array();
                }
                $newPlayerDate[$date][] = $v['account'];
            }
        }

        $result = array();
        foreach($list as $k=>$v){
            $tem = array();
            $tem['date'] = $k;
            $tem['activeuser'] = count(array_unique($v));
            $tem['newuser'] = empty($newPlayerDate[$k])?0:count($newPlayerDate[$k]);
            $tem['pay'] = empty($pay[$k])?0:$pay[$k];
            $tem['arpu'] = round($tem['pay']/$tem['activeuser'],2);

//            $s = array();
//            foreach($v as $t){
//                if(!in_array($t,$newPlayerDate[$k])){
//                    $s[] = $t;
//                }
//            }
//            $tem['olduser'] = count(array_unique($s));
//            if($k=='2014-08-03'){
//                echo json_encode(array($v,$newPlayerDate[$k]));exit;
//            }
            $tem['olduser'] = count(array_unique(array_diff($v,$newPlayerDate[$k])));

            $result[] = $tem;
        }
        echo json_encode($result);exit;
    }
}

 