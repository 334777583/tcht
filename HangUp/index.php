<?php
require_once 'mysqli.php';
$config = array (
				"ip" => "127.0.0.1",
				"db" => "game_base",
				"user" => "tcht",
				"password" => "tcht_fuck",
				"port" => "3311"
);
//服务器列表
$obj = new DB($config['db'],$config['ip'],$config['user'],$config['password'],$config['port']);
$serverList = $obj->table('gamedb')->where("g_flag=1")->select();

$bb= '0123456789';

//账号
$accountList = array();
for ($i=1;$i<151;$i++){
	$tem = "AAAAA".$i."ABCDEF0123456789abcdf";
	$length = 32-strlen($tem);
	if ($length!=0){
		$tem = $tem.substr($bb, 0,$length);
	}
// 	echo strlen($tem);
// 	var_dump((0 == preg_match('/^[0-9a-fA-F]{32}$/', $tem)) ? false : true) ;exit;
	$accountList[] = $tem;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>挂机登陆</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<script src="jquery.js" type="text/javascript"></script>
<script src="jquery-ui.js" type="text/javascript"></script>
<link href="jquery-ui.css" rel="stylesheet" type="text/css">
<SCRIPT type="text/javascript">
$(document).ready(function(){
	$('a').click(function(){
		var serverId = $('#serverId').val();
		var openid = $(this).attr('account');
		var url = "login.php?serverId="+serverId+"&openid="+openid;
		window.open(url);
	});

	$("#checkserver").click(function(){
		$("#sform").dialog({
            height: 500,
            width: 700,
            buttons: {
                "确认": function(){
                    var item = $(':radio[name="db"]:checked').val();
                    $("#serverId").val(item);
                    $(this).dialog("close");
                },
                "关闭": function(){
                    $(this).dialog("close");
                }
            }
        });
    });
	
});
</SCRIPT>
<style type="text/css">
<!--body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #e6efc2;
	font-size: 12px;
}
a{
	color: #569715;
}
-->
</style>
</head>
<body>
	<TABLE align="center" cellspacing="2" cellspadding="2" border="1">
	<tr>
		<TH style="color: red;">选择服务器后直接点击账号登录</TH>
	</tr>
	<tr>
		<TH>服务器:
		<SELECT id="serverId">
			<?php 
			foreach ($serverList as $v){
				echo "<OPTION value=".$v['g_id'].">".$v['g_name']."</OPTION>";
			}
			?>
		</SELECT>
		<INPUT  type="button" id="checkserver" value="选择服务器"/>
		</TH>
	</tr>
	<tr align="left">
		<TH>
		<br/>
		<?php 
		foreach ($accountList as $k=>$v){
			echo '&nbsp;&nbsp;<a href="javascript:;" account="'.$v.'">'.$v.'</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
			if (($k+1)%5==0){
				echo "<br/><br/><hr style='color:#800080;'/><br/>";
			}
		}
		?>
		</TH>
	</tr>
	</TABLE>
	
				<!-- 服务器 -->
        <div id="sform" style="display:none">
            <div class="ajaxform">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
				<tr>
					<td>
					<?php 
					foreach ($serverList as $k=>$v){
						if (($k+1)%5==0){
							echo "<br/><br/>";
						}
						echo '<input type="radio" name="db" value="'.$v['g_id'].'" class="cbox" id="s'.$k.'"/>';
						echo '<label for="s'.$k.'">'.$v['g_name'].'</label>';
						echo '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
					}
					?>
					</td>
				</tr>
                </table>
            </div>
        </div>	
	
</body>
</html>

