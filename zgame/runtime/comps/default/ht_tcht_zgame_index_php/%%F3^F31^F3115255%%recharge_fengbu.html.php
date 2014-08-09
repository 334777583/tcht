<?php /* Smarty version 2.6.18, created on 2014-07-31 16:51:56
         compiled from recharge/recharge_fengbu.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>充值分布</title>
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
						</select>
					</label>
					<input type="text" class="input1" id="startdate" value="<?php echo $this->_tpl_vars['startDate']; ?>
"/>至<input type="text" class="input1" id="enddate" value="<?php echo $this->_tpl_vars['endDate']; ?>
"/>
					<input type="button" value="查询" id="querybtn" style="margin-left:20px"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div id="tabs-1" class="tabitem">	
				<table width="50%" cellpadding="0" cellspacing="0" style="float:left">
					<tr>
						<td align='left'>
							<table width="98%" cellpadding="0" cellspacing="0" class='table_blue2'>
								<tr>
									<td>
										<div id="leftdiv" style="width: 100%; height: 500px;"></div>
									</td>
								</tr>
							</table>
								
							<table class="mytable" style="width:98%;margin-top:30px">
								<thead>
									<tr>
										<th>充值大户等级分布</th>
										<th></th>
										<th></th>
										<th></th>
									</tr>
									<tr>
										<th>充值大户等级</th>
										<th>充值大户人数</th>
										<th>充值次数</th>
										<th>充值金额</th>
									</tr>
								</thead>
								<tbody id="czdh_body">
								</tbody>
							</table>
						</td>
					</tr>	
				</table>
				
				<table width="50%" cellpadding="0" cellspacing="0" style="float:right">
					<tr>
						<td align='right'>
							<table width="98%" cellpadding="0" cellspacing="0" class='table_blue2'>
								<tr>
									<td>
										<div id="rightdiv" style="width: 100%; height: 500px;"></div>
									</td>
								</tr>
							</table>
							
							<table class="mytable" style="width:98%;margin-top:30px">
								<thead>
									<tr>
										<th>充值用户等级分布</th>
										<th></th>
										<th></th>
										<th></th>
									</tr>
									<tr>
										<th>充值用户等级</th>
										<th>充值用户人数</th>
										<th>充值次数</th>
										<th>充值金额</th>
									</tr>
								</thead>
								<tbody id="dtatr_body">
								</tbody>
							</table>
						</td>
					</tr>
				</table>
			</div>
			
			<div style="clear:both"></div>
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
		var recharge_fengbu = {
			INIT : function(){
				var self = this;
				
				//时间插件
				$("#startdate").datepicker();
				$("#enddate").datepicker();
				
				showTitle("充值相关查询:充值分布");
				
				$("#querybtn").click(function(){
					if( validator("startdate", "enddate") ){
						/*var data = [
						{
							country: "0~10级",
							litres: 123
						}, {
							country: "11~20级",
							litres: 564
						}, {
							country: "21~30级",
							litres: 1251
						}, {
							country: "31~40级",
							litres: 564
						}, {
							country: "41~50级",
							litres: 5465
						}, {
							country: "51~60级",
							litres: 4654
						},{
							country: "61~70级",
							litres: 4654
						},{
							country: "71~80级",
							litres: 4654
						},{
							country: "81~90级",
							litres: 4654
						},{
							country: "91~100级",
							litres: 4654
						}
						];
						
						self.showPie(data, "leftdiv");
						self.showPie(data, "rightdiv");*/
					}
				})
			},
			
			show : function() {
				$.ajax({
					type : 'POST',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/rechargefengbu/getRecords',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $('#startdate').val(),
						endDate :	$('#enddate').val(),
					},
					beforeSend : function() {
						$("#dtatr_body").html("<tr><td colspan='9'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success : function (data) {
						var result = data.result;
						if(typeof(result) != 'undefined' && result.length > 0) {
							var tbody = "";
							for(var i in result) {
								tbody += "<tr>";
								tbody += "<td>" + result[i]["p_id"] + "</td>";
								tbody += "<td>" + result[i]["p_order"] + "</td>";
								tbody += "<td>" + result[i]["p_acc"] + "</td>";
								tbody += "<td>" + result[i]["p_playid"] + "</td>";
								tbody += "<td>" + '暂无' + "</td>";
								tbody += "<td>" + result[i]["p_creatdate"] + "</td>";
								tbody += "<td>" + result[i]["p_money"] * data.rate + "</td>";
								tbody += "<td>" + result[i]["p_money"] + "</td>";
								tbody += "<td>" + result[i]["p_pt"] + "</td>";
								tbody += "</tr>";
							}
							$("#dtatr_body").html(tbody);
						}else {
							$("#dtatr_body").html("<tr><td colspan='9'>没有数据！</td></tr>");
						}
					},
					error : function () {
						$("#dtatr_body").html("<tr><td colspan='9'>没有数据！</td></tr>");
					}
				})
			
			
			},
			
			//显示饼状图
			showPie : function(data, div){
				// PIE pieChart
				var pieChart = new AmCharts.AmPieChart();
				pieChart.dataProvider = data;
				pieChart.titleField = "country";
				pieChart.valueField = "litres";

				// LEGEND
				var legend = new AmCharts.AmLegend();
				legend.align = "circle";
				legend.position = "right";
				legend.marginRight = 500;
				legend.switchType = "y";
				pieChart.addLegend(legend);

				// WRITE
				pieChart.write(div);
			}
			
		}
		
		
		$(document).ready(function(){
			recharge_fengbu.INIT();
		})
	</script>
</body>
</html>