<?php /* Smarty version 2.6.18, created on 2014-08-09 10:57:18
         compiled from stickiness/user_pay.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>用户付费分析</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/skin.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/jquery-ui.css" rel="stylesheet" type="text/css">
	<style type="text/css">
	<!--
	body {
		margin-left: 0px;
		margin-top: 0px;
		margin-right: 0px;
		margin-bottom: 0px;
		background-color: #EEF2FB;
		font-size: 12px;
	}
	-->
	</style>
</head>
<body>
	<div>
		<div>
			<div>
				<table class="explain">
					<thead>
					</thead>
					<tbody style="font-family:Mingliu">
						<tr>
							<td width="5%"  class="tableleft"><b>说明：</b></td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1、**************；</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="topinfo">
				<div>
					截止至：<input type="text" class="input1" id="enddate" value="<?php echo $this->_tpl_vars['endDate']; ?>
"/>
					<input type="button" value="查询" id="querybtn" style="margin-left:20px"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div>
				<table  class="mytable" cellspacing="0" align="center" id="dtable">
					<thead>
						<tr>
							<th>服务器</th>
							<th>开服日期</th>
							<th>创建角色</th>
							<th>付费用户</th>
							<th>活跃付费用户数</th>
							<th>多次付费用户</th>
							<th>注册用户付费率</th>
							<th>平均在线付费率</th>
							<th>活跃用户付费率</th>
							<th>日ARPU</th>
							<th>月ARPU</th>
						</tr>
					</thead>
					<tbody id="dtatr_body">
					</tbody>
				</table>
			</div>
		
			<div style="clear:both"></div>
		</div>
		
		<div style="height:50px">&nbsp;</div>
		
	</div>
	
	
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery-ui.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/amcharts.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script> 	
	<script type="text/javascript">
		$(document).ready(function(){
			//时间插件
			$("#enddate").datepicker();
			showTitle("玩家数据统计:用户付费分析");
				
			$("#querybtn").click(function(){
				show();
			});
		});
		
		function show(){
            $.ajax({
                type: 'POST',
                url: '<?php echo $this->_tpl_vars['logicApp']; ?>
/userpay/getData',
                dataType: 'json',
                data: {
                    endDate:$('#enddate').val()
                },
                beforeSend: function(){
                    $("#dtatr_body").html("<tr><td colspan='11'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
                },
                success: function(data){
                    if (typeof(data) != 'undefined' && data.length > 0) {
                        var tbody = "";
                        for (var i in data) {
                            tbody += "<tr>";
                            tbody += "<td>" + data[i]["serverid"] + "</td>";
							tbody += "<td>" + data[i]["date"] + "</td>";
							tbody += "<td>" + data[i]["roleNum"] + "</td>";
							tbody += "<td>" + data[i]["payNum"] + "</td>";
							tbody += "<td>" + data[i]["activeNum"] + "</td>";
							tbody += "<td>" + data[i]["payMore"] + "</td>";
							tbody += "<td>" + data[i]["payPer"] + "</td>";
							tbody += "<td>" + data[i]["onlinePer"] + "</td>";
							tbody += "<td>" + data[i]["avtivePayPer"] + "</td>";
							tbody += "<td>" + data[i]["dPer"] + "</td>";
							tbody += "<td>" + data[i]["mPer"] + "</td>";
                            tbody += "</tr>";
                        }
                        $("#dtatr_body").html(tbody);
                    }
                    else {
                        $("#dtatr_body").html("<tr><td colspan='11'>没有数据！</td></tr>");
                    }
                },
                error: function(){
                    $("#dtatr_body").html("<tr><td colspan='11'>没有数据！</td></tr>");
                }
            })
		}
	</script>
</body>
</html>