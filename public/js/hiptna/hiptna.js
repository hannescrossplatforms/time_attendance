$( document ).ready(function() {

    // console.log("hiptna.js availableInstances : " + availableInstances);
    console.log("hiptna.js currentInstance : " + currentInstance);

    if (availableInstances == "BOTH") {
        $("#instance_menus").show();
    }

    if (currentInstance == "NONE") {
        $("#exception_manage_menus").hide();
    } else {
        $("#exception_manage_menus").show();
    }

    pathname = $('#url').val();

    $( ".close" ).click(function() {
      $('#member_popup').removeClass('in');
      $('#member_popup').hide();
    });

    result = $.ajax({

        url: pathname+'hiptna/latenessthreshold',
        type: 'get',
        async: false,
        data : '',
        success: function(data) {

        }
    }).responseText ; //alert(result);

    proximityresult = $.ajax({

        url: pathname+'hiptna/proximitythreshold',
        type: 'get',
        async: false,
        data : '',
        success: function(data) {

        }
    }).responseText ;
//alert(proximityresult);


    // new theme to drow different colors for negative and positive value
        FusionCharts.register("theme",{name:"colorData",theme:{base:{chart:{paletteColors:"#0075c2,#1aaf5d,#f2c500,#f45b00,#8e0000,#0e948c,#8cbb2c,#f3de00,#c02d00,#5b0101",labelDisplay:"auto",baseFontColor:"#333333",baseFont:"Helvetica Neue,Arial",captionFontSize:"14",subcaptionFontSize:"14",subcaptionFontBold:"0",showBorder:"0",bgColor:"#ffffff",showShadow:"0",canvasBgColor:"#ffffff",showCanvasBorder:"0",useplotgradientcolor:"0",useRoundEdges:"0",showPlotBorder:"0",showAlternateHGridColor:"0",showAlternateVGridColor:"0",
toolTipColor:"#ffffff",toolTipBorderThickness:"0",toolTipBgColor:"#000000",toolTipBgAlpha:"80",toolTipBorderRadius:"2",toolTipPadding:"5",legendBgAlpha:"0",legendBorderAlpha:"0",legendShadow:"0",legendItemFontSize:"10",legendItemFontColor:"#666666",legendCaptionFontSize:"9",divlineAlpha:"100",divlineColor:"#999999",divlineThickness:"1",divLineIsDashed:"1",divLineDashLen:"1",divLineGapLen:"1",scrollheight:"10",flatScrollBars:"1",scrollShowButtons:"0",scrollColor:"#cccccc",showHoverEffect:"1",valueFontSize:"10",
showXAxisLine:"1",xAxisLineThickness:"1",xAxisLineColor:"#999999"},dataset:[{}],trendlines:[{}]},geo:{chart:{showLabels:"0",fillColor:"#0075c2",showBorder:"1",borderColor:"#eeeeee",borderThickness:"1",borderAlpha:"50",entityFillhoverColor:"#0075c2",entityFillhoverAlpha:"80",connectorColor:"#cccccc",connectorThickness:"1.5",markerFillHoverAlpha:"90"}},pie2d:{chart:{placeValuesInside:"0",use3dlighting:"0",valueFontColor:"#333333",captionPadding:"15"},data:function(c,a,b){a=window.Math;return{alpha:100-
(50<b?a.round(100/a.ceil(b/10)):20)*a.floor(c/10)}}},doughnut2d:{chart:{placeValuesInside:"0",use3dlighting:"0",valueFontColor:"#333333",centerLabelFontSize:"12",centerLabelBold:"1",centerLabelFontColor:"#333333",captionPadding:"15"},data:function(c,a,b){a=window.Math;return{alpha:100-(50<b?a.round(100/a.ceil(b/10)):20)*a.floor(c/10)}}},line:{chart:{lineThickness:"2"}},spline:{chart:{lineThickness:"2"}},column2d:{chart:{paletteColors:"#0075c2",valueFontColor:"#ffffff",placeValuesInside:"1",rotateValues:"1"},
                    data: function (index, dataItem, dataLength) {
                        var value = parseInt(dataItem.value),
                            color;
                        if (value > parseInt(result)) { //alert()
                            color = '#ff0000';
                        } else if (value <= 0) {
                            color = '#22B14C';
                        }
                        return {color: color};
                    }},
bar2d:{chart:{paletteColors:"#0075c2",valueFontColor:"#ffffff",placeValuesInside:"1"}},column3d:{chart:{paletteColors:"#0075c2",valueFontColor:"#ffffff",placeValuesInside:"1",rotateValues:"1"}},bar3d:{chart:{paletteColors:"#0075c2",valueFontColor:"#ffffff",placeValuesInside:"1"}},area2d:{chart:{valueBgColor:"#ffffff",valueBgAlpha:"90",valueBorderPadding:"-2",valueBorderRadius:"2"}},splinearea:{chart:{valueBgColor:"#ffffff",valueBgAlpha:"90",valueBorderPadding:"-2",valueBorderRadius:"2"}},mscolumn2d:{chart:{valueFontColor:"#ffffff",
placeValuesInside:"1",rotateValues:"1"}},mscolumn3d:{chart:{showValues:"0"}},msstackedcolumn2dlinedy:{chart:{showValues:"0"}},stackedcolumn2d:{chart:{showValues:"0"}},stackedarea2d:{chart:{valueBgColor:"#ffffff",valueBgAlpha:"90",valueBorderPadding:"-2",valueBorderRadius:"2"}},stackedbar2d:{chart:{showValues:"0"}},msstackedcolumn2d:{chart:{showValues:"0"}},mscombi3d:{chart:{showValues:"0"}},mscombi2d:{chart:{showValues:"0"}},mscolumn3dlinedy:{chart:{showValues:"0"}},stackedcolumn3dline:{chart:{showValues:"0"}},
stackedcolumn2dline:{chart:{showValues:"0"}},scrollstackedcolumn2d:{chart:{valueFontColor:"#ffffff"}},scrollcombi2d:{chart:{showValues:"0"}},scrollcombidy2d:{chart:{showValues:"0"}},logstackedcolumn2d:{chart:{showValues:"0"}},logmsline:{chart:{showValues:"0"}},logmscolumn2d:{chart:{showValues:"0"}},msstackedcombidy2d:{chart:{showValues:"0"}},scrollcolumn2d:{chart:{valueFontColor:"#ffffff",placeValuesInside:"1",rotateValues:"1"}},pareto2d:{chart:{paletteColors:"#0075c2",showValues:"0"}},pareto3d:{chart:{paletteColors:"#0075c2",
showValues:"0"}},angulargauge:{chart:{pivotFillColor:"#ffffff",pivotRadius:"4",gaugeFillMix:"{light+0}",showTickValues:"1",majorTMHeight:"12",majorTMThickness:"2",majorTMColor:"#000000",minorTMNumber:"0",tickValueDistance:"10",valueFontSize:"24",valueFontBold:"1",gaugeInnerRadius:"50%",showHoverEffect:"0"},dials:{dial:[{baseWidth:"10",rearExtension:"7",bgColor:"#000000",bgAlpha:"100",borderColor:"#666666",bgHoverAlpha:"20"}]}},hlineargauge:{chart:{pointerFillColor:"#ffffff",gaugeFillMix:"{light+0}",
showTickValues:"1",majorTMHeight:"3",majorTMColor:"#000000",minorTMNumber:"0",valueFontSize:"18",valueFontBold:"1"},pointers:{pointer:[{}]}},bubble:{chart:{use3dlighting:"0",showplotborder:"0",showYAxisLine:"1",yAxisLineThickness:"1",yAxisLineColor:"#999999",showAlternateHGridColor:"0",showAlternateVGridColor:"0"},categories:[{verticalLineDashed:"1",verticalLineDashLen:"1",verticalLineDashGap:"1",verticalLineThickness:"1",verticalLineColor:"#000000",category:[{}]}],vtrendlines:[{line:[{alpha:"0"}]}]},
scatter:{chart:{use3dlighting:"0",showYAxisLine:"1",yAxisLineThickness:"1",yAxisLineColor:"#999999",showAlternateHGridColor:"0",showAlternateVGridColor:"0"},categories:[{verticalLineDashed:"1",verticalLineDashLen:"1",verticalLineDashGap:"1",verticalLineThickness:"1",verticalLineColor:"#000000",category:[{}]}],vtrendlines:[{line:[{alpha:"0"}]}]},boxandwhisker2d:{chart:{valueBgColor:"#ffffff",valueBgAlpha:"90",valueBorderPadding:"-2",valueBorderRadius:"2"}},thermometer:{chart:{gaugeFillColor:"#0075c2"}},
cylinder:{chart:{cylFillColor:"#0075c2"}},sparkline:{chart:{linecolor:"#0075c2"}},sparkcolumn:{chart:{plotFillColor:"#0075c2"}},sparkwinloss:{chart:{winColor:"#0075c2",lossColor:"#1aaf5d",drawColor:"#f2c500",scoreLessColor:"#f45b00"}},hbullet:{chart:{plotFillColor:"#0075c2",targetColor:"#1aaf5d"}},vbullet:{chart:{plotFillColor:"#0075c2",targetColor:"#1aaf5d"}}}});

// end new theme to drow different colors for negative and positive value



    // new theme to drow different colors for negative and positive value
        FusionCharts.register("theme",{name:"colorDataP",theme:{base:{chart:{paletteColors:"#0075c2,#1aaf5d,#f2c500,#f45b00,#8e0000,#0e948c,#8cbb2c,#f3de00,#c02d00,#5b0101",labelDisplay:"auto",baseFontColor:"#333333",baseFont:"Helvetica Neue,Arial",captionFontSize:"14",subcaptionFontSize:"14",subcaptionFontBold:"0",showBorder:"0",bgColor:"#ffffff",showShadow:"0",canvasBgColor:"#ffffff",showCanvasBorder:"0",useplotgradientcolor:"0",useRoundEdges:"0",showPlotBorder:"0",showAlternateHGridColor:"0",showAlternateVGridColor:"0",
toolTipColor:"#ffffff",toolTipBorderThickness:"0",toolTipBgColor:"#000000",toolTipBgAlpha:"80",toolTipBorderRadius:"2",toolTipPadding:"5",legendBgAlpha:"0",legendBorderAlpha:"0",legendShadow:"0",legendItemFontSize:"10",legendItemFontColor:"#666666",legendCaptionFontSize:"9",divlineAlpha:"100",divlineColor:"#999999",divlineThickness:"1",divLineIsDashed:"1",divLineDashLen:"1",divLineGapLen:"1",scrollheight:"10",flatScrollBars:"1",scrollShowButtons:"0",scrollColor:"#cccccc",showHoverEffect:"1",valueFontSize:"10",
showXAxisLine:"1",xAxisLineThickness:"1",xAxisLineColor:"#999999"},dataset:[{}],trendlines:[{}]},geo:{chart:{showLabels:"0",fillColor:"#0075c2",showBorder:"1",borderColor:"#eeeeee",borderThickness:"1",borderAlpha:"50",entityFillhoverColor:"#0075c2",entityFillhoverAlpha:"80",connectorColor:"#cccccc",connectorThickness:"1.5",markerFillHoverAlpha:"90"}},pie2d:{chart:{placeValuesInside:"0",use3dlighting:"0",valueFontColor:"#333333",captionPadding:"15"},data:function(c,a,b){a=window.Math;return{alpha:100-
(50<b?a.round(100/a.ceil(b/10)):20)*a.floor(c/10)}}},doughnut2d:{chart:{placeValuesInside:"0",use3dlighting:"0",valueFontColor:"#333333",centerLabelFontSize:"12",centerLabelBold:"1",centerLabelFontColor:"#333333",captionPadding:"15"},data:function(c,a,b){a=window.Math;return{alpha:100-(50<b?a.round(100/a.ceil(b/10)):20)*a.floor(c/10)}}},line:{chart:{lineThickness:"2"}},spline:{chart:{lineThickness:"2"}},column2d:{chart:{paletteColors:"#0075c2",valueFontColor:"#ffffff",placeValuesInside:"1",rotateValues:"1"},
                    data: function (index, dataItem, dataLength) {
                        var value = parseInt(dataItem.value),
                            colorp;
                        if (value < parseInt(proximityresult)) {
                            colorp = '#ff0000';
                        } else if (value >= parseInt(proximityresult)) {
                            colorp = '#22B14C';
                        }
                        return {color: colorp};
                    }},
bar2d:{chart:{paletteColors:"#0075c2",valueFontColor:"#ffffff",placeValuesInside:"1"}},column3d:{chart:{paletteColors:"#0075c2",valueFontColor:"#ffffff",placeValuesInside:"1",rotateValues:"1"}},bar3d:{chart:{paletteColors:"#0075c2",valueFontColor:"#ffffff",placeValuesInside:"1"}},area2d:{chart:{valueBgColor:"#ffffff",valueBgAlpha:"90",valueBorderPadding:"-2",valueBorderRadius:"2"}},splinearea:{chart:{valueBgColor:"#ffffff",valueBgAlpha:"90",valueBorderPadding:"-2",valueBorderRadius:"2"}},mscolumn2d:{chart:{valueFontColor:"#ffffff",
placeValuesInside:"1",rotateValues:"1"}},mscolumn3d:{chart:{showValues:"0"}},msstackedcolumn2dlinedy:{chart:{showValues:"0"}},stackedcolumn2d:{chart:{showValues:"0"}},stackedarea2d:{chart:{valueBgColor:"#ffffff",valueBgAlpha:"90",valueBorderPadding:"-2",valueBorderRadius:"2"}},stackedbar2d:{chart:{showValues:"0"}},msstackedcolumn2d:{chart:{showValues:"0"}},mscombi3d:{chart:{showValues:"0"}},mscombi2d:{chart:{showValues:"0"}},mscolumn3dlinedy:{chart:{showValues:"0"}},stackedcolumn3dline:{chart:{showValues:"0"}},
stackedcolumn2dline:{chart:{showValues:"0"}},scrollstackedcolumn2d:{chart:{valueFontColor:"#ffffff"}},scrollcombi2d:{chart:{showValues:"0"}},scrollcombidy2d:{chart:{showValues:"0"}},logstackedcolumn2d:{chart:{showValues:"0"}},logmsline:{chart:{showValues:"0"}},logmscolumn2d:{chart:{showValues:"0"}},msstackedcombidy2d:{chart:{showValues:"0"}},scrollcolumn2d:{chart:{valueFontColor:"#ffffff",placeValuesInside:"1",rotateValues:"1"}},pareto2d:{chart:{paletteColors:"#0075c2",showValues:"0"}},pareto3d:{chart:{paletteColors:"#0075c2",
showValues:"0"}},angulargauge:{chart:{pivotFillColor:"#ffffff",pivotRadius:"4",gaugeFillMix:"{light+0}",showTickValues:"1",majorTMHeight:"12",majorTMThickness:"2",majorTMColor:"#000000",minorTMNumber:"0",tickValueDistance:"10",valueFontSize:"24",valueFontBold:"1",gaugeInnerRadius:"50%",showHoverEffect:"0"},dials:{dial:[{baseWidth:"10",rearExtension:"7",bgColor:"#000000",bgAlpha:"100",borderColor:"#666666",bgHoverAlpha:"20"}]}},hlineargauge:{chart:{pointerFillColor:"#ffffff",gaugeFillMix:"{light+0}",
showTickValues:"1",majorTMHeight:"3",majorTMColor:"#000000",minorTMNumber:"0",valueFontSize:"18",valueFontBold:"1"},pointers:{pointer:[{}]}},bubble:{chart:{use3dlighting:"0",showplotborder:"0",showYAxisLine:"1",yAxisLineThickness:"1",yAxisLineColor:"#999999",showAlternateHGridColor:"0",showAlternateVGridColor:"0"},categories:[{verticalLineDashed:"1",verticalLineDashLen:"1",verticalLineDashGap:"1",verticalLineThickness:"1",verticalLineColor:"#000000",category:[{}]}],vtrendlines:[{line:[{alpha:"0"}]}]},
scatter:{chart:{use3dlighting:"0",showYAxisLine:"1",yAxisLineThickness:"1",yAxisLineColor:"#999999",showAlternateHGridColor:"0",showAlternateVGridColor:"0"},categories:[{verticalLineDashed:"1",verticalLineDashLen:"1",verticalLineDashGap:"1",verticalLineThickness:"1",verticalLineColor:"#000000",category:[{}]}],vtrendlines:[{line:[{alpha:"0"}]}]},boxandwhisker2d:{chart:{valueBgColor:"#ffffff",valueBgAlpha:"90",valueBorderPadding:"-2",valueBorderRadius:"2"}},thermometer:{chart:{gaugeFillColor:"#0075c2"}},
cylinder:{chart:{cylFillColor:"#0075c2"}},sparkline:{chart:{linecolor:"#0075c2"}},sparkcolumn:{chart:{plotFillColor:"#0075c2"}},sparkwinloss:{chart:{winColor:"#0075c2",lossColor:"#1aaf5d",drawColor:"#f2c500",scoreLessColor:"#f45b00"}},hbullet:{chart:{plotFillColor:"#0075c2",targetColor:"#1aaf5d"}},vbullet:{chart:{plotFillColor:"#0075c2",targetColor:"#1aaf5d"}}}});

// end new theme to drow different colors for negative and positive value



});

function getChartHeight(numrows) {
    // alert("getChartHeight");
  if(numrows <=4) {
    return 250;

  } else if(numrows > 4 && numrows <= 8) {
    return 300;

  } else if(numrows > 8 && numrows <= 16) {
    return 600;

  } else if(numrows > 16 && numrows <= 32) {
    return 900;

  } else if(numrows > 32 && numrows <= 64) {
    return 1300;

  } else if(numrows > 64 && numrows <= 128) {
    return 3200;

  } else if(numrows > 128 && numrows <= 256) {
    return 5400;

  } else {
    return 6400;

  }
}



function change_report_period(){
    var time = $("#brandreportperiod").val();
    if(time == 'daterange') {
        $('#custom').show();
    } else {
        $('#custom').hide();
        renderCharts(time,'','')
    }
}

function custom_report_period(){
    var from = $('#venuefrom').val();
    var to = $('#venueto').val();

    renderCharts('daterange',from,to)
}


function renderCharts(time,start,end){


    $.ajax({

            url: pathname+'hiptna/periodchartJsondata',
            type: 'get',
            dataType: 'json',
            data : { 'period':time,'start':start,'end':end },
            success: function(data) {

                $("#report_period").html(data.report_period);
                $("#report_name_date").val(data.report_name_date);

                if(data.currentInstance == 'NR01' || data.currentInstance == 'NR02' ){

                    //-------------- Staff On Time -------------
                    var chartProperties = {
                        "caption": "",
                        "xAxisName": "Date",
                        "yAxisName": "Staff",
                        "paletteColors" : "#0075c2,#f8b81d",
                        "rotatevalues": "1",
                        "theme": "zune",
                        "clickURL": pathname+'hiptna_showgraphdrilldown?period='+time+'&start='+start+'&end='+end+'#lateness'
                    };

                    apiChart = new FusionCharts({
                        type: 'msline',
                        renderAt: 'staff_ontime_trend',
                        width: '400',
                        height: '350',
                        dataFormat: 'json',
                        dataSource: {
                            "chart": chartProperties,
                            "categories": [
                                {
                                    "category": data.category
                                }
                            ],
                            "dataset": data.lateness_graph
                            //"data": data.ontime_graph

                        }
                    });
                    apiChart.render();

                    var chartProperties = {
                        "caption": "",
                        "xAxisName": "Date",
                        "yAxisName": "Staff",
                        "paletteColors" : "#0075c2,#f8b81d",
                        "rotatevalues": "1",
                        "theme": "zune",
                        "clickURL": pathname+'hiptna_showgraphdrilldown?period='+time+'&start='+start+'&end='+end+'#lateness'
                    };

                    apiChart = new FusionCharts({
                        type: 'mscolumn2d',
                        renderAt: 'staff_ontime',
                        width: '400',
                        height: '350',
                        dataFormat: 'json',
                        dataSource: {
                            "chart": chartProperties,
                            "categories": [
                                {
                                    "category": data.category
                                }
                            ],
                            "dataset": data.lateness_graph
                            //"data": data.ontime_graph

                        }
                    });
                    apiChart.render();

                } else {
                    //----------------staff at work-----------
                    var chartProperties = {
                        "caption": "",
                        "xAxisName": "Date",
                        "yAxisName": "Staff",
                        "paletteColors" : "#0075c2,#f8b81d",
                        "rotatevalues": "1",
                        "theme": "zune",
                        "clickURL": pathname+'hiptna_showgraphdrilldown?period='+time+'&start='+start+'&end='+end+'#absence'
                    };

                    apiChart = new FusionCharts({
                        type: 'msline',
                        renderAt: 'staff_wrk_trend',
                        width: '400',
                        height: '350',
                        dataFormat: 'json',
                        dataSource: {
                            "chart": chartProperties,
                            "categories": [
                                {
                                    "category": data.category
                                }
                            ],
                            "dataset": data.staff_graph
                            //"data": data.staff_graph

                        }
                    });
                    apiChart.render();

                    var chartProperties = {
                        "caption": "",
                        "xAxisName": "Date",
                        "yAxisName": "Staff",
                        "paletteColors" : "#0075c2,#f8b81d",
                        "rotatevalues": "1",
                        "theme": "zune",
                        "clickURL": pathname+'hiptna_showgraphdrilldown?period='+time+'&start='+start+'&end='+end+'#absence'
                    };

                    apiChart = new FusionCharts({
                        type: 'mscolumn2d',
                        renderAt: 'staff_wrk',
                        width: '400',
                        height: '350',
                        dataFormat: 'json',
                        dataSource: {
                            "chart": chartProperties,
                            "categories": [
                                {
                                    "category": data.category
                                }
                            ],
                            "dataset": data.staff_graph
                            //"data": data.staff_graph

                        }
                    });
                    apiChart.render();

                    //-------------- Staff On Time -------------
                    var chartProperties = {
                        "caption": "",
                        "xAxisName": "Date",
                        "yAxisName": "Staff",
                        "paletteColors" : "#0075c2,#f8b81d",
                        "rotatevalues": "1",
                        "theme": "zune",
                        "clickURL": pathname+'hiptna_showgraphdrilldown?period='+time+'&start='+start+'&end='+end+'#lateness'
                    };

                    apiChart = new FusionCharts({
                        type: 'msline',
                        renderAt: 'staff_ontime_trend',
                        width: '400',
                        height: '350',
                        dataFormat: 'json',
                        dataSource: {
                            "chart": chartProperties,
                            "categories": [
                                {
                                    "category": data.category
                                }
                            ],
                            "dataset": data.lateness_graph
                            //"data": data.ontime_graph

                        }
                    });
                    apiChart.render();

                    var chartProperties = {
                        "caption": "",
                        "xAxisName": "Date",
                        "yAxisName": "Staff",
                        "paletteColors" : "#0075c2,#f8b81d",
                        "rotatevalues": "1",
                        "theme": "zune",
                        "clickURL": pathname+'hiptna_showgraphdrilldown?period='+time+'&start='+start+'&end='+end+'#lateness'
                    };

                    apiChart = new FusionCharts({
                        type: 'mscolumn2d',
                        renderAt: 'staff_ontime',
                        width: '400',
                        height: '350',
                        dataFormat: 'json',
                        dataSource: {
                            "chart": chartProperties,
                            "categories": [
                                {
                                    "category": data.category
                                }
                            ],
                            "dataset": data.lateness_graph
                            //"data": data.ontime_graph

                        }
                    });
                    apiChart.render();


                    //-------------- Staff Meeting WS Proximity Target -------------
                    var chartProperties = {
                        "caption": "",
                        "xAxisName": "Date",
                        "yAxisName": "Staff",
                        "paletteColors" : "#0075c2,#f8b81d",
                        "rotatevalues": "1",
                        "theme": "zune",
                        "clickURL": pathname+'hiptna_showgraphdrilldown?period='+time+'&start='+start+'&end='+end+'#wsproximity'
                    };

                    apiChart = new FusionCharts({
                        type: 'msline',
                        renderAt: 'staff_proximity_trend',
                        width: '400',
                        height: '350',
                        dataFormat: 'json',
                        dataSource: {
                            "chart": chartProperties,
                            "categories": [
                                {
                                    "category": data.category
                                }
                            ],
                            "dataset": data.wsproximity_graph
                            //"data": data.proximity_graph

                        }
                    });
                    apiChart.render();

                    var chartProperties = {
                        "caption": "",
                        "xAxisName": "Date",
                        "yAxisName": "Staff",
                        "paletteColors" : "#0075c2,#f8b81d",
                        "rotatevalues": "1",
                        "theme": "zune",
                        "clickURL": pathname+'hiptna_showgraphdrilldown?period='+time+'&start='+start+'&end='+end+'#wsproximity'
                    };

                    apiChart = new FusionCharts({
                        type: 'mscolumn2d',
                        renderAt: 'staff_proximity',
                        width: '400',
                        height: '350',
                        dataFormat: 'json',
                        dataSource: {
                            "chart": chartProperties,
                            "categories": [
                                {
                                    "category": data.category
                                }
                            ],
                            "dataset": data.wsproximity_graph
                            //"data": data.proximity_graph

                        }
                    });
                    apiChart.render();
                }
            }
        });
}

//printpreview for hiptna charts starts here

function printpreview() {
    $('#loadingDiv').show();
    var fusioncharts_container = {};
        $("span[class='fusioncharts-container']").each(function(index, elem){
            var spanId              =   $(this).attr('id');
            spanId                  =   spanId.split("-");
            fusioncharts_container[spanId[1]]    =   $(this).html();
        });

        var fusionchartspans            = fusioncharts_container;
        var fusionImg = $.ajax({
                type 		: 'POST',
                dataType 	: 'json',
                async           : false,
                url 		: pathname+'hiptna_convertsvgtoimage',
                data 		: {
                'fusionchartspans'         : fusionchartspans
                },
                success     : function(result) {
                    $('#loadingDiv').hide();
                }

            });

        var fusionImages  = fusionImg.responseJSON.result_img;
            $("span[class='fusioncharts-container']").each(function(index, elem){
                $(this).removeAttr('style');
                var spanId              =   $(this).attr('id');
                spanId                  =   spanId.split("-");
                var image_path          =   'fc_images/image_temp/'+fusionImages['img_'+spanId[1]];
                $(this).html('<img src="'+image_path+'">');

            });

            var i = 1;
            $("#fusion-chart .col-sm-6").each(function(index, elem){

                if(i%2 == 0) {
                    $(this).addClass('col-6-right-al');
                } else {
                    $(this).addClass('col-6-left-al');
                }
                i++;
            });

            previewPDF(fusionImages);
}

function exception_change_report_period(){
    var time = $("#brandreportperiod").val();
    if(time == 'daterange') {
        $('#custom').show();
    } else {
        $('#custom').hide();
        renderexceptionCharts(time,'','')
    }
}

function exception_custom_report_period(){
    var time = $("#brandreportperiod").val();
    var from = $('#venuefrom').val();
    var to = $('#venueto').val();

    renderexceptionCharts(time,from,to)
}

function renderexceptionCharts(time,start,end){

    $.ajax({

            url: pathname+'hiptna/periodExceptionchartJsondata',
            type: 'get',
            dataType: 'json',
            data : { 'period':time,'start':start,'end':end },
            success: function(data) {


                fullData = data;

                //absent
                var chartProperties = {
                      "caption": "",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Days Absent",
                      "rotatevalues": "1",
                      "theme": "zune",
                      "link": "JavaScript:membergraph("+data.external_user_id+")"

                  };
                  apiChart = new FusionCharts({
                      type: 'bar2d',
                      renderAt: 'staff_absence_list',
                      width: '500',
                      height: getChartHeight(data.staff_absent.length),
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": data.staff_absent
                      }
                  });
                  apiChart.render();

                //lateness
                var chartProperties = {
                    "caption": "",
                    "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                    "yAxisName": "Days Late",
                    "rotatevalues": "1",
                    "theme": "zune",
                    "link": "JavaScript:membergraph("+data.external_user_id+")"
                };

                apiChart = new FusionCharts({
                    type: 'bar2d',
                    renderAt: 'staff_lateness_list',
                    width: '500',
                    height: getChartHeight(data.staff_lateness.length),
                    dataFormat: 'json',
                    dataSource: {
                        "chart": chartProperties,
                  "data": data.staff_lateness

                    }
                });
                apiChart.render();


                //proximity
                var chartProperties = {
                      "caption": "",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Days Not Meeting WS Target",
                      "rotatevalues": "1",
                      "theme": "zune",
                      "link": "JavaScript:membergraph("+data.external_user_id+")"
                  };

                  apiChart = new FusionCharts({
                      type: 'bar2d',
                      renderAt: 'staff_proximity_list',
                      width: '500',
                      height: getChartHeight(data.staff_proximity.length),
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": data.staff_proximity
                      }
                });
                apiChart.render();


                //get most 5 absent details
              var most_absent = [];
              $.each(data.staff_absent.slice(0,5), function(i, list) {
                  most_absent.push(list);
              });
              var yaxisabsent = (most_absent.length == 0 || most_absent[0].value < 2 ) ? 5 : most_absent[0].value;
              //get least 5 absent details
              var max_length = data.staff_absent.length;
              var min_length = max_length - 5;
              var least_absent = [];
              $.each(data.staff_absent.slice(min_length,max_length).reverse(), function(i, list) {
                  least_absent.push(list);
              }); //console.log(least_absent); alert("jjj");
              var chartProperties = {
                      "caption": "Top 5",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Days absent",
                      "yAxisMaxValue": yaxisabsent,
                      "yAxisMinValue": 0,
                      "numDivLines" : yaxisabsent - 2,
                      "rotatevalues": "1",
                      "theme": "zune",
                      "link": "JavaScript:membergraph("+data.external_user_id+")"
                  };

                  apiChart = new FusionCharts({
                      type: 'bar2d',
                      renderAt: 'most_absence_list',
                      width: '300',
                      height: '220',
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": most_absent

                      }
                  });
                  apiChart.render();

                  var chartProperties = {
                      "caption": "Bottom 5",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Days absent",
                      "yAxisMaxValue": yaxisabsent,
                      "yAxisMinValue": 0,
                      "numDivLines" : yaxisabsent - 2,
                      "rotatevalues": "1",
                      "theme": "zune",
                      "link": "JavaScript:membergraph("+data.external_user_id+")"
                  };

                  apiChart = new FusionCharts({
                      type: 'bar2d',
                      renderAt: 'least_absence_list',
                      width: '300',
                      height: '220',
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": least_absent

                      }
                  });
                  apiChart.render();

                  //------------------------

              //get most 5 lateness details
              var most_late = [];
              $.each(data.staff_lateness.slice(0,5), function(i, list) {
                  most_late.push(list);
              });
              var yaxislateness = (most_late.length == 0 || most_late[0].value < 2 ) ? 5 : most_late[0].value;
              //get least 5 lateness details
              var max_length_l = data.staff_lateness.length;
              var min_length_l = max_length_l - 5;
              var least_late = [];
              $.each(data.staff_lateness.slice(min_length_l,max_length_l).reverse(), function(i, list) {
                  least_late.push(list);
              }); //console.log(least_absent); alert("jjj");
              var chartProperties = {
                      "caption": "Top 5 - Lateness",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Days late",
                      "yAxisMaxValue": yaxislateness,
                      "yAxisMinValue": 0,
                      "numDivLines" : yaxislateness - 2,
                      "rotatevalues": "1",
                      "theme": "zune",
                      "link": "JavaScript:membergraph("+data.external_user_id+")"
                  };

                  apiChart = new FusionCharts({
                      type: 'bar2d',
                      renderAt: 'most_lateness_list',
                      width: '300',
                      height: '220',
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": most_late

                      }
                  });
                  apiChart.render();

                  var chartProperties = {
                      "caption": "Bottom 5 - Lateness",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Days late",
                      "yAxisMaxValue": yaxislateness,
                      "yAxisMinValue": 0,
                      "numDivLines" : yaxislateness - 2,
                      "rotatevalues": "1",
                      "theme": "zune",
                      "link": "JavaScript:membergraph("+data.external_user_id+")"
                  };

                  apiChart = new FusionCharts({
                      type: 'bar2d',
                      renderAt: 'least_lateness_list',
                      width: '300',
                      height: '220',
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": least_late

                      }
                  });
                  apiChart.render();

                      //------------------------

              //get most 5 proximity details
              var most_proximity = [];
              $.each(data.staff_proximity.slice(0,5), function(i, list) {
                  most_proximity.push(list);
              });
              var yaxisproximity = (most_proximity.length == 0 || most_proximity[0].value < 2 ) ? 5 : most_proximity[0].value;
              //get least 5 proximity details
              var max_length_p = data.staff_proximity.length;
              var min_length_p = max_length_p - 5;
              var least_proximity = [];
              $.each(data.staff_proximity.slice(min_length_p,max_length_p).reverse(), function(i, list) {
                  least_proximity.push(list);
              }); //console.log(least_absent); alert("jjj");
              var chartProperties = {
                      "caption": "Top 5",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Days not meeting target",
                      "yAxisMaxValue": yaxisproximity,
                      "yAxisMinValue": 0,
                      "numDivLines" : yaxisproximity - 2,
                      "rotatevalues": "1",
                      "theme": "zune",
                      "link": "JavaScript:membergraph("+data.external_user_id+")"
                  };

                  apiChart = new FusionCharts({
                      type: 'bar2d',
                      renderAt: 'most_proximity_list',
                      width: '300',
                      height: '220',
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": most_proximity

                      }
                  });
                  apiChart.render();

                  var chartProperties = {
                      "caption": "Bottom 5",
                      "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Days not meeting target",
                      "yAxisMaxValue": yaxisproximity,
                      "yAxisMinValue": 0,
                      "numDivLines" : yaxisproximity - 2,
                      "rotatevalues": "1",
                      "theme": "zune",
                      "link": "JavaScript:membergraph("+data.external_user_id+")"
                  };

                  apiChart = new FusionCharts({
                      type: 'bar2d',
                      renderAt: 'least_proximity_list',
                      width: '300',
                      height: '220',
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": least_proximity

                      }
                  });
                  apiChart.render();


                    //get most 5 early leaving details
                    var most_early_leaving = [];
                    $.each(data.staff_early_leaving.slice(0,5), function(i, list) {
                        most_early_leaving.push(list);
                    });
                    var yaxisearly_leaving = (most_early_leaving.length == 0 || most_early_leaving[0].value < 2 ) ? 5 : most_early_leaving[0].value;
                    //alert(yaxisearly_leaving);//get least 5 proximity details
                    var max_length_e = data.staff_early_leaving.length;
                    var min_length_e = max_length_e - 5;
                    var least_early_leaving = [];
                    $.each(data.staff_early_leaving.slice(min_length_e,max_length_e).reverse(), function(i, list) {
                      least_early_leaving.push(list);
                    }); //console.log(least_absent); alert("jjj");
                    var chartProperties = {
                          "caption": "Top 5 - Early Leavers",
                          "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                          "yAxisName": "Days leaving early",
                          "yAxisMaxValue": yaxisearly_leaving,
                          "yAxisMinValue": 0,
                          "numDivLines" : yaxisearly_leaving - 2,
                          "rotatevalues": "1",
                          "theme": "zune",
                          "link": "JavaScript:membergraph("+data.external_user_id+")"
                      };

                      apiChart = new FusionCharts({
                          type: 'bar2d',
                          renderAt: 'most_early_leaving',
                          width: '300',
                          height: '220',
                          dataFormat: 'json',
                          dataSource: {
                              "chart": chartProperties,
                              "data": most_early_leaving

                          }
                      });
                      apiChart.render();

                      var chartProperties = {
                          "caption": "Bottom 5 - Early Leavers",
                          "xAxisName": "Name", // This whould be the full name in the format "Surname, first names"
                          "yAxisName": "Days leaving early",
                          "yAxisMaxValue": yaxisearly_leaving,
                          "yAxisMinValue": 0,
                          "numDivLines" : yaxisearly_leaving - 2,
                          "rotatevalues": "1",
                          "theme": "zune",
                          "link": "JavaScript:membergraph("+data.external_user_id+")"
                      };

                      apiChart = new FusionCharts({
                          type: 'bar2d',
                          renderAt: 'least_early_leaving',
                          width: '300',
                          height: '220',
                          dataFormat: 'json',
                          dataSource: {
                              "chart": chartProperties,
                              "data": least_early_leaving

                          }
                      });
                      apiChart.render();



            }
        });
}

/*function membergraph(staff_id) {

    //alert(staff_id);
    var time = $("#brandreportperiod").val();
    if(time == 'daterange'){
        start = $('#venuefrom').val();
        end = $('#venueto').val();
    }else{
        start = '';
        end = '';
    }

    $.ajax({

            url: pathname+'hiptna/memberGraph',
            type: 'get',
            dataType: 'json',
            data : { 'period':time,'start':start,'end':end,'staff_id':staff_id },
            success: function(data) {

                $("#member_popup").addClass('in');
                $("#member_popup").show();
                $("#myModalLabel").html(data.staff_absent[0].name);
                //absent
                var chartProperties = {
                      "caption": "",
                      "xAxisName": "Date", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Absence",
                      "rotatevalues": "1",
                      "theme": "zune"

                  };
                  apiChart = new FusionCharts({
                      type: 'column2d',
                      renderAt: 'member_absence',
                      width: '750',
                      height: '150',
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": data.staff_absent
                      }
                  });
                  apiChart.render();

                //lateness
                var chartProperties = {
                    "caption": "",
                    "xAxisName": "Date", // This whould be the full name in the format "Surname, first names"
                    "yAxisName": "Lateness",
                    "rotatevalues": "1",
                    "theme": "zune"
                };

                apiChart = new FusionCharts({
                    type: 'column2d',
                    renderAt: 'member_lateness',
                    width: '750',
                    height: '150',
                    dataFormat: 'json',
                    dataSource: {
                        "chart": chartProperties,
                  "data": data.staff_lateness

                    }
                });
                apiChart.render();


                //proximity
                var chartProperties = {
                      "caption": "",
                      "xAxisName": "Date", // This whould be the full name in the format "Surname, first names"
                      "yAxisName": "Proximity",
                      "rotatevalues": "1",
                      "theme": "zune"
                  };

                  apiChart = new FusionCharts({
                      type: 'column2d',
                      renderAt: 'member_proximity',
                      width: '750',
                      height: '150',
                      dataFormat: 'json',
                      dataSource: {
                          "chart": chartProperties,
                          "data": data.staff_proximity
                      }
                });
                apiChart.render();

            }
    });
}*/

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}



/*function displayMemberAbsence(data) {
     var chartProperties = {
          "caption": "",
          "xAxisName": "Date", // This whould be the full name in the format "Surname, first names"
          "yAxisName": "Absence",
          "rotatevalues": "1",
          "theme": "zune"

      };
      apiChart = new FusionCharts({
          type: 'column2d',
          renderAt: 'member_absence',
          width: '750',
          height: '150',
          dataFormat: 'json',
          dataSource: {
              "chart": chartProperties,
              "data": data.staff_absent
          }
      });
      apiChart.render();
}*/

/*function displayMemberLateness(data) {
        var chartProperties = {
            "caption": "",
            "xAxisName": "Date", // This whould be the full name in the format "Surname, first names"
            "yAxisName": "Lateness",
            "rotatevalues": "1",
            "theme": "colorData"
        };

        apiChart = new FusionCharts({
            type: 'column2d',
            renderAt: 'member_lateness',
            width: '750',
            height: '150',
            dataFormat: 'json',
            dataSource: {
                "chart": chartProperties,
          "data": data.staff_lateness

            }
        });
        apiChart.render();
}*/

/*function displayMemberProximity(data) {
        var chartProperties = {
              "caption": "",
              "xAxisName": "Date", // This whould be the full name in the format "Surname, first names"
              "yAxisName": "Proximity",
              "rotatevalues": "1",
              "theme": "zune"
          };

          apiChart = new FusionCharts({
              type: 'column2d',
              renderAt: 'member_proximity',
              width: '750',
              height: '150',
              dataFormat: 'json',
              dataSource: {
                  "chart": chartProperties,
                  "data": data.staff_proximity
              }
        });
        apiChart.render();
}*/

function displayMemberAbsenceToday(data) {

    $boxcode='\
        <div >\
          <div class="venuerow">\
            <div class="modStat">\
                <div class="modstattitle">\
                    <h3>Absence</h3>\
                </div>\
                <div id="staff_today" class="modStatspan"><span style="font-size: 110%;">' + data.staff_absent[0].value + '</span></div>\
              </div>\
          </div>\
      </div>';

    $("#member_absence").html($boxcode);
}


function displayMemberLatenessToday(data) {

    $boxcode='\
        <div  >\
          <div class="venuerow">\
            <div class="modStat">\
                <div class="modstattitle">\
                    <h3>Lateness</h3>\
                </div>\
                <div id="staff_today" class="modStatspan"><span style="font-size: 110%;">' + data.staff_lateness[0].value + '</span></div>\
              </div>\
            </div>\
        </div>';

    $("#member_lateness").html($boxcode);

}

function displayMemberProximityToday(data) {

    $boxcode='\
    <div  >\
        <div class="venuerow">\
            <div class="modStat">\
                <div class="modstattitle">\
                    <h3>WS Proximity Yesterday</h3>\
                </div>\
                <div id="staff_today" class="modStatspan"><span style="font-size: 110%;">' + data.staff_proximity[0].value + '</span></div>\
            </div>\
        </div>\
    </div>';

    $("#member_proximity").html($boxcode);

}

function displayMemberAbsence(data) {

  var chartProperties = {
        "caption": "",
        "xAxisName": "Date", // This whould be the full name in the format "Surname, first names"
        "yAxisName": "Absence",
        "rotatevalues": "1",
        "theme": "zune"

    };
    apiChart = new FusionCharts({
        type: 'column2d',
        renderAt: 'member_absence',
        width: '750',
        height: '210',
        dataFormat: 'json',
        dataSource: {
            "chart": chartProperties,
            "data": data.staff_absent
        }
    });
    apiChart.render();
}

function displayMemberLateness(data) {
    var chartProperties = {
        "caption": "",
        "xAxisName": "Date", // This whould be the full name in the format "Surname, first names"
        "yAxisName": "Lateness",
        "rotatevalues": "1",
        "theme": "colorData"
    };

    apiChart = new FusionCharts({
        type: 'column2d',
        renderAt: 'member_lateness',
        width: '750',
        height: '210',
        dataFormat: 'json',
        dataSource: {
            "chart": chartProperties,
      "data": data.staff_lateness

        }
    });
    apiChart.render();
}

function displayMemberProximity(data) {
    var chartProperties = {
          "caption": "",
          "xAxisName": "Date", // This whould be the full name in the format "Surname, first names"
          "yAxisName": "WS Proximity",
          "rotatevalues": "1",
          "theme": "colorDataP"
      };

      apiChart = new FusionCharts({
          type: 'column2d',
          renderAt: 'member_proximity',
          width: '750',
          height: '210',
          dataFormat: 'json',
          dataSource: {
              "chart": chartProperties,
              "data": data.staff_proximity,
                "trendlines": [
                    {
                        "line": [
                            {
                                "startvalue": proximityresult,
                                "color": "#0000FF",
                            }
                                // "valueOnRight": "1",
                                // "tooltext": "",
                                // "displayvalue": "Target - "+proximityresult
                        ]
                    }
                ]
          }
    });
    apiChart.render();
}



function membergraph(staff_id) {

    //alert(staff_id);
    var time = $("#brandreportperiod").val();
    if(time == 'daterange'){
        start = $('#venuefrom').val();
        end = $('#venueto').val();
    }else{
        start = '';
        end = '';
    }

    if(time == "today") {
      url = pathname+'hiptna/memberGraphToday';
    } else {
      url = pathname+'hiptna/memberGraph';
    }
    var report_date ="";

    $.ajax({

        url: url,
        type: 'get',
        dataType: 'json',
        data : { 'period':time,'start':start,'end':end,'staff_id':staff_id },
        success: function(data) {

            $("#member_popup").addClass('in');
            $("#member_popup").show();
            $("#memberHeader").html(data.staff_absent[0].name);
            $("#report_date_details").html(data.report_date);
            //$("#abc").html('blah');


            if(time == "today") {

              displayMemberAbsenceToday(data);
              displayMemberLatenessToday(data);
              displayMemberProximityToday(data);

            } else {

              displayMemberAbsence(data);
              displayMemberLateness(data);
              displayMemberProximity(data);

            }
        }
    });
    //alert(report_date);
}

