/**
封装amcharts.js图表函数
 */
//单单饼图
function showSimplePie(chartData, chartdivid) {
	var chart;
    var legend;
    // PIE CHART
    chart = new AmCharts.AmPieChart();
    chart.dataProvider = chartData;
    chart.titleField = "country";
    chart.valueField = "litres";

    // LEGEND
    legend = new AmCharts.AmLegend();
    legend.align = "center";
    legend.markerType = "circle";
    chart.addLegend(legend);

    // WRITE
    chart.write(chartdivid);
}

//线图
function showLine(chartData,chartdivid,title,imgpath) {
	var chart;
	
	// this method is called when chart is first inited as we listen for "dataUpdated" event
    function zoomChart() {
        // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
        chart.zoomToIndexes(10, 20);
    }
    
    AmCharts.ready(function () {

        // SERIAL CHART
        chart = new AmCharts.AmSerialChart();
        chart.pathToImages = imgpath;
        chart.dataProvider = chartData;
        chart.categoryField = "date";

        // listen for "dataUpdated" event (fired when chart is inited) and call zoomChart method when it happens
        chart.addListener("dataUpdated", zoomChart);

        // AXES
        // category
        var categoryAxis = chart.categoryAxis;
        categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
        categoryAxis.minPeriod = "DD"; // our data is daily, so we set minPeriod to DD
        categoryAxis.minorGridEnabled = true;
        categoryAxis.axisColor = "#DADADA";
        categoryAxis.twoLineMode = true;
        categoryAxis.dateFormats = [{
             period: 'fff',
             format: 'JJ:NN:SS'
         }, {
             period: 'ss',
             format: 'JJ:NN:SS'
         }, {
             period: 'mm',
             format: 'JJ:NN'
         }, {
             period: 'hh',
             format: 'JJ:NN'
         }, {
             period: 'DD',
             format: 'DD'
         }, {
             period: 'WW',
             format: 'DD'
         }, {
             period: 'MM',
             format: 'MMM'
         }, {
             period: 'YYYY',
             format: 'YYYY'
         }];
        
        var color = ["#FF6600","#FCD202","#B0DE09","#ff8000","#ffff00","#ff00ff"];
        for(var i in title){
        	// first value axis (on the left)
            var valueAxis1 = new AmCharts.ValueAxis();
            valueAxis1.offset = (i-1)*20;
            valueAxis1.axisColor = color[i];
            valueAxis1.axisThickness = 2;
            valueAxis1.gridAlpha = 0;
            chart.addValueAxis(valueAxis1);
            
            // GRAPHS
            // first graph
            var graph1 = new AmCharts.AmGraph();
            graph1.valueAxis = valueAxis1; // we have to indicate which value axis should be used
            graph1.title = title[i]['title'];
            graph1.valueField = title[i]['value'];
            graph1.bullet = "round";
            graph1.hideBulletsCount = 30;
            graph1.bulletBorderThickness = 1;
            chart.addGraph(graph1);
        }
        
        // CURSOR
        var chartCursor = new AmCharts.ChartCursor();
        chartCursor.cursorAlpha = 0.1;
        chartCursor.fullWidth = true;
        chart.addChartCursor(chartCursor);

        // SCROLLBAR
        var chartScrollbar = new AmCharts.ChartScrollbar();
        chart.addChartScrollbar(chartScrollbar);

        // LEGEND
        var legend = new AmCharts.AmLegend();
        legend.marginLeft = 110;
        legend.useGraphSettings = true;
        chart.addLegend(legend);

        // WRITE
        chart.write(chartdivid);
    });
}
