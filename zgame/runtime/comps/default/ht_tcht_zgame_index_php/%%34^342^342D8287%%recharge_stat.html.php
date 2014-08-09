<?php /* Smarty version 2.6.18, created on 2014-07-31 11:30:07
         compiled from recharge/recharge_stat.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>充值统计</title>
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
							<td width="95%" class="tableleft">1、**********</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="topinfo">
				<input type="button" value="选择服务器" id ="sel">
					
			</div>
			<div class="topinfo">
				<div>
					<input type="radio" name="rtype" value="1" checked='checked'/><span>开服时间至</span><input type="text" class="input1" id="finshdate" value="<?php echo $this->_tpl_vars['finishDate']; ?>
"/>
					<input type="radio" name="rtype" value="2"/><span>选择区间</span>
					<span id="qujiang" style="display:none"><input type="text" class="input1" id="startdate" value="<?php echo $this->_tpl_vars['startDate']; ?>
"/>至<input type="text" class="input1" id="enddate" value="<?php echo $this->_tpl_vars['endDate']; ?>
"/></span>
					<input type="button" value="查询" id="querybtn" style="margin-left:20px"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div id="tabs-1" class="tabitem">
				<div>
					<table  class="mytable" cellspacing="0" align="center" id="dtable">
						<thead>
							<tr>
								<th>服务器</th>
								<th>开服日期</th>
								<th>充值天数</th>
								<th>充值人数</th>
								<th>充值金额</th>
								<th>开服首冲金额</th>
								<th>首充人数</th>
								<th>首日充值总金额</th>
								<th>日均充值</th>
								<th>ARPU</th>
							</tr>
						</thead>
						<tbody id="dtatr_body">
						</tbody>
					</table>
				</div>
				
			</div>
			
			<div style="clear:both"></div>
		</div>
		
	</div>
	<!--弹出覆盖层-->
	<div class="overlay" style="display:none">
		<table style="width:220px;height:100%;margin:0 auto;">
			<tr>
				<td style="text-align:center">
					<img src='<?php echo $this->_tpl_vars['res']; ?>
/images/ajax-loader.gif'/>
				</td>
			</tr>
		</table>
	</div>
	
	<!-- 服务器 -->
	<div id="dform"  style="display:none">
		<div class="ajaxform">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
				
				<?php $_from = $this->_tpl_vars['ipList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ip']):
?>
					<tr>
						<td>
						<input type="checkbox" name="db" value='<?php echo $this->_tpl_vars['ip']['s_id']; ?>
'  class="cbox"/><span><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</span>
						</td>
					</tr>
				<?php endforeach; endif; unset($_from); ?>
			</table>
		</div>
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
		var recharge_stat = {
			INIT : function(){
				var self = this;
				
				//时间插件
				$("#startdate").datepicker();
				$("#enddate").datepicker();
				$("#finshdate").datepicker();
				
				showTitle("充值数据分析:充值统计");
			
				$("#querybtn").click(function(){
					var value = $("input[name=rtype]:checked").val();
					var db = [];
					$('input[name=db]:checked').each(function() {
						db.push($(this).val());
					});
					if(value == '1') {
						if($("#finshdate").val() == "") {
							alert("请输入截止时间");
							return false;
						}
						self.show(db);
					} else if(value == '2') {
						if(validator("startdate", "enddate")) {
							self.show(db);
						}
					}
				});
				
				//区间与截止到开服时间切换
				$("input[name=rtype]").click(function() {
					var value = $("input[name=rtype]:checked").val();
					if(value == '1') {
						$("#finshdate").show();
						$("#qujiang").hide();
					} else if(value == '2') {
						$("#finshdate").hide();
						$("#qujiang").show();
					}
				});
				
				$("#sel").click(function(){
					
					$("#dform").dialog({
							height: 500,
							width: 700,
							buttons :{
								"确认": function(){
									var db = [];
									$('input[name=db]:checked').each(function() {
										db.push($(this).val());
									});
									self.show(db);
									$(this).dialog("close");
								},
								"关闭" : function(){
									$(this).dialog("close");
								}
							}
						})
				})
			},
			
			//table交叉样式
			 color_table : function(table) {
				$("#"+table+" tr:odd").css("background-color", "#edf2f7"); 
				$("#"+table+" tr:odd").css("background-color","#e0f0f0"); 
			 },
			 
			 show : function(db) {
				$.ajax({
					type : 'POST',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/rechargestat/getRecords',
					dataType : 'json',
					data : {
						type : $("input[name=rtype]:checked").val(),
						finshDate : $("#finshdate").val(),
						startDate : $('#startdate').val(),
						endDate :	$('#enddate').val(),
						db : db
					},
					beforeSend : function() {
						$("#dtatr_body").html("<tr><td colspan='11'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success : function (data) {
						var result = data.result;
						if(typeof(result) != 'undefined' && result.length > 0) {
							var tbody = "";
							for(var i in result) {
								tbody += "<tr>";
								tbody += "<td>" + result[i]["db"] + "</td>";
								tbody += "<td>" + result[i]["first"] + "</td>";
								tbody += "<td>" + result[i]["czts"] + "</td>";
								tbody += "<td>" + result[i]["czrs"] + "</td>";
								tbody += "<td>" + result[i]["czje"] + "</td>";
								tbody += "<td>" + result[i]["kfsc"] + "</td>";
								tbody += "<td>" + result[i]["csrs"] + "</td>";
								tbody += "<td>" + result[i]["crczje"] + "</td>";
								tbody += "<td>" + result[i]["rjcz"] + "</td>";
								tbody += "<td>" + result[i]["arpu"] + "</td>";
								tbody += "</tr>";
							}
							$("#dtatr_body").html(tbody);
						}else {
							$("#dtatr_body").html("<tr><td colspan='11'>没有数据！</td></tr>");
						}
					},
					error : function () {
						$("#dtatr_body").html("<tr><td colspan='11'>没有数据！</td></tr>");
					}
				})
			 }
		}
		
		$(document).ready(function(){
			recharge_stat.INIT();
			recharge_stat.show();
		})
		
	</script>
</body>
</html>