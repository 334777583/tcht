<?php /* Smarty version 2.6.18, created on 2014-08-02 11:46:10
         compiled from money/wing.html */ ?>
﻿<!DOCTYPE html>
<html>
<head>
	<title>元宝消费比例</title>
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
							<td width="95%" class="tableleft">**********</td>
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
					<span>日期:</span>
					<input type="text" class="input1" id="startdate" value="<?php echo $this->_tpl_vars['startdate']; ?>
"/>
					至<input type="text" class="input1" id="enddate" value="<?php echo $this->_tpl_vars['startdate']; ?>
"/>
					<input type="button" value="查询" id="history" style="margin-left:20px"/>
				</div>
			</div>
			<div style="clear:both"></div>
			
		</div>
		
		<div  style="border:1px solid #DDDBF2" >
				<div id="piediv" style="width: 75%; height: 400px;"></div>
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
/js/function.js" type="text/javascript"></script> 	
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/amcharts.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            //时间插件
            $("#startdate").datepicker();
            $("#enddate").datepicker();
            
            showTitle("货币相关查询:元宝消费比例");
            
            $("#sip").change(function(){
                if ($("#sip").val() == 0) {
                    $("#dform").dialog({
                        height: 500,
                        width: 700,
                        buttons: {
                            "确认": function(){
                                var item = $(':radio[name="db"]:checked').val();
                                $("#sip").val(item);
                                $(this).dialog("close");
                            },
                            "关闭": function(){
                                $(this).dialog("close");
                            }
                        }
                    })
                }
            });
            
            $('#history').click(function(){
				var startdate = $('#startdate').val();
				var enddate= $("#enddate").val() ;
				if(enddate<startdate){
					alert('结束时间必须大于开始时间');
				}
            	$.ajax({
					type: 'POST',
					url: '<?php echo $this->_tpl_vars['logicApp']; ?>
/wing/getWingData',
					dataType: 'json',
					data: {
						ip: $("#sip").val(),
						startdate: startdate,
						enddate:enddate
					},
					beforeSend: function(){
						$("#piediv").html("<img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/>");
					},
					success: function(data){
						if(data!=1){
							var pieChartData = [{
							country: "商城消费",
							litres: data['shop']
						}, {
							country: "神秘商店",
							litres: data['mystery_shop']
						}, {
							country: "聚划算",
							litres: data['bargain']
						}, {
							country: "摸金",
							litres:data['touch_of_gold']
						}, {
							country: "诸葛钱庄",
							litres: data['bank']
						}];
						// PIE pieChart
						var pieChart = new AmCharts.AmPieChart();
						pieChart.dataProvider = pieChartData;
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
						pieChart.write("piediv");
						}else {
							$("#piediv").html("没有数据！");
						}
					},
					error: function(){
						$("#piediv").html("没有数据！");
					}
				});
            });
        });
    </script>
</body>
</html>