<?php /* Smarty version 2.6.18, created on 2014-08-08 00:47:30
         compiled from stickiness/chongzhilevel.html */ ?>
﻿<!DOCTYPE html>
<html>
<head>
	<title>充值等级分布</title>
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
				<div>
					<label>
						<span>服务器:</span>
						<select id="sip">
							<?php $_from = $this->_tpl_vars['ipList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ip']):
?>
								<option value="<?php echo $this->_tpl_vars['ip']['s_id']; ?>
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
							<?php endforeach; endif; unset($_from); ?>
							<option value="0" >全部</option>
						</select>
					</label>
					<label>
						<span>充值次数:</span>
						<select id="times">
							<option value="1">第1次</option>
							<option value="2">第2次</option>
							<option value="3">第3次</option>
							<option value="4">第4次</option>
							<option value="5">第5次</option>
						</select>
					</label>
					<span>日期:</span>
					<input type="text" class="input1" id="startdate" value="<?php echo $this->_tpl_vars['startdate']; ?>
"/>
					至<input type="text" class="input1" id="enddate" value="<?php echo $this->_tpl_vars['startdate']; ?>
"/>
					<input type="button" value="查询" id="jishi" style="margin-left:20px"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div id="tabs-1" class="tabitem">	
				<div>
					<table  class="mytable" cellspacing="0" align="center" id="dtable">
						<thead>
							<tr>
								<th>等级</th>
								<th>日期</th>
								<th>充值人数</th>
								<th>充值总额</th>
							</tr>
						</thead>
						<tbody id="dtatr_body">
						</tbody>
					</table>
					
					<div id="pagehtml" style="float:right;margin-right:20px"></div>
					<div id="example_length" class="dataTables_length"  style="display:none">
						<label>每页显示
							<select id="menu" name="example_length" size="1" aria-controls="example">
							<option value="10">10</option>
							<option value="25">25</option>
							<option value="50" selected="selected">50</option>
							<option value="100">100</option>
							</select> 条记录
						</label>
					</div>
					
				</div>
				
			</div>
			
			<div style="clear:both"></div>
		</div>
		
		<div style="height:50px">&nbsp;</div>
		
	</div>
	<!-- 服务器 -->
	<div id="dform"  style="display:none">
		<div class="ajaxform">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
				
				<?php $_from = $this->_tpl_vars['ipList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['ip']):
?>
					<?php if ($this->_tpl_vars['key']%5 == '0'): ?>
						<br/><br/>
					<?php endif; ?>
						<input type="radio" name="db" value='<?php echo $this->_tpl_vars['ip']['s_id']; ?>
'  class="cbox"/><span><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
		var recharge_query = {
			INIT : function(){
				var self = this;
				
				//时间插件
				$("#startdate").datepicker();
				$("#enddate").datepicker();
				
				showTitle("充值数据分析:充值等级分布");
				
				$("#jishi").click(function(){
					self.show(1);
				})
				
				$("#sip").change(function() {
					if($("#sip").val() == 0){
						$("#dform").dialog({
							height: 500,
							width: 700,
							buttons :{
								"确认": function(){
									var item = $(':radio[name="db"]:checked').val();
									$("#sip").val(item);
									$(this).dialog("close");
								},
								"关闭" : function(){
									$(this).dialog("close");
								}
							}
						})
					}
				})
				
			},
			
			show : function(page) {
				$.ajax({
					type : 'POST',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/chongzhilevel/getData',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $('#startdate').val(),
						endDate :	$('#enddate').val(),
						times : $('#times').val(),
						pageSize : $("#menu").val(),
						curPage : page
					},
					beforeSend : function() {
						$("#dtatr_body").html("<tr><td colspan='4'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success : function (data) {
						
						$("#example_length").show();
						var result = data.result;
						if(typeof(result) != 'undefined' && result.length > 0) {
							var tbody = "";
							for(var i in result) {
								tbody += "<tr>";
								tbody += "<td>" + result[i]["c_level"] + "</td>";
								tbody += "<td>" + result[i]["date"] + "</td>";
								tbody += "<td>" + result[i]["num"] + "</td>";
								tbody += "<td>" + result[i]["RMB"] + "</td>";
								tbody += "</tr>";
							}
							$("#dtatr_body").html(tbody);
							$("#pagehtml").html(data.pageHtml);		//分页
							$('#orderKey').val('');
						}else {
							$("#example_length").hide();
							$("#pagehtml").html("");
							$("#dtatr_body").html("<tr><td colspan='4'>没有数据！</td></tr>");
						}
					},
					error : function () {
						$("#example_length").hide();
						$("#pagehtml").html("");
						$("#dtatr_body").html("<tr><td colspan='4'>没有数据！</td></tr>");
					}
				})
			
			
			}
		}
		
		
		$(document).ready(function(){
			recharge_query.INIT();
			recharge_query.show(1);
		})
		
		
		//跳到相应页面 
		var go = function(){
			var pagenum = $("#page").val();
			if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
				alert('请输入一个正整数！');
				$("#page").val(1);
			}else{
				//recharge_query.show(pagenum);
				formAjax(pagenum);
			}
		}
		
		//分页ajax函数
		var formAjax = function(page){
			$ty = $("#s_type").val();
			recharge_query.show(page,$ty);
		}
	</script>
</body>
</html>