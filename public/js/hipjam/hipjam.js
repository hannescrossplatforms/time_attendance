$(document).ready(function () {

    var generateHeatmap;
    var chartWidth = 900;
    var chartHeight = 500;
    $('#loadingDiv').hide();

    $(document).on('input', '#heatmaphourslider', function () {

        updateSliderChanges();
    });

    generateHeatmap = function (resultdata) {

        var c = document.getElementById("imgcanvas");
        var ctx = c.getContext("2d");
        var img = document.getElementById("myImg");
        c.width = img.width;
        c.height = img.height;
        ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, img.width, img.height);

        var xx = h337.create({ "element": document.getElementById("heatmapArea"), "visible": true });
        var el = JSON.stringify(resultdata);
        // alert("generateHeatmap : " + el);
        var obj = eval('(' + el + ')');
        xx.store.setDataSet(obj);
    }

    pathname = $('#url').val();
    venuename = $('#apivenuename').val();
    if (venuename == 'no_venue') {

        alert("Track Venue Id has not been provided. Please configure in Track->Venue Management");
        return false;
    }
    domainname = $('#apisitename').val();
    if (domainname == 'no_venue') {

        alert("Track Venue Id has not been provided. Please configure in Track->Venue Management");
        return false;
    }
    venueid = $('#apivenueid').val();

    //---------- now ----------
    // $.ajax({

    //     url: pathname + 'hipjam/chartJsondata',
    //     type: 'get',
    //     dataType: 'json',
    //     data: { 'period': 'now', 'scanner_type': 'internal', 'venue': venuename, 'domain': domainname },
    //     success: function (data) {
    //         now_data = data;
    //         Data1 = data;
    //         Data2 = Data1.total.total;
    //         $('#customer_now').html(Data2);
    //         $('#new_now').html(Data1.total.new);

    //     },
    //     error: function (error) {
    //         $('#customer_now').html('<span style="font-size: 35%;">Data not available</span>');
    //         $('#new_now').html('<span style="font-size: 35%;">Data not available</span>');
    //     }
    // });

    //---------- today ----------
    // $.ajax({

    //     url: pathname + 'hipjam/chartJsondata',
    //     type: 'get',
    //     dataType: 'json',
    //     data: { 'period': 'today', 'scanner_type': 'internal', 'venue': venuename, 'domain': domainname },
    //     success: function (data) {
    //         Data1 = data;
    //         Data2 = Data1.total.total;
    //         $('#customer_today').html(Data2);
    //         $('#new_today').html(Data1.total.new);
    //         $('#window_today').html(Data1.total.window_conversion + '%');

    //     },
    //     error: function (error) {
    //         $('#customer_today').html('<span style="font-size: 35%;">Data not available</span>');
    //         $('#new_today').html('<span style="font-size: 35%;">Data not available</span>');
    //     }
    // });

    // //---------- window conversion ----------
    // console.log("In window conversion 05");
    // if( $('#brandreportperiod').val() == 'daterange'){
    //     var s_start = $('#venuefrom').val();
    //     var s_end = $('#venueto').val();
    // }else{
    //     var s_start = '';
    //     var s_end = '';   
    // }
    // $.ajax({

    //     url: pathname+'hipjam/getWindowconversion',
    //     type: 'get',
    //     dataType: 'json',
    //     data : { 'period':$('#brandreportperiod').val(),'scanner_type':'internal','start':s_start,'end':s_end,'venue':venuename ,'domain':domainname },
    //     success: function(data) { 
    //         console.log("In window conversion 10");
    //         if(data){ 
    //             if(!("error" in data)){
    //                 if((data[1] != null) && (data[0] != null) && (data[1].total.total != 0)){
    //                     window_conversion = ((data[0].total.total/data[1].total.total)*100).toFixed(2)+'%';
    //                 }else {
    //                     window_conversion = '<span style="font-size: 35%;">Data not available</span>';
    //                 }
    //                 if((data[2] != null) && (data[0] != null) && (data[2].total.total != 0)){
    //                     window_conversion_select = ((data[0].total.total/data[2].total.total)*100).toFixed(2)+'%';
    //                     // window_conversion_select = data[0].window_conversion;
    //                     // window_conversion_select = 66;
    //                 }else {
    //                     window_conversion_select = '<span style="font-size: 35%;">Data not available</span>';
    //                 }
    //             } else {
    //                 window_conversion = '<span style="font-size: 35%;">Data not available</span>';
    //                 window_conversion_select = '<span style="font-size: 35%;">Data not available</span>';
    //             }
    //         } else {
    //             window_conversion = '<span style="font-size: 35%;">Data not available</span>';
    //             window_conversion_select = '<span style="font-size: 35%;">Data not available</span>';
    //         }
    //         // $('#window_today').html(window_conversion);
    //         // $('#window_con').html(window_conversion_select);


    //     },
    //     error: function (error) {
    //         window_conversion = '<span style="font-size: 35%;">Data not available</span>';
    //         window_conversion_select = '<span style="font-size: 35%;">Data not available</span>';
    //         $('#window_today').html(window_conversion);
    //         $('#window_con').html(window_conversion_select);
    //     }
    // });



    //---------- heatmap ----------
    if ($('#wsproximitytab').length !== 0) {
        document.getElementById("wsproximitytab").onclick = function () {
            $('#loadingDiv').show();
            var hour = 13;
            $.ajax({
                url: pathname + 'hipjam/heatmapJsondata',
                type: 'get',
                dataType: 'json',
                data: { 'hour': hour, 'period': 'today', 'scanner_type': 'internal', 'venue': venuename, 'domain': domainname, 'venue_id': venueid },
                success: function (resultdata) {
                    // generateHeatmap(resultdata); // For some reason I get an referenceerror with this
                    var c = document.getElementById("imgcanvas");
                    var ctx = c.getContext("2d");
                    var img = document.getElementById("myImg");
                    // alert("wsproximitytab img.height = " + img.height);
                    c.width = img.width;

                    c.height = img.height;
                    ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, img.width, img.height);
                    // c.width=900;
                    // c.height=500;
                    // ctx.drawImage(img,0,0,900,500,0,0,900,500);

                    var xx = h337.create({ "element": document.getElementById("heatmapArea"), "visible": true });
                    var el = JSON.stringify(resultdata);
                    $.removeCookie('current_heatmap_hours');
                    $.cookie("current_heatmap_hours", el);
                    // alert("generateHeatmap : " + el);
                    var obj = eval('(' + el + ')');
                    xx.store.setDataSet(obj);
                    $('#loadingDiv').hide();
                },
                error: function (returnval) {
                    $('#loadingDiv').hide();
                    alert("Error retrieving data");
                }
            });
        }
    }



    //---------- zonal tab ----------
    if ($('#zonaltab').length !== 0) {
        document.getElementById("zonaltab").onclick = function () {
            $.ajax({

                url: pathname + 'hipjam/zonalJsondata',
                type: 'get',
                data: { 'period': 'today', 'scanner_type': 'internal', 'venue': venuename, 'domain': domainname },
                success: function (resultdata) {

                    $("#zoneTable").html(resultdata);

                }
            });
        }

    }


    //---------- week ----------
    $('#rep_customer').html('loading...');
    $('#new_rep_customer').html('loading...');
    $('#engaged_customers').html('loading...');
    $('#rep_ave').html('loading...');
    $.ajax({

        url: pathname + 'hipjam/chartJsondata',
        type: 'get',
        dataType: 'json',
        data: { 'period': 'this_week', 'scanner_type': 'internal', 'venue': venuename, 'domain': domainname },
        success: function (data) {
            Data1 = data;
            Data2 = Data1.total.total;
            $('#new_week').html(Data1.total.new);
            //for onchange function
            week_data = data;

            $('#rep_customer').html(week_data.total.total);
            $('#new_rep_customer').html(week_data.total.new);
            $('#engaged_customers').html(week_data.total.engaged_customers);
            $('#rep_ave').html(week_data.total.average_session);
            $('#window_con').html(week_data.total.window_conversion + '%');

            $('#perHperiod').html('This Week');
            $('#storeTrfc').html('This Week');
            $('#storeTrfc').html('This Week');

            //----------------------- week visit  -------
            chartData_w1 = week_data.total.trends.dates;
            jsonObj_w = [];
            $.each(chartData_w1, function () {
                item_w = {};
                item_w["label"] = this.date;
                item_w["value"] = this.total;

                jsonObj_w.push(item_w);

            });
            var chartProperties = {
                //"caption": "STORE TRAFFIC THIS WEEK",
                "caption": "",
                "xAxisName": "Date",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'column2d',
                renderAt: 'date_week',
                width: '400',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "data": jsonObj_w

                }
            });
            apiChart.render();

            //------------------------overall ----------
            var chartProperties = {
                //"caption": "OVERALL STORE TRAFFIC TREND",
                "caption": "",
                "xAxisName": "Month",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'line',
                renderAt: 'chart-05',
                width: '900',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "data": jsonObj_w

                }
            });
            apiChart.render();

            //-----------------------overall traffic ( new/returning ) ----------
            chartData2 = week_data.total.trends.weekdays;
            jsonObj = [];
            jsonCat = [];

            jsonCat_s = {};
            jsonCat_s["category"] = [];

            jsonDat = [];

            item1 = {};
            item1["seriesname"] = "new";
            item1["data"] = [];

            data = [];

            $.each(chartData2, function () {
                item = {};
                item["label"] = this.weekday;

                jsonCat_s["category"].push(item);

                itemsnew = {};
                itemsnew["value"] = this.new;
                item1["data"].push(itemsnew);

                item2 = {};
                item2["value"] = this.total - this.new;
                data.push(item2);

            });
            jsonCat = jsonCat_s;

            jsonDat.push(item1);

            item_r = {};
            item_r["seriesname"] = "returning";
            item_r["data"] = data;
            jsonDat.push(item_r);

            var chartProperties = {
                //"caption": "OVERALL STORE TRAFFIC (NEW vs. RETURNING)",
                "caption": "",
                "xAxisName": "Day",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'msline',
                renderAt: 'chart-06',
                width: '900',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "categories": jsonCat,
                    "dataset": jsonDat

                }
            });
            apiChart.render();


            //------------------- store traffic /hour -----------
            chartData_t1 = week_data.total.trends.hours;
            jsonObj = [];
            $.each(chartData_t1, function () {
                item = {};
                item["label"] = this.hour;
                item["value"] = this.total;

                jsonObj.push(item);

            });
            var chartProperties = {
                //"caption": "STORE TRAFFIC/HOUR week",
                "caption": "",
                "xAxisName": "Hours",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'column2d',
                renderAt: 'chart-container',
                width: '400',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "data": jsonObj

                }
            });
            apiChart.render();

        },
        error: function (error) {
            $('#rep_customer').html('<span style="font-size: 35%;">Data not available</span>');
            $('#new_rep_customer').html('<span style="font-size: 35%;">Data not available</span>');
            $('#engaged_customers').html('<span style="font-size: 35%;">Data not available</span>');
            $('#rep_ave').html('<span style="font-size: 35%;">Data not available</span>');
        }
    });

    //---------- month ----------
    // $.ajax({

    //     url: pathname + 'hipjam/chartJsondata',
    //     type: 'get',
    //     dataType: 'json',
    //     data: { 'period': 'this_month', 'scanner_type': 'internal', 'venue': venuename, 'domain': domainname },
    //     success: function (data) {
    //         debugger;
    //         month_data = data;
    //         // alert(month_data);

    //     },
    //     error: function (xhr, msg) {
    //         debugger;
    //     }
    // });

    //---------- last month ----------
    // $.ajax({

    //     // url: pathname+'hipjam/customchartJsondata',
    //     url: pathname + 'hipjam/chartJsondata',
    //     type: 'get',
    //     dataType: 'json',
    //     /*data : { 'period':'custom','scanner_type':'internal','start':'2016-06-01','end':'2016-06-30','venue':venuename },*/
    //     data: { 'period': 'last_month', 'scanner_type': 'internal', 'venue': venuename, 'domain': domainname },
    //     success: function (data) {

    //         pre_month_data = data;

    //     }
    // });




});

function drawCoordinates(x, y) {
    var pointSize = 3; // Change according to the size of the point.
    var ctx = document.getElementById("imgcanvas").getContext("2d");

    /*var c = document.getElementById("img01");
    ctx.clearRect(0,0,c.width,c.height);*/
    ctx.fillStyle = "#ff2626"; // Red color

    ctx.beginPath(); //Start path
    ctx.arc(x, y, pointSize, 0, Math.PI * 2, true); // Draw a point using the arc function of the canvas with a point structure.
    ctx.fill(); // Close the path and fill.
}

function change_zonal_report_period() {

    var period = $("#zonalreportperiod").val();
    if (period != 'daterange') {
        $('#zonecustom').hide();
        $.ajax({

            url: pathname + 'hipjam/zonalJsondata',
            type: 'get',
            data: { 'period': period, 'scanner_type': 'internal', 'venue': venuename, 'start': '', 'end': '', 'domain': domainname },
            success: function (resultdata) {

                $("#zoneTable").html(resultdata);

            }
        });
    } else {
        $('#zonecustom').show();
    }

}

function custom_zonal_report_period() {

    var from = $('#zonalfrom').val();
    var to = $('#zonalto').val();
    if (from == '' || to == '') {
        alert("Enter Range");
        return false;
    }

    $.ajax({

        url: pathname + 'hipjam/zonalJsondata',
        type: 'get',
        data: { 'period': 'custom', 'scanner_type': 'internal', 'venue': venuename, 'start': from, 'end': to, 'domain': domainname },
        success: function (resultdata) {

            $("#zoneTable").html(resultdata);

        }
    });
}
document.getElementById("heatmapreportperiod").onchange = function () {
    $('#loadingDiv').show();
    var period = $("#heatmapreportperiod").val();
    var slider = $("#heatmaphourslider");
    var hour = slider.val();
    if (period != 'daterange') {
        $('#heatmapcustom').hide();
        $.ajax({

            url: pathname + 'hipjam/heatmapJsondata',
            type: 'get',
            data: { 'hour': hour, 'period': period, 'scanner_type': 'internal', 'venue': venuename, 'start': '', 'end': '', 'domain': domainname, 'venue_id': venueid },

            success: function (resultdata) {
                // generateHeatmap(resultdata); // For some reason I get an referenceerror with this
                var c = document.getElementById("imgcanvas");
                var ctx = c.getContext("2d");

                var img = document.getElementById("myImg");
                c.width = img.width;
                c.height = img.height;
                $("canvas:not(#imgcanvas)")[0].remove()
                ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, img.width, img.height);

                var xx = h337.create({ "element": document.getElementById("heatmapArea"), "visible": true });

                var el = JSON.stringify(resultdata);
                $.removeCookie('current_heatmap_hours');
                $.cookie("current_heatmap_hours", el);
                // alert("generateHeatmap : " + el);
                var obj = eval('(' + el + ')');
                xx.store.setDataSet(obj);

                $('#loadingDiv').hide();
            },
            error: function (returnval) {
                $('#loadingDiv').hide();
                alert("Error retrieving data");
            }
        });
    } else {
        $('#heatmapcustom').show();
    }

    $('#loadingDiv').hide();
}
//function change_heatmap_report_period(){
document.getElementById("heatmapreportperiod").onchange = function () { //Commented_out

    $('#loadingDiv').show();
    var period = $("#heatmapreportperiod").val();
    if (period != 'daterange') {
        $('#heatmapcustom').hide();
        $.ajax({

            url: pathname + 'hipjam/heatmapJsondata',
            type: 'get',
            data: { 'period': period, 'scanner_type': 'internal', 'venue': venuename, 'start': '', 'end': '', 'domain': domainname, 'venue_id': venueid },

            success: function (resultdata) {
                // generateHeatmap(resultdata); // For some reason I get an referenceerror with this
                var c = document.getElementById("imgcanvas");
                var ctx = c.getContext("2d");
                var img = document.getElementById("myImg");
                c.width = img.width;
                c.height = img.height;
                ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, img.width, img.height);

                var xx = h337.create({ "element": document.getElementById("heatmapArea"), "visible": true });
                var el = JSON.stringify(resultdata);
                // alert("generateHeatmap : " + el);
                var obj = eval('(' + el + ')');
                xx.store.setDataSet(obj);

                $('#loadingDiv').hide();
            },
            error: function (returnval) {
                $('#loadingDiv').hide();
                alert("Error retrieving data");
            }
        });
    } else {
        $('#heatmapcustom').show();
    }

};

function custom_heatmap_report_period() {

    var from = $('#heatmapfrom').val();
    var to = $('#heatmapto').val();
    if (from == '' || to == '') {
        alert("Enter Range");
        return false;
    }

    $.ajax({

        url: pathname + 'hipjam/heatmapJsondata',
        type: 'get',
        data: { 'period': 'custom', 'scanner_type': 'internal', 'venue': venuename, 'start': from, 'end': to, 'domain': domainname, 'venue_id': venueid },
        success: function (resultdata) {
            console.log(resultdata['data'][0]);
            var c = document.getElementById("imgcanvas");
            var ctx = c.getContext("2d");
            var img = document.getElementById("myImg");
            c.width = img.width;
            c.height = img.height;
            ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, img.width, img.height);

            $.each(resultdata.cordinates, function (arrayID, group) {
                drawCoordinates(group.xcoord, group.ycoord);
            });

            var xx = h337.create({ "element": document.getElementById("heatmapArea"), "radius": 25, "visible": true });

            //var el =  "{max: 100, data: [{x:800,y:100,count:100},{x:290,y:80,count:20},{x:30,y:580,count:80},{x:10,y:100,count:10},{x:40,y:70,count:60},{x:90,y:10,count:40},{x:50,y:100,count:70},{x:20,y:70,count:30},{x:60,y:50,count:15},{x:90,y:40,count:20}]}";  
            //var el =  "{max: 100, data: "+resultdata+"}";
            //var el =  "{max: "+resultdata.split('&')[1]+", data: "+resultdata.split('&')[0]+"}";
            var el = resultdata.heatmap;

            var obj = eval('(' + el + ')');
            // call the heatmap's store's setDataSet method in order to set static data
            xx.store.setDataSet(obj);

        }
    });
}
function updateSliderChanges() {

    var c = document.getElementById("imgcanvas");
    var ctx = c.getContext("2d");

    //$("canvas:not(#imgcanvas)")[0].remove();
    var period = $("#heatmapreportperiod").val();
    var slider = $("#heatmaphourslider");
    $("#heatmaphour").val(slider.val() + ":00");
    var img = document.getElementById("myImg");

    c.width = img.width;
    c.height = img.height;
    ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, img.width, img.height);

    $.ajax({

        url: pathname + 'hipjam/heatmapJsondata',
        type: 'get',
        data: { 'hour': slider.val(), 'period': period, 'scanner_type': 'internal', 'venue': venuename, 'start': '', 'end': '', 'domain': domainname, 'venue_id': venueid },

        success: function (resultdata) {
            // generateHeatmap(resultdata); // For some reason I get an referenceerror with this
            var c = document.getElementById("imgcanvas");
            var ctx = c.getContext("2d");

            var img = document.getElementById("myImg");
            c.width = img.width;
            c.height = img.height;
            $("canvas:not(#imgcanvas)")[0].remove()
            ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, img.width, img.height);

            var xx = h337.create({ "element": document.getElementById("heatmapArea"), "visible": true });

            var el = JSON.stringify(resultdata);
            // alert("generateHeatmap : " + el);
            var obj = eval('(' + el + ')');
            xx.store.setDataSet(obj);

            $('#loadingDiv').hide();
        },
        error: function (returnval) {
            $('#loadingDiv').hide();
            alert("Error retrieving data");
        }
    });

}

// function change_report_period() {
//     var time = $("#brandreportperiod").val();
//     if (time != 'daterange') {
//         $('#rep_customer').html('loading...');
//         $('#new_rep_customer').html('loading...');
//         $('#engaged_customers').html('loading...');
//         $('#rep_ave').html('loading...');
//         $('#window_con').html('loading...');
//     }

//     if (time == 'rep7day') {
//         $('#rep_customer').html(week_data.total.total);
//         $('#new_rep_customer').html(week_data.total.new);
//         $('#engaged_customers').html(week_data.total.engaged_customers);
//         $('#rep_ave').html(week_data.total.average_session);
//         $('#custom').hide();
//         // window_conversion_select = ((now_data.total.total/week_data.total.total)*100).toFixed(2);
//         $('#window_con').html(week_data.total.window_conversion + '%');

//         store_traffic('rep7day')

//         //$('#rep_max').html();
//     } else if (time == 'repthismonth') {

//         $('#rep_customer').html(month_data.total.total);
//         $('#new_rep_customer').html(month_data.total.new);
//         $('#engaged_customers').html(month_data.total.engaged_customers);
//         $('#custom').hide();
//         $('#rep_ave').html(month_data.total.average_session);
//         // window_conversion_select = ((now_data.total.total/month_data.total.total)*100).toFixed(2);
//         // $('#window_con').html(window_conversion_select+'%');
//         $('#window_con').html(month_data.total.window_conversion + '%');

//         store_traffic('repthismonth')

//         // $('#rep_customer').html(month_data.total.total);
//         // $('#new_rep_customer').html(month_data.total.new);
//         // $('#engaged_customers').html(month_data.total.engaged_customers);
//         // $('#custom').hide();
//         // $('#rep_ave').html(month_data.total.average_session);
//         // // window_conversion_select = ((now_data.total.total/month_data.total.total)*100).toFixed(2);
//         // // $('#window_con').html(window_conversion_select+'%');
//         // $('#window_con').html(month_data.total.window_conversion + '%');

//         // store_traffic('repthismonth')
//         //$('#rep_max').html();
//     } else if (time == 'replastmonth') {
//         $('#rep_customer').html(pre_month_data.total.total);
//         $('#new_rep_customer').html(pre_month_data.total.new);
//         $('#engaged_customers').html(pre_month_data.total.engaged_customers);
//         $('#custom').hide();
//         $('#rep_ave').html(pre_month_data.total.average_session);
//         // window_conversion_select = ((now_data.total.total/pre_month_data.total.total)*100).toFixed(2);
//         // $('#window_con').html(window_conversion_select+'%');
//         $('#window_con').html(pre_month_data.total.window_conversion + '%');

//         store_traffic('replastmonth')
//         //$('#rep_max').html();       
//     } else if (time == 'daterange') {
//         $('#custom').show();

//     } else if (time == '') {
//         alert('Please Select');
//     } else {
//         console.info('Invalid time')
//     }
// }

function custom_report_period() {
    var from = $('#venuefrom').val();
    var to = $('#venueto').val();
    if (from == '' || to == '') {
        alert("Enter Range");
        return false;
    }


    $('#rep_customer').html('loading...');
    $('#new_rep_customer').html('loading...');
    $('#engaged_customers').html('loading...');
    $('#rep_ave').html('loading...');
    $('#window_con').html('loading...');

    $.ajax({

        url: pathname + 'hipjam/customchartJsondata',
        type: 'get',
        dataType: 'json',
        data: { 'period': 'custom', 'scanner_type': 'internal', 'start': from, 'end': to, 'venue': venuename, 'domain': domainname },
        success: function (data) {


            $('#rep_customer').html(data.total.total);
            $('#new_rep_customer').html(data.total.new);
            $('#engaged_customers').html(data.total.engaged_customers);
            $('#rep_ave').html(data.total.average_session);
            $('#window_con').html(data.total.window_conversion + '%');


            $('#perHperiod').html('From ' + from + ' To ' + to + ' ');
            $('#storeTrfc').html('From ' + from + ' To ' + to + ' ');
            $('#storeTrfc').html('From ' + from + ' To ' + to + ' ');
            //----------------------- week visit  -------
            chartData_w1 = data.total.trends.dates;
            jsonObj_w = [];
            $.each(chartData_w1, function () {
                item_w = {};
                item_w["label"] = this.date;
                item_w["value"] = this.total;

                jsonObj_w.push(item_w);

            });
            var chartProperties = {
                //"caption": "STORE TRAFFIC THIS WEEK",
                "caption": "",
                "xAxisName": "Date",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'column2d',
                renderAt: 'date_week',
                width: '400',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "data": jsonObj_w

                }
            });
            apiChart.render();

            //------------------------overall ----------
            var chartProperties = {
                //"caption": "OVERALL STORE TRAFFIC TREND",
                "caption": "",
                "xAxisName": "Date",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'line',
                renderAt: 'chart-05',
                width: '900',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "data": jsonObj_w

                }
            });
            apiChart.render();

            //-----------------------overall traffic ( new/returning ) ----------
            chartData2 = data.total.trends.dates;
            jsonObj = [];
            jsonCat = [];

            jsonCat_s = {};
            jsonCat_s["category"] = [];

            jsonDat = [];

            item1 = {};
            item1["seriesname"] = "new";
            item1["data"] = [];

            data = [];

            $.each(chartData2, function () {
                item = {};
                item["label"] = this.date;

                jsonCat_s["category"].push(item);

                itemsnew = {};
                itemsnew["value"] = this.new;
                item1["data"].push(itemsnew);

                item2 = {};
                item2["value"] = this.total - this.new;
                data.push(item2);

            });
            jsonCat = jsonCat_s;

            jsonDat.push(item1);

            item_r = {};
            item_r["seriesname"] = "returning";
            item_r["data"] = data;
            jsonDat.push(item_r);

            var chartProperties = {
                //"caption": "OVERALL STORE TRAFFIC (NEW vs. RETURNING)",
                "caption": "",
                "xAxisName": "Date",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'msline',
                renderAt: 'chart-06',
                width: '900',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "categories": jsonCat,
                    "dataset": jsonDat

                }
            });
            apiChart.render();

            //------------------- store traffic /hour -----------
            debugger;
            chartData_t1 = data.total.trends.hours;
            jsonObj = [];
            $.each(chartData_t1, function () {
                item = {};
                item["label"] = this.hour;
                item["value"] = this.total;

                jsonObj.push(item);

            });
            var chartProperties = {
                //"caption": "STORE TRAFFIC/HOUR week",
                "caption": "",
                "xAxisName": "Hours",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'column2d',
                renderAt: 'chart-container',
                width: '400',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "data": jsonObj

                }
            });
            apiChart.render();




        }
    });
}

// function store_traffic(period) {
//     // alert("in store_traffic");


//     if (period == 'rep7day') {
//         debugger;

//         $('#perHperiod').html('This Week');
//         $('#storeTrfc').html('This Week');
//         $('#storeTrfc').html('This Week');

//         //----------------------- week visit  -------
//         chartData_w1 = week_data.total.trends.dates;
//         jsonObj_w = [];
//         $.each(chartData_w1, function () {
//             item_w = {};
//             item_w["label"] = this.date;
//             item_w["value"] = this.total;

//             jsonObj_w.push(item_w);

//         });
//         var chartProperties = {
//             //"caption": "STORE TRAFFIC THIS WEEK",
//             "caption": "",
//             "xAxisName": "Date",
//             "yAxisName": "Customers",
//             "rotatevalues": "1",
//             "theme": "zune"
//         };

//         apiChart = new FusionCharts({
//             type: 'column2d',
//             renderAt: 'date_week',
//             width: '400',
//             height: '350',
//             dataFormat: 'json',
//             dataSource: {
//                 "chart": chartProperties,
//                 "data": jsonObj_w

//             }
//         });
//         apiChart.render();

//         //------------------------overall ----------
//         var chartProperties = {
//             //"caption": "OVERALL STORE TRAFFIC TREND",
//             "caption": "",
//             "xAxisName": "Month",
//             "yAxisName": "Customers",
//             "rotatevalues": "1",
//             "theme": "zune"
//         };

//         apiChart = new FusionCharts({
//             type: 'line',
//             renderAt: 'chart-05',
//             width: '900',
//             height: '350',
//             dataFormat: 'json',
//             dataSource: {
//                 "chart": chartProperties,
//                 "data": jsonObj_w

//             }
//         });
//         apiChart.render();

//         //-----------------------overall traffic ( new/returning ) ----------
//         chartData2 = week_data.total.trends.weekdays;
//         jsonObj = [];
//         jsonCat = [];

//         jsonCat_s = {};
//         jsonCat_s["category"] = [];

//         jsonDat = [];

//         item1 = {};
//         item1["seriesname"] = "new";
//         item1["data"] = [];

//         data = [];

//         $.each(chartData2, function () {
//             item = {};
//             item["label"] = this.weekday;

//             jsonCat_s["category"].push(item);

//             itemsnew = {};
//             itemsnew["value"] = this.new;
//             item1["data"].push(itemsnew);

//             item2 = {};
//             item2["value"] = this.total - this.new;
//             data.push(item2);

//         });
//         jsonCat = jsonCat_s;

//         jsonDat.push(item1);

//         item_r = {};
//         item_r["seriesname"] = "returning";
//         item_r["data"] = data;
//         jsonDat.push(item_r);

//         var chartProperties = {
//             //"caption": "OVERALL STORE TRAFFIC (NEW vs. RETURNING)",
//             "caption": "",
//             "xAxisName": "Day",
//             "yAxisName": "Customers",
//             "rotatevalues": "1",
//             "theme": "zune"
//         };

//         apiChart = new FusionCharts({
//             type: 'msline',
//             renderAt: 'chart-06',
//             width: '900',
//             height: '350',
//             dataFormat: 'json',
//             dataSource: {
//                 "chart": chartProperties,
//                 "categories": jsonCat,
//                 "dataset": jsonDat

//             }
//         });
//         apiChart.render();


//         //------------------- store traffic /hour -----------
//         chartData_t1 = week_data.total.trends.hours;
//         jsonObj = [];
//         $.each(chartData_t1, function () {
//             item = {};
//             item["label"] = this.hour;
//             item["value"] = this.total;

//             jsonObj.push(item);

//         });
//         var chartProperties = {
//             //"caption": "STORE TRAFFIC/HOUR week",
//             "caption": "",
//             "xAxisName": "Hours",
//             "yAxisName": "Customers",
//             "rotatevalues": "1",
//             "theme": "zune"
//         };

//         apiChart = new FusionCharts({
//             type: 'column2d',
//             renderAt: 'chart-container',
//             width: '400',
//             height: '350',
//             dataFormat: 'json',
//             dataSource: {
//                 "chart": chartProperties,
//                 "data": jsonObj

//             }
//         });
//         apiChart.render();

//     } else if (period == 'repthismonth') {

//         // alert("in thismonth");

//         $('#perHperiod').html('This Month');
//         $('#storeTrfc').html('This Month');
//         $('#storeTrfc').html('This Month');
//         //----------------------- week visit  -------
//         chartData_w1 = month_data.total.trends.dates;
//         jsonObj_w = [];
//         $.each(chartData_w1, function () {
//             item_w = {};
//             item_w["label"] = this.date;
//             // alert(this.date);
//             item_w["value"] = this.total;

//             jsonObj_w.push(item_w);

//         });
//         var chartProperties = {
//             //"caption": "STORE TRAFFIC THIS WEEK",
//             "caption": "",
//             "xAxisName": "Date",
//             "yAxisName": "Customers",
//             "rotatevalues": "1",
//             "theme": "zune"
//         };

//         apiChart = new FusionCharts({
//             type: 'column2d',
//             renderAt: 'date_week',
//             width: '400',
//             height: '350',
//             dataFormat: 'json',
//             dataSource: {
//                 "chart": chartProperties,
//                 "data": jsonObj_w

//             }
//         });
//         apiChart.render();

//         //------------------------overall ----------
//         var chartProperties = {
//             //"caption": "OVERALL STORE TRAFFIC TREND",
//             "caption": "",
//             "xAxisName": "Month",
//             "yAxisName": "Customers",
//             "rotatevalues": "1",
//             "theme": "zune"
//         };

//         apiChart = new FusionCharts({
//             type: 'line',
//             renderAt: 'chart-05',
//             width: '900',
//             height: '350',
//             dataFormat: 'json',
//             dataSource: {
//                 "chart": chartProperties,
//                 "data": jsonObj_w

//             }
//         });
//         apiChart.render();

//         //-----------------------overall traffic ( new/returning ) ----------
//         chartData2 = month_data.total.trends.dates;
//         jsonObj = [];
//         jsonCat = [];

//         jsonCat_s = {};
//         jsonCat_s["category"] = [];

//         jsonDat = [];

//         item1 = {};
//         item1["seriesname"] = "new";
//         item1["data"] = [];

//         data = [];

//         $.each(chartData2, function () {
//             item = {};
//             item["label"] = this.date;

//             jsonCat_s["category"].push(item);

//             itemsnew = {};
//             itemsnew["value"] = this.new;
//             item1["data"].push(itemsnew);

//             item2 = {};
//             item2["value"] = this.total - this.new;
//             data.push(item2);

//         });
//         jsonCat = jsonCat_s;

//         jsonDat.push(item1);

//         item_r = {};
//         item_r["seriesname"] = "returning";
//         item_r["data"] = data;
//         jsonDat.push(item_r);

//         var chartProperties = {
//             //"caption": "OVERALL STORE TRAFFIC (NEW vs. RETURNING)",
//             "caption": "",
//             "xAxisName": "Date",
//             "yAxisName": "Customers",
//             "rotatevalues": "1",
//             "theme": "zune"
//         };

//         apiChart = new FusionCharts({
//             type: 'msline',
//             renderAt: 'chart-06',
//             width: '900',
//             height: '350',
//             dataFormat: 'json',
//             dataSource: {
//                 "chart": chartProperties,
//                 "categories": jsonCat,
//                 "dataset": jsonDat

//             }
//         });
//         apiChart.render();

//         //------------------- store traffic /hour -----------
//         chartData_t1 = month_data.total.trends.hours;
//         jsonObj = [];
//         $.each(chartData_t1, function () {
//             item = {};
//             item["label"] = this.hour;
//             item["value"] = this.total;

//             jsonObj.push(item);

//         });
//         var chartProperties = {
//             //"caption": "STORE TRAFFIC/HOUR week",
//             "caption": "",
//             "xAxisName": "Hours",
//             "yAxisName": "Customers",
//             "rotatevalues": "1",
//             "theme": "zune"
//         };

//         apiChart = new FusionCharts({
//             type: 'column2d',
//             renderAt: 'chart-container',
//             width: '400',
//             height: '350',
//             dataFormat: 'json',
//             dataSource: {
//                 "chart": chartProperties,
//                 "data": jsonObj

//             }
//         });
//         apiChart.render();

//     } else if (period == 'replastmonth') {

//         $('#perHperiod').html('Last Month');
//         $('#storeTrfc').html('Last Month');
//         $('#storeTrfc').html('Last Month');

//         //----------------------- week visit  -------
//         chartData_w1 = pre_month_data.total.trends.dates;
//         jsonObj_w = [];
//         $.each(chartData_w1, function () {
//             item_w = {};
//             item_w["label"] = this.date;
//             item_w["value"] = this.total;

//             jsonObj_w.push(item_w);

//         });
//         var chartProperties = {
//             //"caption": "STORE TRAFFIC THIS WEEK",
//             "caption": "",
//             "xAxisName": "Date",
//             "yAxisName": "Customers",
//             "rotatevalues": "1",
//             "theme": "zune"
//         };

//         apiChart = new FusionCharts({
//             type: 'column2d',
//             renderAt: 'date_week',
//             width: '400',
//             height: '350',
//             dataFormat: 'json',
//             dataSource: {
//                 "chart": chartProperties,
//                 "data": jsonObj_w

//             }
//         });
//         apiChart.render();

//         //------------------------overall ----------
//         var chartProperties = {
//             //"caption": "OVERALL STORE TRAFFIC TREND",
//             "caption": "",
//             "xAxisName": "Month",
//             "yAxisName": "Customers",
//             "rotatevalues": "1",
//             "theme": "zune"
//         };

//         apiChart = new FusionCharts({
//             type: 'line',
//             renderAt: 'chart-05',
//             width: '900',
//             height: '350',
//             dataFormat: 'json',
//             dataSource: {
//                 "chart": chartProperties,
//                 "data": jsonObj_w

//             }
//         });
//         apiChart.render();

//         //-----------------------overall traffic ( new/returning ) ----------
//         chartData2 = pre_month_data.total.trends.dates;
//         jsonObj = [];
//         jsonCat = [];

//         jsonCat_s = {};
//         jsonCat_s["category"] = [];

//         jsonDat = [];

//         item1 = {};
//         item1["seriesname"] = "new";
//         item1["data"] = [];

//         data = [];

//         $.each(chartData2, function () {
//             item = {};
//             item["label"] = this.date;

//             jsonCat_s["category"].push(item);

//             itemsnew = {};
//             itemsnew["value"] = this.new;
//             item1["data"].push(itemsnew);

//             item2 = {};
//             item2["value"] = this.total - this.new;
//             data.push(item2);

//         });
//         jsonCat = jsonCat_s;

//         jsonDat.push(item1);

//         item_r = {};
//         item_r["seriesname"] = "returning";
//         item_r["data"] = data;
//         jsonDat.push(item_r);

//         var chartProperties = {
//             //"caption": "OVERALL STORE TRAFFIC (NEW vs. RETURNING)",
//             "caption": "",
//             "xAxisName": "Date",
//             "yAxisName": "Customers",
//             "rotatevalues": "1",
//             "theme": "zune"
//         };

//         apiChart = new FusionCharts({
//             type: 'msline',
//             renderAt: 'chart-06',
//             width: '900',
//             height: '350',
//             dataFormat: 'json',
//             dataSource: {
//                 "chart": chartProperties,
//                 "categories": jsonCat,
//                 "dataset": jsonDat

//             }
//         });
//         apiChart.render();


//         //------------------- store traffic /hour -----------
//         chartData_t1 = pre_month_data.total.trends.hours;
//         jsonObj = [];
//         $.each(chartData_t1, function () {
//             item = {};
//             item["label"] = this.hour;
//             item["value"] = this.total;

//             jsonObj.push(item);

//         });
//         var chartProperties = {
//             //"caption": "STORE TRAFFIC/HOUR week",
//             "caption": "",
//             "xAxisName": "Hours",
//             "yAxisName": "Customers",
//             "rotatevalues": "1",
//             "theme": "zune"
//         };

//         apiChart = new FusionCharts({
//             type: 'column2d',
//             renderAt: 'chart-container',
//             width: '400',
//             height: '350',
//             dataFormat: 'json',
//             dataSource: {
//                 "chart": chartProperties,
//                 "data": jsonObj

//             }
//         });
//         apiChart.render();
//     }


//     var chartProperties = {
//         //"caption": "STORE TRAFFIC/HOUR TODAY",
//         "caption": "",
//         "xAxisName": "Hours",
//         "yAxisName": "Customers",
//         "rotatevalues": "1",
//         "theme": "zune"
//     };

//     apiChart = new FusionCharts({
//         type: 'column2d',
//         renderAt: 'chart-container',
//         width: '400',
//         height: '350',
//         dataFormat: 'json',
//         dataSource: {
//             "chart": chartProperties,
//             "data": jsonObj

//         }
//     });
//     apiChart.render();
// }

function getMax(arr, prop) {
    var max;
    for (var i = 0; i < arr.length; i++) {
        if (!max || parseInt(arr[i][prop]) > parseInt(max[prop]))
            max = arr[i];
    }
    return max;
}

// -------------------------------------------------------
// Matthew Barnard Changes
// -------------------------------------------------------

const root = $('#url').val();
const venue_name = $('#apivenuename').val();
const domain_name = $('#apisitename').val();

$( document ).ready(function() {
initializeInterface();
retrieveServerData('this_week');
});

$(document).on('change', '#brandreportperiod', function () {
    let report_period = $(this).val();
    if (report_period !== 'custom')
        setInterfaceToLoading();
    retrieveServerData(report_period);
})

function retrieveServerData(period) {
    $.get(`${root}hipjam/chartJsondata`, { 'period': period, 'scanner_type': 'internal', 'venue': venue_name, 'domain': domain_name }, function (data) {
        setInterfaceToData(JSON.parse(data));
        populateChartData(JSON.parse(data));
    })
}

function initializeInterface() {
    $.get(`${root}hipjam/chartJsondata`, { 'period': 'now', 'scanner_type': 'internal', 'venue': venue_name, 'domain': domain_name }, function (data) {
        let JSONData = JSON.parse(data);
        $('#customer_now').html(JSONData.total.total);
        $('#new_now').html(JSONData.total.new);
    });

    $.get(`${root}hipjam/chartJsondata`, { 'period': 'today', 'scanner_type': 'internal', 'venue': venue_name, 'domain': domain_name }, function (data) {
        let JSONData = JSON.parse(data);
        $('#customer_today').html(JSONData.total.total);
        $('#new_today').html(JSONData.total.new);
        $('#window_today').html(JSONData.total.window_conversion + '%');
    });
}

function populateChartData(JSONdata) {

    $('#perHperiod').html('This Month');
    $('#storeTrfc').html('This Month');
    $('#storeTrfc').html('This Month');

    //----------------------- week visit  -------
    chartData_w1 = JSONdata.total.trends.dates;
    jsonObj_w = [];
    $.each(chartData_w1, function () {
        item_w = {};
        item_w["label"] = this.date;
        item_w["value"] = this.total;

        jsonObj_w.push(item_w);

    });
    var chartProperties = {
        //"caption": "STORE TRAFFIC THIS WEEK",
        "caption": "",
        "xAxisName": "Date",
        "yAxisName": "Customers",
        "rotatevalues": "1",
        "theme": "zune"
    };

    apiChart = new FusionCharts({
        type: 'column2d',
        renderAt: 'date_week',
        width: '400',
        height: '350',
        dataFormat: 'json',
        dataSource: {
            "chart": chartProperties,
            "data": jsonObj_w

        }
    });
    apiChart.render();

    //------------------------overall ----------
    var chartProperties = {
        //"caption": "OVERALL STORE TRAFFIC TREND",
        "caption": "",
        "xAxisName": "Month",
        "yAxisName": "Customers",
        "rotatevalues": "1",
        "theme": "zune"
    };

    apiChart = new FusionCharts({
        type: 'line',
        renderAt: 'chart-05',
        width: '900',
        height: '350',
        dataFormat: 'json',
        dataSource: {
            "chart": chartProperties,
            "data": jsonObj_w

        }
    });
    apiChart.render();

    //-----------------------overall traffic ( new/returning ) ----------
    chartData2 = JSONdata.total.trends.weekdays;
    jsonObj = [];
    jsonCat = [];

    jsonCat_s = {};
    jsonCat_s["category"] = [];

    jsonDat = [];

    item1 = {};
    item1["seriesname"] = "new";
    item1["data"] = [];

    data = [];

    $.each(chartData2, function () {
        item = {};
        item["label"] = this.weekday;

        jsonCat_s["category"].push(item);

        itemsnew = {};
        itemsnew["value"] = this.new;
        item1["data"].push(itemsnew);

        item2 = {};
        item2["value"] = this.total - this.new;
        data.push(item2);

    });
    jsonCat = jsonCat_s;

    jsonDat.push(item1);

    item_r = {};
    item_r["seriesname"] = "returning";
    item_r["data"] = data;
    jsonDat.push(item_r);

    var chartProperties = {
        //"caption": "OVERALL STORE TRAFFIC (NEW vs. RETURNING)",
        "caption": "",
        "xAxisName": "Day",
        "yAxisName": "Customers",
        "rotatevalues": "1",
        "theme": "zune"
    };

    apiChart = new FusionCharts({
        type: 'msline',
        renderAt: 'chart-06',
        width: '900',
        height: '350',
        dataFormat: 'json',
        dataSource: {
            "chart": chartProperties,
            "categories": jsonCat,
            "dataset": jsonDat

        }
    });
    apiChart.render();


    //------------------- store traffic /hour -----------

    chartData_t1 = JSONdata.total.trends.hours;
    jsonObj = [];
    $.each(chartData_t1, function () {
        item = {};
        item["label"] = this.hour;
        item["value"] = this.total;

        jsonObj.push(item);

    });
    var chartProperties = {
        //"caption": "STORE TRAFFIC/HOUR week",
        "caption": "",
        "xAxisName": "Hours",
        "yAxisName": "Customers",
        "rotatevalues": "1",
        "theme": "zune"
    };

    apiChart = new FusionCharts({
        type: 'column2d',
        renderAt: 'chart-container',
        width: '400',
        height: '350',
        dataFormat: 'json',
        dataSource: {
            "chart": chartProperties,
            "data": jsonObj

        }
    });
    apiChart.render();
}

function setInterfaceToData(data) {
    $('#rep_customer').html(data.total.total);
    $('#new_rep_customer').html(data.total.new);
    $('#engaged_customers').html(data.total.engaged_customers || 0);
    $('#custom').hide();
    $('#rep_ave').html(data.total.average_session);
    $('#window_con').html(data.total.window_conversion + '%');

    // store_traffic('repthismonth')
}

function setInterfaceToLoading() {
    $('#rep_customer').html('loading...');
    $('#new_rep_customer').html('loading...');
    $('#engaged_customers').html('loading...');
    $('#rep_ave').html('loading...');
    $('#window_con').html('loading...');
}
