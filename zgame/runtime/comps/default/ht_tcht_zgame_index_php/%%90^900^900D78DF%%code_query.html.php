<?php /* Smarty version 2.6.18, created on 2014-07-31 16:56:48
         compiled from gmtools/code_query.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>激活码查询</title>
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
			<div id="user-tabs"  style="margin-top:20px;">
				<span id="1"  class="user-gray">生成</span>
				<span id="2">查询</span>
				<hr/>
			</div>

			<div>
				<table class="explain">
					<thead>
					</thead>
					<tbody style="font-family:Mingliu">
						<tr>
							<td width="5%"  class="tableleft"><b>说明：</b></td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1、输入激活码查看激活码的使用状态;</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">2、输入角色ID查看该角色所使用过的激活码详情；</td>
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
					<!--<label for='codeStatus' style="margin-left:5px">激活状态:</label>
					
					<select id="cstatus">
						<option value="">全部</option>
						<option value="0">未使用</option>
						<option value="1">已使用</option>
					</select>-->
					
					<label for='codeType' style="margin-left:5px">激活类型:</label>
					<select id="ctype">
						<option value="">全部</option>
						<option value="6">不限类型</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="7">6</option>
						<option value="8">7</option>
						<option value="9">8</option>
						<option value="10">9</option>
						<option value="11">10</option>
						<option value="12">11</option>
						<option value="13">12</option>
						<option value="14">13</option>
						<option value="15">14</option>
						<option value="16">15</option>
						<option value="17">16</option>
						<option value="18">17</option>
						<option value="19">18</option>
						<option value="20">19</option>
						<option value="21">20</option>
						<option value="22">21</option>
						<option value="23">22</option>
						<option value="24">23</option>
						<option value="25">24</option>
						<option value="26">25</option>
						<option value="27">26</option>
						<option value="28">27</option>
						<option value="29">28</option>
						<option value="30">29</option>
						<option value="31">30</option>
						<option value="32">31</option>
						<option value="33">32</option>
						<option value="34">33</option>
						<option value="35">34</option>
						<option value="36">35</option>
						<option value="37">36</option>
						<option value="38">37</option>
						<option value="39">38</option>
						<option value="40">39</option>
						<option value="41">40</option>
						<option value="42">41</option>
						<option value="43">42</option>
						<option value="44">43</option>
						<option value="45">44</option>
						<option value="46">45</option>
						<option value="47">46</option>
						<option value="48">47</option>
						<option value="49">48</option>
						<option value="50">49</option>
						<option value="51">50</option>
						<option value="52">51</option>
						<option value="53">52</option>
						<option value="54">53</option>
						<option value="55">54</option>
						<option value="56">55</option>
						<option value="57">56</option>
						<option value="58">57</option>
						<option value="59">58</option>
						<option value="60">59</option>
						<option value="61">60</option>
						<option value="62">61</option>
						<option value="63">62</option>
						<option value="64">63</option>
						<option value="65">64</option>
						<option value="66">65</option>
						<option value="67">66</option>
						<option value="68">67</option>
						<option value="69">68</option>
						<option value="70">69</option>
						<option value="71">70</option>
						<option value="72">71</option>
						<option value="73">72</option>
						<option value="74">73</option>
						<option value="75">74</option>
						<option value="76">75</option>
						<option value="77">76</option>
						<option value="78">77</option>
						<option value="79">78</option>
						<option value="80">79</option>
						<option value="81">80</option>
						<option value="82">81</option>
						<option value="83">82</option>
						<option value="84">83</option>
						<option value="85">84</option>
						<option value="86">85</option>
						<option value="87">86</option>
						<option value="88">87</option>
						<option value="89">88</option>
						<option value="90">89</option>
						<option value="91">90</option>
						<option value="92">91</option>
						<option value="93">92</option>
						<option value="94">93</option>
						<option value="95">94</option>
						<option value="96">95</option>
						<option value="97">96</option>
						<option value="98">97</option>
						<option value="99">98</option>
						<option value="100">99</option>
					</select>
					<label for='codeText' style="margin-left:5px">激活码:</label><input type='text' class='input1' size='12' id='codeText'/>
					<!--
					<span style="margin-left:5px">角色ID:</span>
					<input type="text" class="input1" id="roleId" size="6"/>
					-->
					<span style="margin-left:5px">过期时间：</span>
					<!--<input type="text" class="input1" id="startdate" value="<?php echo $this->_tpl_vars['startDate']; ?>
"/>至--><input type="text" class="input1" id="enddate" value="<?php echo $this->_tpl_vars['endDate']; ?>
"/>
					<input type="button" value="查询" id="querybtn" style="margin-left:20px"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div id="tabs-1" class="tabitem">
				
				<div>
					<table  class="mytable" cellspacing="0" align="center" id="dtable">
						<thead>
							<tr>
								<th>激活码</th>
								<th>激活码类型</th>
								<!--<th>激活码详情</th>-->
								<th>服务器</th>
								<!--
								<th>激活码状态</th>
								<th>限制角色</th>
								<th>使用角色</th>
								<th>角色等级</th>
								<th>生成时间</th>
								-->
								<th>使用时限</th>
								<th>使用情况</th>
								<!--<th>使用时间</th>-->
							</tr>
						</thead>
						<tbody id="codeBody">
						</tbody>
					</table>
					<div class="exportbtn" style="display:none" id="export_div">
						<input type="button" value="导出Excel" id="exportbtn"/>
						<!--<input type="button" value="导出Txt" id="txtbtn"/>-->
					</div>
					<div id="pagehtml" style="float:right;margin-right:20px"></div>
					<div id="example_length" class="dataTables_length" style="display:none">
						<label>每页显示
							<select id="menu" name="example_length" size="1" aria-controls="example">
							<option value="10">10</option>
							<option value="25" selected="selected">25</option>
							<option value="50">50</option>
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
		var code_query = {
			INIT : function(){
				var self = this;
				
				//时间插件
				$("#startdate").datepicker();
				$("#enddate").datepicker();
				
				showTitle("GM工具:新手卡");
				
				$("#querybtn").click(function(){
					//if( check_date("startdate", "enddate") ){
						self.show_code(1);
					//}
				})
				
				//切换标签
				$("#user-tabs span").click(function(){
					window.location = "<?php echo $this->_tpl_vars['app']; ?>
/code/show/pageId/"+this.id;
				})
				
				//导出excel
				$("#exportbtn").click(function(){
					var ip = $("#sip").val();
					window.location.href = "<?php echo $this->_tpl_vars['logicApp']; ?>
/code/excel/ip/"+ip;
				});
				
				//导出txt
				$("#txtbtn").click(function(){
					var ip = $("#sip").val();
					var code = $("#codeText").val();
					var roleText = $("#roleId").val();
					var startDate = $("#startdate").val();
					var	endDate = $("#enddate").val();
					var cstatus = $("#cstatus").val();
					var ctype = $("#ctype").val();
					var s = '';
					if(code != '') {
						s += "/code/" + code;	
					}
					if(roleText != '') {
						s += '/roleText/' + roleText;
					}
					if(startDate != '') {
						s += '/startDate/' + startDate;
					}
					if(endDate != '') {
						s += '/endDate/' + endDate;
					}
					//if(cstatus != '') {
					//	s += '/cstatus/' + cstatus;
					//}
					if(ctype != '') {
						s += '/ctype/' + ctype;	
					}
					window.location.href = "<?php echo $this->_tpl_vars['logicApp']; ?>
/code/writeTxt/ip/"+ip+s;
				})
				
				self.show_code(1);
				
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
			
			//table交叉样式
			color_table : function(table) {
				$("#"+table+" tr:odd").css("background-color", "#edf2f7"); 
				$("#"+table+" tr:odd").css("background-color","#e0f0f0"); 
			},
			
			//查询激活码
			show_code : function(page){
				var self = this;
				$.ajax({
					type:"GET",
					dataType:"json",
					url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/code/getCode",
					data:
					{
						ip : $("#sip").val(),
						code : $("#codeText").val(),
						roleText : $("#roleId").val(),
						pageSize : $("#menu").val(),
						curPage : page,
						//cstatus : $("#cstatus").val(),
						ctype : $("#ctype").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val()
					},
					cache : true,
					beforeSend:function(){
						$("#codeBody").html("<tr><td colspan='10'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success:function(data){
						var list = [];
						$("#pagehtml").html("");//清除分页 
						if(typeof(data.list) != 'undefined'){
							list = data.list;
						}
						if(list.length > 0 ){
							$("#export_div").show();
							$("#example_length").show();//显示每页
							var tbody = "";
							for(var i in list){
								switch(parseInt(list[i]["type"])){
									case 1 : list[i]["type"] = "1";break;
									case 2 : list[i]["type"] = "2";break;
									case 3 : list[i]["type"] = "3";break;
									case 4 : list[i]["type"] = "4";break;
									case 5 : list[i]["type"] = "5";break;
									case 7 : list[i]["type"] = "6";break;
									case 8 : list[i]["type"] = "7";break;
									case 9 : list[i]["type"] = "8";break;
									case 10 : list[i]["type"] = "9";break;
									case 11 : list[i]["type"] = "10";break;
									case 12 : list[i]["type"] = "11";break;
									case 13 : list[i]["type"] = "12";break;
									case 14 : list[i]["type"] = "13";break;
									case 15 : list[i]["type"] = "14";break;
									case 16 : list[i]["type"] = "15";break;
									case 17 : list[i]["type"] = "16";break;
									case 18 : list[i]["type"] = "17";break;
									case 19 : list[i]["type"] = "18";break;
									case 20 : list[i]["type"] = "19";break;
									case 21 : list[i]["type"] = "20";break;
									case 22 : list[i]["type"] = "21";break;
									case 23 : list[i]["type"] = "22";break;
									case 24 : list[i]["type"] = "23";break;
									case 25 : list[i]["type"] = "24";break;
									case 26 : list[i]["type"] = "25";break;
									case 27 : list[i]["type"] = "26";break;
									case 28 : list[i]["type"] = "27";break;
									case 29 : list[i]["type"] = "28";break;
									case 30 : list[i]["type"] = "29";break;
									case 31 : list[i]["type"] = "30";break;
									case 32 : list[i]["type"] = "31";break;
									case 33 : list[i]["type"] = "32";break;
									case 34 : list[i]["type"] = "33";break;
									case 35 : list[i]["type"] = "34";break;
									case 36 : list[i]["type"] = "35";break;
									case 37 : list[i]["type"] = "36";break;
									case 38 : list[i]["type"] = "37";break;
									case 39 : list[i]["type"] = "38";break;
									case 40 : list[i]["type"] = "39";break;
									case 41 : list[i]["type"] = "40";break;
									case 42 : list[i]["type"] = "41";break;
									case 43 : list[i]["type"] = "42";break;
									case 44 : list[i]["type"] = "43";break;
									case 45 : list[i]["type"] = "44";break;
									case 46 : list[i]["type"] = "45";break;
									case 47 : list[i]["type"] = "46";break;
									case 48 : list[i]["type"] = "47";break;
									case 49 : list[i]["type"] = "48";break;
									case 50 : list[i]["type"] = "49";break;
									case 51 : list[i]["type"] = "50";break;
									case 52 : list[i]["type"] = "51";break;
									case 53 : list[i]["type"] = "52";break;
									case 54 : list[i]["type"] = "53";break;
									case 55 : list[i]["type"] = "54";break;
									case 56 : list[i]["type"] = "55";break;
									case 57 : list[i]["type"] = "56";break;
									case 58 : list[i]["type"] = "57";break;
									case 59 : list[i]["type"] = "58";break;
									case 60 : list[i]["type"] = "59";break;
									case 61 : list[i]["type"] = "60";break;
									case 62 : list[i]["type"] = "61";break;
									case 63 : list[i]["type"] = "62";break;
									case 64 : list[i]["type"] = "63";break;
									case 65 : list[i]["type"] = "64";break;
									case 66 : list[i]["type"] = "65";break;
									case 67 : list[i]["type"] = "66";break;
									case 68 : list[i]["type"] = "67";break;
									case 69 : list[i]["type"] = "68";break;
									case 70 : list[i]["type"] = "69";break;
									case 71 : list[i]["type"] = "70";break;
									case 72 : list[i]["type"] = "71";break;
									case 73 : list[i]["type"] = "72";break;
									case 74 : list[i]["type"] = "73";break;
									case 75 : list[i]["type"] = "74";break;
									case 76 : list[i]["type"] = "75";break;
									case 77 : list[i]["type"] = "76";break;
									case 78 : list[i]["type"] = "77";break;
									case 79 : list[i]["type"] = "78";break;
									case 80 : list[i]["type"] = "79";break;
									case 81 : list[i]["type"] = "80";break;
									case 82 : list[i]["type"] = "81";break;
									case 83 : list[i]["type"] = "82";break;
									case 84 : list[i]["type"] = "83";break;
									case 85 : list[i]["type"] = "84";break;
									case 86 : list[i]["type"] = "85";break;
									case 87 : list[i]["type"] = "86";break;
									case 88 : list[i]["type"] = "87";break;
									case 89 : list[i]["type"] = "88";break;
									case 90 : list[i]["type"] = "89";break;
									case 91 : list[i]["type"] = "90";break;
									case 92 : list[i]["type"] = "91";break;
									case 93 : list[i]["type"] = "92";break;
									case 94 : list[i]["type"] = "93";break;
									case 95 : list[i]["type"] = "94";break;
									case 96 : list[i]["type"] = "95";break;
									case 97 : list[i]["type"] = "96";break;
									case 98 : list[i]["type"] = "97";break;
									case 99 : list[i]["type"] = "98";break;
									case 100 : list[i]["type"] = "99";break;
									case 6 : list[i]["type"] = "不限";break;
								}
								
								//switch(parseInt(list[i]["used"])){
								//	case 0 : list[i]["used"] = "未使用";break;
								//	case 1 : list[i]["used"] = "已使用";break;
								//}
									
								//if(list[i]["player_id"] == '0'){
								//	list[i]["player_id"] = "不限";
								//}
								
								if(list[i]["end_time"] == '0'){
									list[i]["end_time"] = "不限";
								}else{
									list[i]["end_time"] = curentByTime(parseInt(list[i]["end_time"]));
								}
								
								//if(list[i]["used_time"] != '0'){
								//	list[i]["used_time"] = curentByTime(parseInt(list[i]["used_time"]));
								//}
								
								//if(list[i]["start_time"] != '0'){
								//	list[i]["start_time"] = curentByTime(parseInt(list[i]["start_time"]));
								//}
								
								//if(list[i]["user_id"] == '0'){
								//	list[i]["user_id"] = "未使用";
								//}
								
								tbody += "<tr>";
								tbody += "<td>"+list[i]["sn_code"]+"</td>";
								tbody += "<td>"+list[i]["pack_type"]+"</td>";
								//tbody += "<td>"+list[i]["item_id"]+"</td>";
								tbody += "<td>"+data.sname+"</td>";
								//tbody += "<td>"+list[i]["used"]+"</td>";
								//tbody += "<td>"+list[i]["player_id"]+"</td>";
								//tbody += "<td>"+list[i]["user_id"]+"</td>";
								//tbody += "<td>"+list[i]["user_level"]+"</td>";
								//tbody += "<td>"+list[i]["start_time"]+"</td>";
								tbody += "<td>"+list[i]["end_time"]+"</td>";
								tbody += "<td>"+list[i]["use"]+"</td>";
								tbody += "</tr>";
							}
							$("#codeBody").html(tbody);
							self.color_table("codeBody");
							$("#pagehtml").html(data.pageHtml);
						}else{
							$("#example_length").hide();
							$("#export_div").hide();
							$("#codeBody").html("<tr><td colspan='12'>没有数据！</td></tr>");
						}
					},
					error:function(){
						$("#example_length").hide();
						$("#export_div").hide();
						$("#codeBody").html("<tr><td colspan='12'>没有数据！</td></tr>");
					}
				})
			
			}
			
		}
		
		//每页显示
		$("#menu").change(function(){
			code_query.show_code(1);
		});
		
		//分页ajax函数
		var pageAjax = function(page){
			code_query.show_code(page);
		}
		
		$(document).ready(function(){
			code_query.INIT();
			//页面加载，显示table
		})
	</script>
</body>
</html>