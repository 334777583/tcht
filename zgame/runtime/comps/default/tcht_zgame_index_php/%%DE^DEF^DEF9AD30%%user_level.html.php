<?php /* Smarty version 2.6.18, created on 2014-08-08 00:47:45
         compiled from stickiness/user_level.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>等级分布</title>
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
							<td width="95%" class="tableleft">1、统计每个等级的人数</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="topinfo">
				<div>
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
					<!--<input type="text" class="input1" id="enddate" value="<?php echo $this->_tpl_vars['endday']; ?>
"/>-->
					<input type="button" value="查询" id="querybtn"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div>
				<table  class="mytable" cellspacing="0" align="center" id="dtable">
					<thead>
						<tr>
							<th>日期</th>
							<th>0-10级</th>
							<th>11-20级</th>
							<th>21-30级</th>
							<th>31-40级</th>
							<th>41-50级</th>
							<th>51-60级</th>
							<th>61-70级</th>
							<th>71-80级</th>
							<th>80级以上</th>
						</tr>
					</thead>
					<tbody id="dtatr_body">
					</tbody>
				</table>
			</div>
			
			<div style="float:right;margin-right:20px;display:none" id="pagehtml">
				<div class="pages">
					<a id="home_page" href="javascript:void(0)">首页</a>&nbsp;&nbsp;
					<a id="pre_page" href="javascript:void(0)">上一页</a>&nbsp;&nbsp;
					<a id="next_page" href="javascript:void(0)">下一页</a>&nbsp;&nbsp;
					<a id="last_page" href="javascript:void(0)">尾页</a>&nbsp;&nbsp;
					<span>第<span id="cur_page">1</span>/<span id="total_page">1</span>页&nbsp;&nbsp;</span>
					转到<input type="text" class="text" size="3"  id="page" value="1"/>
					<a id="go" class="go" href="javascript:void(0);"></a>页
				</div>
			</div>
			<div style="height:50px">&nbsp;</div>
			
			<div id="chart_div" style="width: 100%; height: 500px;display:none"></div>
		     <div style="width: 100%; height: 40px"></div>
			<div id="piediv" style="width: 100%; height: 500px; display:none; overflow: hidden; text-align: left; "></div>
			
			
			<div style="clear:both"></div>
		
			<div style="clear:both"></div>
		</div>
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
		var user_level = {
			INIT : function(){
				var self = this;
				//时间插件
				$("#startdate").datepicker();
				$("#enddate").datepicker();
				
				showTitle("游戏数据统计:等级分布统计");
				page.listen();	//分页事件绑定
				
				$("#querybtn").click(function(){
					self.showLV();
				})
				//self.showLV();
				
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
			
			//等级分布
			showLV : function() {
				var self = this;
				$.ajax({
					'type' : 'POST',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/userlevel/getLevel',
					dataType : 'json',
					data : {
						endday : $("#enddate").val(),
						ip : $("#sip").val()
					},
					beforeSend : function() {
						$("#dtatr_body").html("<tr><td colspan='13'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success : function (data) {						
						if(typeof(data.result) != 'undefined' && data.result != "") {
							var string = "<tr><td>"+data.result['Time']+"</td><td>"+data.result['OeTn']+
										"</td><td>"+data.result['OeTo']+"</td><td>"+data.result['ToTr']+
										"</td><td>"+data.result['TrFo']+"</td><td>"+data.result['FoFv']+
										"</td><td>"+data.result['FvSx']+"</td><td>"+data.result['SxSv']+
										"</td><td>"+data.result['SvEg']+"</td><td>"+data.result['EgMax']+"</td></tr>";
							$("#dtatr_body").html(string);	
							//柱图
							if(typeof(data.col_lev) != 'undefined' && data.col_lev !="") {
								var col_res = data.col_lev
								var col = [];		
								for(var i in col_res) {
									var col_item = {};
									col_item.Level = col_res[i]['Level']+"级";
									col_item.num = col_res[i]['count'];
									col.push(col_item);
								}
								$("#chart_div").show();
								self.showColumn(col,"chart_div");
							}
							//饼图
							if(typeof(data.pie_lev) != 'undefined' && data.pie_lev !="") {
								var pie_res = data.pie_lev
								var pie = [];
								for(var i in pie_res) {
									var pie_item = {};
									pie_item.country = pie_res[i]['lev_tit']+"级";
									pie_item.litres = pie_res[i]['lev_val'];
									pie.push(pie_item);
								}
								$("#piediv").show();
								self.showPie(pie,"piediv");
							}
							return true;
						}
						
						$("#pagehtml").hide();
						$("#chart_div").hide();
						$("#piediv").hide();
						$("#dtatr_body").html("<tr><td colspan='13'>没有数据！</td></tr>");
						
					},
					error : function () {
						$("#pagehtml").hide();
						$("#chart_div").hide();
						$("#piediv").hide();
						$("#dtatr_body").html("<tr><td colspan='13'>没有数据！</td></tr>");
					}
				})
			},
			//显示柱状图
			showColumn : function(data, div) {
				var chart = new AmCharts.AmSerialChart();
                chart.dataProvider = data;
                chart.categoryField = "Level";
                chart.startDuration = 1;

                // AXES
                // category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.labelRotation = 90;
                categoryAxis.gridPosition = "start";

                // value
                // in case you don't want to change default settings of value axis,
                // you don't need to create it, as one value axis is created automatically.

                // GRAPH
                var graph = new AmCharts.AmGraph();
                graph.valueField = "num";
                graph.balloonText = "[[category]]: [[value]]";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                chart.addGraph(graph);

                chart.write(div);
			},
			
			//显示饼状图
			showPie : function(data, div){
				// PIE pieChart
				var pieChart = new AmCharts.AmPieChart();
				pieChart.dataProvider = data;
				pieChart.titleField = "country";
				pieChart.valueField = "litres";
				//pieChart.labelRadius = 5;

				// LEGEND
				var legend = new AmCharts.AmLegend();
				legend.align = "circle";
				legend.position = "bottom";
				legend.marginRight = 500;
				legend.switchType = "Y";
				pieChart.addLegend(legend);

				// WRITE
				pieChart.write(div);
			}
		}
		
		$(document).ready(function(){
			user_level.INIT();
		})
	</script>
</body>
</html>