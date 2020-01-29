$( document ).ready(function() {

    var generateHeatmap;
    var chartWidth = 900;
    var chartHeight = 500;
    $('#loadingDiv').hide();

    $(document).on('input', '#heatmaphourslider', function() {

        updateSliderChanges();
    });

    generateHeatmap = function (resultdata) {

        var c = document.getElementById("imgcanvas");
        var ctx = c.getContext("2d");
        var img = document.getElementById("myImg");
        c.width=img.width;
        c.height=img.height;
        ctx.drawImage(img,0,0,img.width,img.height,0,0,img.width,img.height);

        var xx = h337.create({"element":document.getElementById("heatmapArea"),"visible":true});
        var el =  JSON.stringify(resultdata);
        // alert("generateHeatmap : " + el);
        var obj = eval('('+el+')');
        xx.store.setDataSet(obj);
    }

    pathname = $('#url').val();
    venuename = $('#apivenuename').val();
    if(venuename == 'no_venue'){

        alert("Track Venue Id has not been provided. Please configure in Track->Venue Management");
        return false;
    }
    domainname = $('#apisitename').val();
    if(domainname == 'no_venue'){

        alert("Track Venue Id has not been provided. Please configure in Track->Venue Management");
        return false;
    }
    venueid = $('#apivenueid').val();


    //---------- now ----------
    $.ajax({

        url: pathname+'hipjam/chartJsondata',
        type: 'get',
        dataType: 'json',
        data : { 'period':'now','scanner_type':'internal','venue':venuename ,'domain':domainname },
        success: function(data) {
        
            now_data = data;
            Data1 = data;
            Data2 = Data1.total.total;
            $('#customer_now').html(Data2);
            $('#new_now').html(Data1.total.new);

        },
        error: function (error) {
            $('#customer_now').html('<span style="font-size: 35%;">Data not available</span>');
            $('#new_now').html('<span style="font-size: 35%;">Data not available</span>');
        }
    });

    //---------- today ----------
    $.ajax({

        url: pathname+'hipjam/chartJsondata',
        type: 'get',
        dataType: 'json',
        data : { 'period':'today','scanner_type':'internal','venue':venuename ,'domain':domainname },
        success: function(data) {
            Data1 = data;
            Data2 = Data1.total.total;
            $('#customer_today').html(Data2);
            $('#new_today').html(Data1.total.new);
            $('#window_today').html(Data1.total.window_conversion + '%');

        },
        error: function (error) {
            $('#customer_today').html('<span style="font-size: 35%;">Data not available</span>');
            $('#new_today').html('<span style="font-size: 35%;">Data not available</span>');
        }
    });

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
    $(document).on('click', '#wsproximitytab', function() {
        $('#loadingDiv').show();
        var hour = 13;
        $.ajax({
            url: pathname+'hipjam/heatmapJsondata',
            type: 'get',
            dataType: 'json',
            data : {'hour':hour, 'period':'today','scanner_type':'internal','venue':venuename ,'domain':domainname,'venue_id':venueid },
            success: function(resultdata) {
                // generateHeatmap(resultdata); // For some reason I get an referenceerror with this
                var c = document.getElementById("imgcanvas");
                var ctx = c.getContext("2d");
                var img = document.getElementById("myImg");
                // alert("wsproximitytab img.height = " + img.height);
                c.width=img.width;

                c.height=img.height;
                ctx.drawImage(img,0,0,img.width,img.height,0,0,img.width,img.height);
                // c.width=900;
                // c.height=500;
                // ctx.drawImage(img,0,0,900,500,0,0,900,500);

                var xx = h337.create({"element":document.getElementById("heatmapArea"),"visible":true});
                var el =  JSON.stringify(resultdata);
                $.removeCookie('current_heatmap_hours');
                $.cookie("current_heatmap_hours", el);
                // alert("generateHeatmap : " + el);
                var obj = eval('('+el+')');
                xx.store.setDataSet(obj);
                $('#loadingDiv').hide();
            },
            error: function(returnval) {
                $('#loadingDiv').hide();
                alert("Error retrieving data");
            }
        });
    });

    //---------- zonal tab ----------
    $(document).on('click', '#zonaltab', function () {
        $.ajax({

            url: pathname+'hipjam/zonalJsondata',
            type: 'get',
            data : { 'period':'today','scanner_type':'internal','venue':venuename ,'domain':domainname },
            success: function(resultdata) {

                $( "#zoneTable" ).html( resultdata );

            }
        });
    });

    //---------- week ----------
    // $('#rep_customer').html('loading...');
    // $('#new_rep_customer').html('loading...');
    // $('#engaged_customers').html('loading...');
    // $('#rep_ave').html('loading...');
    // $.ajax({

    //     url: pathname+'hipjam/chartJsondata',
    //     type: 'get',
    //     dataType: 'json',
    //     data : { 'period':'this_week','scanner_type':'internal','venue':venuename ,'domain':domainname},
    //     success: function(data) {
    //         Data1 = data;
    //         Data2 = Data1.total.total;
    //         $('#new_week').html(Data1.total.new);
    //         //for onchange function
    //         week_data = data;

    //         $('#rep_customer').html(week_data.total.total);
    //         $('#new_rep_customer').html(week_data.total.new);
    //         $('#engaged_customers').html(week_data.total.engaged_customers);
    //         $('#rep_ave').html(week_data.total.average_session);
    //         $('#window_con').html(week_data.total.window_conversion + '%');

    //         $('#perHperiod').html('This Week');
    //         $('#storeTrfc').html('This Week');
    //         $('#storeTrfc').html('This Week');

    //         //----------------------- week visit  -------
    //         chartData_w1 = week_data.total.trends.dates;
    //         jsonObj_w = [];
    //         $.each(chartData_w1 , function (){
    //             item_w = {} ;
    //             item_w ["label"] = this.date;
    //             item_w ["value"] = this.total;

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

    //         item1 = {} ;
    //         item1["seriesname"] = "new";
    //         item1["data"] = [];

    //         data = [];

    //         $.each(chartData2 , function (){
    //             item = {} ;
    //             item ["label"] = this.weekday;

    //             jsonCat_s["category"].push(item);

    //             itemsnew = {};
    //             itemsnew ["value"] = this.new;
    //             item1["data"].push(itemsnew);

    //             item2 = {} ;
    //             item2 ["value"] = this.total - this.new;
    //             data.push(item2);

    //         });
    //         jsonCat = jsonCat_s;

    //         jsonDat.push(item1);

    //         item_r = {} ;
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
    //                 "dataset" : jsonDat

    //             }
    //         });
    //         apiChart.render();


    //         //------------------- store traffic /hour -----------
    //         chartData_t1 = week_data.total.trends.hours;
    //         jsonObj = [];
    //         $.each(chartData_t1 , function (){
    //             item = {} ;
    //             item ["label"] = this.hour;
    //             item ["value"] = this.total;

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

    //     },
    //     error: function (error) {
    //         $('#rep_customer').html('<span style="font-size: 35%;">Data not available</span>');
    //         $('#new_rep_customer').html('<span style="font-size: 35%;">Data not available</span>');
    //         $('#engaged_customers').html('<span style="font-size: 35%;">Data not available</span>');
    //         $('#rep_ave').html('<span style="font-size: 35%;">Data not available</span>');
    //     }
    // });

    //---------- OVERALL STORE TRAFFIC TREND ----------
    /*$.ajax({

        url: pathname+'hipjam/chartJsondata',
        type: 'get',
        dataType: 'json',
        data : { 'period':'year','scanner_type':'internal' },
        success: function(data) {
            chartData1 = data;
            chartData2 = chartData1.total.trends.months;
            jsonObj = [];
            $.each(chartData2 , function (){
                item = {} ;
                item ["label"] = this.month;
                item ["value"] = this.total;

                jsonObj.push(item);

            });
            var chartProperties = {
                "caption": "OVERALL STORE TRAFFIC TREND",
                "xAxisName": "Month",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'line',
                renderAt: 'chart-05',
                width: '1150',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "data": jsonObj

                }
            });
            apiChart.render();
        }
    });*/

//------------- STORE TRAFFIC/HOUR TODAY ----------
    /*$.ajax({

        url: pathname+'hipjam/chartJsondata',
        type: 'GET',
        dataType: 'json',
        data : { 'period':'day','scanner_type':'internal' },
        success: function(data) {
            chartData_t = data;
            chartData_t1 = chartData_t.total.trends.hours;
            jsonObj = [];
    alert("OK");
            $.each(chartData_t1 , function (){
                item = {} ;
                item ["label"] = this.hour;
                item ["value"] = this.total;

                jsonObj.push(item);

            });
            var chartProperties = {
                "caption": "STORE TRAFFIC BY HOUR",
                "xAxisName": "Hours",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'column2d',
                renderAt: 'chart-container',
                width: '550',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "data": jsonObj

                }
            });
            apiChart.render();



            //----------------------- ave today visit -new/returning -------
            chartData2 = chartData_t.total.trends.hours;
            jsonObj = [];
            jsonCat = [];

            jsonCat_s = {};
            jsonCat_s["category"] = [];
            jsonDat = [];

            item1 = {} ;
            item1["seriesname"] = "new";
            item1["data"] = [];

            data = [];

            $.each(chartData2 , function (){
                item = {} ;
                item ["label"] = this.hour;

                jsonCat_s["category"].push(item);

                itemsnew = {};
                itemsnew ["value"] = this.new;
                item1["data"].push(itemsnew);

                item2 = {} ;
                item2 ["value"] = this.total - this.new;
                data.push(item2);

            });
            jsonCat = jsonCat_s;

            jsonDat.push(item1);

            item_r = {} ;
            item_r["seriesname"] = "returning";
            item_r["data"] = data;
            jsonDat.push(item_r);

            var chartProperties = {
                "caption": "AVE STORE VISITS/HOUR (NEW vs. RETURNING)",
                "xAxisName": "Hours",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'mscolumn3d',
                renderAt: 'chart-07',
                width: '550',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "categories": jsonCat,
                    "dataset" : jsonDat

                }
            });
            apiChart.render();

            //------------------------

        }
    });*/


//------ STORE TRAFFIC THIS WEEK -------
    /*$.ajax({

        url: pathname+'hipjam/chartJsondata',
        type: 'GET',
        dataType: 'json',
        data : { 'period':'week','scanner_type':'internal' },
        success: function(data) {
            chartData_w = data;
            chartData_w1 = chartData_w.total.trends.dates;
            jsonObj_w = [];
            $.each(chartData_w1 , function (){
                item_w = {} ;
                item_w ["label"] = this.date;
                item_w ["value"] = this.total;

                jsonObj_w.push(item_w);

            });
            var chartProperties = {
                "caption": "STORE TRAFFIC THIS WEEK",
                "xAxisName": "Date",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'column2d',
                renderAt: 'date_week',
                width: '550',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "data": jsonObj_w

                }
            });
            apiChart.render();


            //----------------------- ave week visit -new/returning -------
            chartData2 = chartData_w.total.trends.weekdays;
            jsonObj = [];
            jsonCat = [];

            jsonCat_s = {};
            jsonCat_s["category"] = [];

            jsonDat = [];

            item1 = {} ;
            item1["seriesname"] = "new";
            item1["data"] = [];

            data = [];

            $.each(chartData2 , function (){
                item = {} ;
                item ["label"] = this.weekday;

                jsonCat_s["category"].push(item);

                itemsnew = {};
                itemsnew ["value"] = this.new;
                item1["data"].push(itemsnew);

                item2 = {} ;
                item2 ["value"] = this.total - this.new;
                data.push(item2);

            });
            jsonCat = jsonCat_s;

            jsonDat.push(item1);

            item_r = {} ;
            item_r["seriesname"] = "returning";
            item_r["data"] = data;
            jsonDat.push(item_r);

            var chartProperties = {
                "caption": "AVE STORE VISITS/DAY (NEW vs. RETURNING) ",
                "xAxisName": "Day",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'mscolumn3d',
                renderAt: 'chart-08',
                width: '550',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "categories": jsonCat,
                    "dataset" : jsonDat

                }
            });
            apiChart.render();

            //------------------------
        }
    });*/


//------- OVERALL STORE TRAFFIC (NEW vs. RETURNING) -----
    /*$.ajax({

        //url: 'http://localhost/anusha/fusion/public/chart/chartdata',
        url: 'http://mrp.doteleven.co/venues/mrp0381?period=day&scanner_type=internal',
        type: 'GET',
        dataType: 'json',
        success: function(data) {*/
            /*chartData1 = {"id":"mrp0381","name":"mrp V\u0026A Waterfront","franchise":"mrp","address":null,"coordinates":null,"timezone":"Africa/Johannesburg","scanners":[{"id":"mrp_01","updated_at":"2015-12-04T14:39:28.000+02:00","location":"Entrance","type":""},{"id":"mrp_02","updated_at":"2016-07-13T13:55:02.000+02:00","location":"PoS","type":null},{"id":"mrp_03","updated_at":"2016-07-13T13:54:55.000+02:00","location":"Three","type":null},{"id":"mrp_04","updated_at":"2016-07-13T13:54:17.000+02:00","location":"Shoes","type":null},{"id":"mrp_05","updated_at":"2016-06-22T12:28:30.000+02:00","location":"Five","type":null},{"id":"mrp_06","updated_at":"2016-04-13T08:04:21.000+02:00","location":"Six","type":null}],"period":{"start":"2015-07-13T13:55:23+00:00","end":"2016-07-13T13:55:23+00:00"},"total":{"total":725622,"new":573537,"average_session":"12:55","previous_period":{"total":0,"new":0,"average_session":0,"change":{"total":725622,"new":573537,"average_session":0}},"trends":{"hours":[{"hour":9,"total":32339,"new":21307,"average_session":"45:34"},{"hour":10,"total":62037,"new":43610,"average_session":"31:16"},{"hour":11,"total":94727,"new":67205,"average_session":"30:17"},{"hour":12,"total":119428,"new":74323,"average_session":"37:46"},{"hour":13,"total":135808,"new":65645,"average_session":"54:31"},{"hour":14,"total":149974,"new":58332,"average_session":"50:52"},{"hour":15,"total":160315,"new":52646,"average_session":"55:13"},{"hour":16,"total":168566,"new":47797,"average_session":"58:26"},{"hour":17,"total":173050,"new":43368,"average_session":"55:19"},{"hour":18,"total":182790,"new":40743,"average_session":"56:06"},{"hour":19,"total":185337,"new":33913,"average_session":"58:37"},{"hour":20,"total":180227,"new":24640,"average_session":"00:47"},{"hour":21,"total":103,"new":8,"average_session":"04:07"}],"months":[{"month":"January","total":64689,"new":30978,"average_session":"11:37"},{"month":"February","total":90987,"new":68585,"average_session":"03:31"},{"month":"March","total":123972,"new":104877,"average_session":"44:15"},{"month":"April","total":88251,"new":72473,"average_session":"02:01"},{"month":"May","total":75894,"new":60674,"average_session":"02:03"},{"month":"June","total":76638,"new":60414,"average_session":"02:25"},{"month":"July","total":46034,"new":35401,"average_session":"02:31"},{"month":"October","total":92612,"new":49228,"average_session":"09:29"},{"month":"November","total":89733,"new":44995,"average_session":"09:40"},{"month":"December","total":86925,"new":45912,"average_session":"56:19"}],"weekdays":[{"weekday":"Monday","total":110748,"new":75133,"average_session":"33:41"},{"weekday":"Tuesday","total":114643,"new":76996,"average_session":"55:14"},{"weekday":"Wednesday","total":111601,"new":73871,"average_session":"55:59"},{"weekday":"Thursday","total":115897,"new":76260,"average_session":"54:15"},{"weekday":"Friday","total":124112,"new":84178,"average_session":"52:45"},{"weekday":"Saturday","total":143394,"new":98642,"average_session":"03:11"},{"weekday":"Sunday","total":130658,"new":88457,"average_session":"04:43"}]}}};
            chartData2 = chartData1.total.trends.months;
            jsonObj = [];
            jsonCat = [];

            jsonCat_s = {};
            jsonCat_s["category"] = [];


            jsonDat = [];

            item1 = {} ;
            item1["seriesname"] = "new";
            item1["data"] = [];

            data = [];

            $.each(chartData2 , function (){
                item = {} ;
                item ["label"] = this.month;

                jsonCat_s["category"].push(item);

                itemsnew = {};
                itemsnew ["value"] = this.new;
                item1["data"].push(itemsnew);

                item2 = {} ;
                item2 ["value"] = this.total - this.new;
                data.push(item2);

            });
            jsonCat = jsonCat_s;

            jsonDat.push(item1);

            item_r = {} ;
            item_r["seriesname"] = "returning";
            item_r["data"] = data;
            jsonDat.push(item_r);

            var chartProperties = {
                "caption": "OVERALL STORE TRAFFIC (NEW vs. RETURNING)",
                "xAxisName": "Month",
                "yAxisName": "Customers",
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'msline',
                renderAt: 'chart-06',
                width: '1150',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "categories": jsonCat,
                    "dataset" : jsonDat

                }
            });
            apiChart.render();*/
        /*}
    });*/

    //---------- month ----------
    // $.ajax({

    //     url: pathname+'hipjam/chartJsondata',
    //     type: 'get',
    //     dataType: 'json',
    //     data : { 'period':'this_month','scanner_type':'internal','venue':venuename ,'domain':domainname },
    //     success: function(data) {

    //         month_data = data;
    //         // alert(month_data);

    //     }
    // });

    //---------- last month ----------
    // $.ajax({

    //     // url: pathname+'hipjam/customchartJsondata',
    //     url: pathname+'hipjam/chartJsondata',
    //     type: 'get',
    //     dataType: 'json',
    //     /*data : { 'period':'custom','scanner_type':'internal','start':'2016-06-01','end':'2016-06-30','venue':venuename },*/
    //     data : { 'period':'last_month','scanner_type':'internal','venue':venuename ,'domain':domainname },
    //     success: function(data) {

    //         pre_month_data = data;

    //     }
    // });




});

function drawCoordinates(x,y){
    var pointSize = 3; // Change according to the size of the point.
    var ctx = document.getElementById("imgcanvas").getContext("2d");

    /*var c = document.getElementById("img01");
    ctx.clearRect(0,0,c.width,c.height);*/
    ctx.fillStyle = "#ff2626"; // Red color

    ctx.beginPath(); //Start path
    ctx.arc(x, y, pointSize, 0, Math.PI * 2, true); // Draw a point using the arc function of the canvas with a point structure.
    ctx.fill(); // Close the path and fill.
}

function change_zonal_report_period(){

    var period = $("#zonalreportperiod").val();
    if(period != 'daterange'){
        $('#zonecustom').hide();
        $.ajax({

            url: pathname+'hipjam/zonalJsondata',
            type: 'get',
            data : { 'period':period,'scanner_type':'internal','venue':venuename ,'start':'','end':'' ,'domain':domainname },
            success: function(resultdata) {

                $( "#zoneTable" ).html( resultdata );

            }
        });
    } else {
        $('#zonecustom').show();
    }

}

function custom_zonal_report_period(){

    var from = $('#zonalfrom').val();
    var to = $('#zonalto').val();
    if(from == '' || to == '' ) {
        alert("Enter Range");
        return false;
    }

    $.ajax({

            url: pathname+'hipjam/zonalJsondata',
            type: 'get',
            data : { 'period':'custom','scanner_type':'internal','venue':venuename ,'start':from,'end':to ,'domain':domainname },
            success: function(resultdata) {

                $( "#zoneTable" ).html( resultdata );

            }
        });
}
document.getElementById("heatmapreportperiod").onchange = function(){
    $('#loadingDiv').show();
    var period = $("#heatmapreportperiod").val();
    var slider = $("#heatmaphourslider");
    var hour = slider.val();
    if(period != 'daterange'){
        $('#heatmapcustom').hide();
        $.ajax({

            url: pathname+'hipjam/heatmapJsondata',
            type: 'get',
            data : {'hour':hour, 'period':period,'scanner_type':'internal','venue':venuename ,'start':'','end':'' ,'domain':domainname,'venue_id':venueid },

            success: function(resultdata) {
                // generateHeatmap(resultdata); // For some reason I get an referenceerror with this
                var c = document.getElementById("imgcanvas");
                var ctx = c.getContext("2d");

                var img = document.getElementById("myImg");
                c.width=img.width;
                c.height=img.height;
                $("canvas:not(#imgcanvas)")[0].remove()
                ctx.drawImage(img,0,0,img.width,img.height,0,0,img.width,img.height);

                var xx = h337.create({"element":document.getElementById("heatmapArea"),"visible":true});

                var el =  JSON.stringify(resultdata);
                $.removeCookie('current_heatmap_hours');
                $.cookie("current_heatmap_hours", el);
                // alert("generateHeatmap : " + el);
                var obj = eval('('+el+')');
                xx.store.setDataSet(obj);

                $('#loadingDiv').hide();
            },
            error: function(returnval) {
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
document.getElementById("heatmapreportperiod").onchange = function(){ //Commented_out

    $('#loadingDiv').show();
    var period = $("#heatmapreportperiod").val();
    if(period != 'daterange'){
        $('#heatmapcustom').hide();
        $.ajax({

            url: pathname+'hipjam/heatmapJsondata',
            type: 'get',
            data : { 'period':period,'scanner_type':'internal','venue':venuename ,'start':'','end':'' ,'domain':domainname,'venue_id':venueid },

            success: function(resultdata) {
                // generateHeatmap(resultdata); // For some reason I get an referenceerror with this
                var c = document.getElementById("imgcanvas");
                var ctx = c.getContext("2d");
                var img = document.getElementById("myImg");
                c.width=img.width;
                c.height=img.height;
                ctx.drawImage(img,0,0,img.width,img.height,0,0,img.width,img.height);

                var xx = h337.create({"element":document.getElementById("heatmapArea"),"visible":true});
                var el =  JSON.stringify(resultdata);
                // alert("generateHeatmap : " + el);
                var obj = eval('('+el+')');
                xx.store.setDataSet(obj);

                $('#loadingDiv').hide();
            },
            error: function(returnval) {
                $('#loadingDiv').hide();
                alert("Error retrieving data");
            }
        });
    } else {
        $('#heatmapcustom').show();
    }

};

function custom_heatmap_report_period(){

    var from = $('#heatmapfrom').val();
    var to = $('#heatmapto').val();
    if(from == '' || to == '' ) {
        alert("Enter Range");
        return false;
    }

    $.ajax({

            url: pathname+'hipjam/heatmapJsondata',
            type: 'get',
            data : { 'period':'custom','scanner_type':'internal','venue':venuename ,'start':from,'end':to ,'domain':domainname,'venue_id':venueid },
            success: function(resultdata) {
                console.log(resultdata['data'][0]);
                var c = document.getElementById("imgcanvas");
                var ctx = c.getContext("2d");
                var img = document.getElementById("myImg");
                c.width=img.width;
                c.height=img.height;
                ctx.drawImage(img,0,0,img.width,img.height,0,0,img.width,img.height);

                $.each(resultdata.cordinates, function(arrayID,group) {
                    drawCoordinates(group.xcoord,group.ycoord);
                });

                var xx = h337.create({"element":document.getElementById("heatmapArea"), "radius":25, "visible":true});

                //var el =  "{max: 100, data: [{x:800,y:100,count:100},{x:290,y:80,count:20},{x:30,y:580,count:80},{x:10,y:100,count:10},{x:40,y:70,count:60},{x:90,y:10,count:40},{x:50,y:100,count:70},{x:20,y:70,count:30},{x:60,y:50,count:15},{x:90,y:40,count:20}]}";
                //var el =  "{max: 100, data: "+resultdata+"}";
                //var el =  "{max: "+resultdata.split('&')[1]+", data: "+resultdata.split('&')[0]+"}";
                var el = resultdata.heatmap;

                var obj = eval('('+el+')');
                // call the heatmap's store's setDataSet method in order to set static data
                xx.store.setDataSet(obj);

            }
        });
}
function updateSliderChanges(){

    var c = document.getElementById("imgcanvas");
    var ctx = c.getContext("2d");

    //$("canvas:not(#imgcanvas)")[0].remove();
    var period = $("#heatmapreportperiod").val();
    var slider = $("#heatmaphourslider");
    $("#heatmaphour").val(slider.val()+":00");
    var img = document.getElementById("myImg");

    c.width=img.width;
    c.height=img.height;
    ctx.drawImage(img,0,0,img.width,img.height,0,0,img.width,img.height);

    $.ajax({

        url: pathname+'hipjam/heatmapJsondata',
        type: 'get',
        data : {'hour':slider.val(), 'period':period,'scanner_type':'internal','venue':venuename ,'start':'','end':'' ,'domain':domainname,'venue_id':venueid },

        success: function(resultdata) {
            // generateHeatmap(resultdata); // For some reason I get an referenceerror with this
            var c = document.getElementById("imgcanvas");
            var ctx = c.getContext("2d");

            var img = document.getElementById("myImg");
            c.width=img.width;
            c.height=img.height;
            $("canvas:not(#imgcanvas)")[0].remove()
            ctx.drawImage(img,0,0,img.width,img.height,0,0,img.width,img.height);

            var xx = h337.create({"element":document.getElementById("heatmapArea"),"visible":true});

            var el =  JSON.stringify(resultdata);
            // alert("generateHeatmap : " + el);
            var obj = eval('('+el+')');
            xx.store.setDataSet(obj);

            $('#loadingDiv').hide();
        },
        error: function(returnval) {
            $('#loadingDiv').hide();
            alert("Error retrieving data");
        }
    });

}

// function change_report_period()
// {
//     var time = $("#brandreportperiod").val();
//     if(time != 'daterange'){
//         $('#rep_customer').html('loading...');
//         $('#new_rep_customer').html('loading...');
//         $('#engaged_customers').html('loading...');
//         $('#rep_ave').html('loading...');
//         $('#window_con').html('loading...');
//     }

//     if(time == 'rep7day'){
//         $('#rep_customer').html(week_data.total.total);
//         $('#new_rep_customer').html(week_data.total.new);
//         $('#engaged_customers').html(week_data.total.engaged_customers);
//         $('#rep_ave').html(week_data.total.average_session);
//         $('#custom').hide();
//         // window_conversion_select = ((now_data.total.total/week_data.total.total)*100).toFixed(2);
//         $('#window_con').html(week_data.total.window_conversion + '%');

//         store_traffic('rep7day')

//         //$('#rep_max').html();
//     }else if(time == 'repthismonth'){
//         $('#rep_customer').html(month_data.total.total);
//         $('#new_rep_customer').html(month_data.total.new);
//         $('#engaged_customers').html(month_data.total.engaged_customers);
//         $('#custom').hide();
//         $('#rep_ave').html(month_data.total.average_session);
//         // window_conversion_select = ((now_data.total.total/month_data.total.total)*100).toFixed(2);
//         // $('#window_con').html(window_conversion_select+'%');
//         $('#window_con').html(month_data.total.window_conversion + '%');

//         store_traffic('repthismonth')
//         //$('#rep_max').html();
//     }else if(time == 'replastmonth'){
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
//     }else if(time == 'daterange'){
//         $('#custom').show();

//     }else if(time == ''){
//         alert('Please Select');
//     }else{
//         alert('Undefined');
//     }
// }

// function custom_report_period(){
//     var from = $('#venuefrom').val();
//     var to = $('#venueto').val();
//     if(from == '' || to == '' ) {
//         alert("Enter Range");
//         return false;
//     }


//     $('#rep_customer').html('loading...');
//     $('#new_rep_customer').html('loading...');
//     $('#engaged_customers').html('loading...');
//     $('#rep_ave').html('loading...');
//     $('#window_con').html('loading...');

//     $.ajax({

//         url: pathname+'hipjam/customchartJsondata',
//         type: 'get',
//         dataType: 'json',
//         data : { 'period':'custom','scanner_type':'internal','start':from,'end':to,'venue':venuename ,'domain':domainname },
//         success: function(data) {
//             // console.log("total : " + data.total);
//             // console.log("total.total : " + data.total.total);
//             // console.log("total.window_conversion : " + data.total.window_conversion);

//             $('#rep_customer').html(data.total.total);
//             $('#new_rep_customer').html(data.total.new);
//             $('#engaged_customers').html(data.total.engaged_customers);
//             $('#rep_ave').html(data.total.average_session);
//             $('#window_con').html(data.total.window_conversion + '%');


//             $('#perHperiod').html('From '+from+' To '+to+' ');
//             $('#storeTrfc').html('From '+from+' To '+to+' ');
//             $('#storeTrfc').html('From '+from+' To '+to+' ');
//             //----------------------- week visit  -------
//                 chartData_w1 = data.total.trends.dates;
//                 jsonObj_w = [];
//                 $.each(chartData_w1 , function (){
//                     item_w = {} ;
//                     item_w ["label"] = this.date;
//                     item_w ["value"] = this.total;

//                     jsonObj_w.push(item_w);

//                 });
//                 var chartProperties = {
//                     //"caption": "STORE TRAFFIC THIS WEEK",
//                     "caption": "",
//                     "xAxisName": "Date",
//                     "yAxisName": "Customers",
//                     "rotatevalues": "1",
//                     "theme": "zune"
//                 };

//                 apiChart = new FusionCharts({
//                     type: 'column2d',
//                     renderAt: 'date_week',
//                     width: '400',
//                     height: '350',
//                     dataFormat: 'json',
//                     dataSource: {
//                         "chart": chartProperties,
//                         "data": jsonObj_w

//                     }
//                 });
//                 apiChart.render();

//                 //------------------------overall ----------
//                 var chartProperties = {
//                     //"caption": "OVERALL STORE TRAFFIC TREND",
//                     "caption": "",
//                     "xAxisName": "Date",
//                     "yAxisName": "Customers",
//                     "rotatevalues": "1",
//                     "theme": "zune"
//                 };

//                 apiChart = new FusionCharts({
//                     type: 'line',
//                     renderAt: 'chart-05',
//                     width: '900',
//                     height: '350',
//                     dataFormat: 'json',
//                     dataSource: {
//                         "chart": chartProperties,
//                         "data": jsonObj_w

//                     }
//                 });
//                 apiChart.render();

//                 //-----------------------overall traffic ( new/returning ) ----------
//                 chartData2 = data.total.trends.dates;
//                 jsonObj = [];
//                 jsonCat = [];

//                 jsonCat_s = {};
//                 jsonCat_s["category"] = [];

//                 jsonDat = [];

//                 item1 = {} ;
//                 item1["seriesname"] = "new";
//                 item1["data"] = [];

//                 data = [];

//                 $.each(chartData2 , function (){
//                     item = {} ;
//                     item ["label"] = this.date;

//                     jsonCat_s["category"].push(item);

//                     itemsnew = {};
//                     itemsnew ["value"] = this.new;
//                     item1["data"].push(itemsnew);

//                     item2 = {} ;
//                     item2 ["value"] = this.total - this.new;
//                     data.push(item2);

//                 });
//                 jsonCat = jsonCat_s;

//                 jsonDat.push(item1);

//                 item_r = {} ;
//                 item_r["seriesname"] = "returning";
//                 item_r["data"] = data;
//                 jsonDat.push(item_r);

//                 var chartProperties = {
//                     //"caption": "OVERALL STORE TRAFFIC (NEW vs. RETURNING)",
//                     "caption": "",
//                     "xAxisName": "Date",
//                     "yAxisName": "Customers",
//                     "rotatevalues": "1",
//                     "theme": "zune"
//                 };

//                 apiChart = new FusionCharts({
//                     type: 'msline',
//                     renderAt: 'chart-06',
//                     width: '900',
//                     height: '350',
//                     dataFormat: 'json',
//                     dataSource: {
//                         "chart": chartProperties,
//                         "categories": jsonCat,
//                         "dataset" : jsonDat

//                     }
//                 });
//                 apiChart.render();

//                 //------------------- store traffic /hour -----------
//                 chartData_t1 = data.total.trends.hours;
//                 jsonObj = [];
//                 $.each(chartData_t1 , function (){
//                     item = {} ;
//                     item ["label"] = this.hour;
//                     item ["value"] = this.total;

//                     jsonObj.push(item);

//                 });
//                 var chartProperties = {
//                     //"caption": "STORE TRAFFIC/HOUR week",
//                     "caption": "",
//                     "xAxisName": "Hours",
//                     "yAxisName": "Customers",
//                     "rotatevalues": "1",
//                     "theme": "zune"
//                 };

//                 apiChart = new FusionCharts({
//                     type: 'column2d',
//                     renderAt: 'chart-container',
//                     width: '400',
//                     height: '350',
//                     dataFormat: 'json',
//                     dataSource: {
//                         "chart": chartProperties,
//                         "data": jsonObj

//                     }
//                 });
//                 apiChart.render();




//         }
//     });
// }

// function store_traffic(period){
//     // alert("in store_traffic");


//     if(period == 'rep7day'){

//         $('#perHperiod').html('This Week');
//         $('#storeTrfc').html('This Week');
//         $('#storeTrfc').html('This Week');

//             //----------------------- week visit  -------
//             chartData_w1 = week_data.total.trends.dates;
//             jsonObj_w = [];
//             $.each(chartData_w1 , function (){
//                 item_w = {} ;
//                 item_w ["label"] = this.date;
//                 item_w ["value"] = this.total;

//                 jsonObj_w.push(item_w);

//             });
//             var chartProperties = {
//                 //"caption": "STORE TRAFFIC THIS WEEK",
//                 "caption": "",
//                 "xAxisName": "Date",
//                 "yAxisName": "Customers",
//                 "rotatevalues": "1",
//                 "theme": "zune"
//             };

//             apiChart = new FusionCharts({
//                 type: 'column2d',
//                 renderAt: 'date_week',
//                 width: '400',
//                 height: '350',
//                 dataFormat: 'json',
//                 dataSource: {
//                     "chart": chartProperties,
//                     "data": jsonObj_w

//                 }
//             });
//             apiChart.render();

//             //------------------------overall ----------
//             var chartProperties = {
//                 //"caption": "OVERALL STORE TRAFFIC TREND",
//                 "caption": "",
//                 "xAxisName": "Month",
//                 "yAxisName": "Customers",
//                 "rotatevalues": "1",
//                 "theme": "zune"
//             };

//             apiChart = new FusionCharts({
//                 type: 'line',
//                 renderAt: 'chart-05',
//                 width: '900',
//                 height: '350',
//                 dataFormat: 'json',
//                 dataSource: {
//                     "chart": chartProperties,
//                     "data": jsonObj_w

//                 }
//             });
//             apiChart.render();

//             //-----------------------overall traffic ( new/returning ) ----------
//             chartData2 = week_data.total.trends.weekdays;
//             jsonObj = [];
//             jsonCat = [];

//             jsonCat_s = {};
//             jsonCat_s["category"] = [];

//             jsonDat = [];

//             item1 = {} ;
//             item1["seriesname"] = "new";
//             item1["data"] = [];

//             data = [];

//             $.each(chartData2 , function (){
//                 item = {} ;
//                 item ["label"] = this.weekday;

//                 jsonCat_s["category"].push(item);

//                 itemsnew = {};
//                 itemsnew ["value"] = this.new;
//                 item1["data"].push(itemsnew);

//                 item2 = {} ;
//                 item2 ["value"] = this.total - this.new;
//                 data.push(item2);

//             });
//             jsonCat = jsonCat_s;

//             jsonDat.push(item1);

//             item_r = {} ;
//             item_r["seriesname"] = "returning";
//             item_r["data"] = data;
//             jsonDat.push(item_r);

//             var chartProperties = {
//                 //"caption": "OVERALL STORE TRAFFIC (NEW vs. RETURNING)",
//                 "caption": "",
//                 "xAxisName": "Day",
//                 "yAxisName": "Customers",
//                 "rotatevalues": "1",
//                 "theme": "zune"
//             };

//             apiChart = new FusionCharts({
//                 type: 'msline',
//                 renderAt: 'chart-06',
//                 width: '900',
//                 height: '350',
//                 dataFormat: 'json',
//                 dataSource: {
//                     "chart": chartProperties,
//                     "categories": jsonCat,
//                     "dataset" : jsonDat

//                 }
//             });
//             apiChart.render();


//             //------------------- store traffic /hour -----------
//             chartData_t1 = week_data.total.trends.hours;
//             jsonObj = [];
//             $.each(chartData_t1 , function (){
//                 item = {} ;
//                 item ["label"] = this.hour;
//                 item ["value"] = this.total;

//                 jsonObj.push(item);

//             });
//             var chartProperties = {
//                 //"caption": "STORE TRAFFIC/HOUR week",
//                 "caption": "",
//                 "xAxisName": "Hours",
//                 "yAxisName": "Customers",
//                 "rotatevalues": "1",
//                 "theme": "zune"
//             };

//             apiChart = new FusionCharts({
//                 type: 'column2d',
//                 renderAt: 'chart-container',
//                 width: '400',
//                 height: '350',
//                 dataFormat: 'json',
//                 dataSource: {
//                     "chart": chartProperties,
//                     "data": jsonObj

//                 }
//             });
//             apiChart.render();

//     }else if(period == 'repthismonth'){

//         // alert("in thismonth");

//         $('#perHperiod').html('This Month');
//         $('#storeTrfc').html('This Month');
//         $('#storeTrfc').html('This Month');
//         //----------------------- week visit  -------
//             chartData_w1 = month_data.total.trends.dates;
//             jsonObj_w = [];
//             $.each(chartData_w1 , function (){
//                 item_w = {} ;
//                 item_w ["label"] = this.date;
//             // alert(this.date);
//                 item_w ["value"] = this.total;

//                 jsonObj_w.push(item_w);

//             });
//             var chartProperties = {
//                 //"caption": "STORE TRAFFIC THIS WEEK",
//                 "caption": "",
//                 "xAxisName": "Date",
//                 "yAxisName": "Customers",
//                 "rotatevalues": "1",
//                 "theme": "zune"
//             };

//             apiChart = new FusionCharts({
//                 type: 'column2d',
//                 renderAt: 'date_week',
//                 width: '400',
//                 height: '350',
//                 dataFormat: 'json',
//                 dataSource: {
//                     "chart": chartProperties,
//                     "data": jsonObj_w

//                 }
//             });
//             apiChart.render();

//             //------------------------overall ----------
//             var chartProperties = {
//                 //"caption": "OVERALL STORE TRAFFIC TREND",
//                 "caption": "",
//                 "xAxisName": "Month",
//                 "yAxisName": "Customers",
//                 "rotatevalues": "1",
//                 "theme": "zune"
//             };

//             apiChart = new FusionCharts({
//                 type: 'line',
//                 renderAt: 'chart-05',
//                 width: '900',
//                 height: '350',
//                 dataFormat: 'json',
//                 dataSource: {
//                     "chart": chartProperties,
//                     "data": jsonObj_w

//                 }
//             });
//             apiChart.render();

//             //-----------------------overall traffic ( new/returning ) ----------
//             chartData2 = month_data.total.trends.dates;
//             jsonObj = [];
//             jsonCat = [];

//             jsonCat_s = {};
//             jsonCat_s["category"] = [];

//             jsonDat = [];

//             item1 = {} ;
//             item1["seriesname"] = "new";
//             item1["data"] = [];

//             data = [];

//             $.each(chartData2 , function (){
//                 item = {} ;
//                 item ["label"] = this.date;

//                 jsonCat_s["category"].push(item);

//                 itemsnew = {};
//                 itemsnew ["value"] = this.new;
//                 item1["data"].push(itemsnew);

//                 item2 = {} ;
//                 item2 ["value"] = this.total - this.new;
//                 data.push(item2);

//             });
//             jsonCat = jsonCat_s;

//             jsonDat.push(item1);

//             item_r = {} ;
//             item_r["seriesname"] = "returning";
//             item_r["data"] = data;
//             jsonDat.push(item_r);

//             var chartProperties = {
//                 //"caption": "OVERALL STORE TRAFFIC (NEW vs. RETURNING)",
//                 "caption": "",
//                 "xAxisName": "Date",
//                 "yAxisName": "Customers",
//                 "rotatevalues": "1",
//                 "theme": "zune"
//             };

//             apiChart = new FusionCharts({
//                 type: 'msline',
//                 renderAt: 'chart-06',
//                 width: '900',
//                 height: '350',
//                 dataFormat: 'json',
//                 dataSource: {
//                     "chart": chartProperties,
//                     "categories": jsonCat,
//                     "dataset" : jsonDat

//                 }
//             });
//             apiChart.render();

//             //------------------- store traffic /hour -----------
//             chartData_t1 = month_data.total.trends.hours;
//             jsonObj = [];
//             $.each(chartData_t1 , function (){
//                 item = {} ;
//                 item ["label"] = this.hour;
//                 item ["value"] = this.total;

//                 jsonObj.push(item);

//             });
//             var chartProperties = {
//                 //"caption": "STORE TRAFFIC/HOUR week",
//                 "caption": "",
//                 "xAxisName": "Hours",
//                 "yAxisName": "Customers",
//                 "rotatevalues": "1",
//                 "theme": "zune"
//             };

//             apiChart = new FusionCharts({
//                 type: 'column2d',
//                 renderAt: 'chart-container',
//                 width: '400',
//                 height: '350',
//                 dataFormat: 'json',
//                 dataSource: {
//                     "chart": chartProperties,
//                     "data": jsonObj

//                 }
//             });
//             apiChart.render();

//     }else if(period == 'replastmonth'){

//         $('#perHperiod').html('Last Month');
//         $('#storeTrfc').html('Last Month');
//         $('#storeTrfc').html('Last Month');

//         //----------------------- week visit  -------
//             chartData_w1 = pre_month_data.total.trends.dates;
//             jsonObj_w = [];
//             $.each(chartData_w1 , function (){
//                 item_w = {} ;
//                 item_w ["label"] = this.date;
//                 item_w ["value"] = this.total;

//                 jsonObj_w.push(item_w);

//             });
//             var chartProperties = {
//                 //"caption": "STORE TRAFFIC THIS WEEK",
//                 "caption": "",
//                 "xAxisName": "Date",
//                 "yAxisName": "Customers",
//                 "rotatevalues": "1",
//                 "theme": "zune"
//             };

//             apiChart = new FusionCharts({
//                 type: 'column2d',
//                 renderAt: 'date_week',
//                 width: '400',
//                 height: '350',
//                 dataFormat: 'json',
//                 dataSource: {
//                     "chart": chartProperties,
//                     "data": jsonObj_w

//                 }
//             });
//             apiChart.render();

//             //------------------------overall ----------
//             var chartProperties = {
//                 //"caption": "OVERALL STORE TRAFFIC TREND",
//                 "caption": "",
//                 "xAxisName": "Month",
//                 "yAxisName": "Customers",
//                 "rotatevalues": "1",
//                 "theme": "zune"
//             };

//             apiChart = new FusionCharts({
//                 type: 'line',
//                 renderAt: 'chart-05',
//                 width: '900',
//                 height: '350',
//                 dataFormat: 'json',
//                 dataSource: {
//                     "chart": chartProperties,
//                     "data": jsonObj_w

//                 }
//             });
//             apiChart.render();

//             //-----------------------overall traffic ( new/returning ) ----------
//             chartData2 = pre_month_data.total.trends.dates;
//             jsonObj = [];
//             jsonCat = [];

//             jsonCat_s = {};
//             jsonCat_s["category"] = [];

//             jsonDat = [];

//             item1 = {} ;
//             item1["seriesname"] = "new";
//             item1["data"] = [];

//             data = [];

//             $.each(chartData2 , function (){
//                 item = {} ;
//                 item ["label"] = this.date;

//                 jsonCat_s["category"].push(item);

//                 itemsnew = {};
//                 itemsnew ["value"] = this.new;
//                 item1["data"].push(itemsnew);

//                 item2 = {} ;
//                 item2 ["value"] = this.total - this.new;
//                 data.push(item2);

//             });
//             jsonCat = jsonCat_s;

//             jsonDat.push(item1);

//             item_r = {} ;
//             item_r["seriesname"] = "returning";
//             item_r["data"] = data;
//             jsonDat.push(item_r);

//             var chartProperties = {
//                 //"caption": "OVERALL STORE TRAFFIC (NEW vs. RETURNING)",
//                 "caption": "",
//                 "xAxisName": "Date",
//                 "yAxisName": "Customers",
//                 "rotatevalues": "1",
//                 "theme": "zune"
//             };

//             apiChart = new FusionCharts({
//                 type: 'msline',
//                 renderAt: 'chart-06',
//                 width: '900',
//                 height: '350',
//                 dataFormat: 'json',
//                 dataSource: {
//                     "chart": chartProperties,
//                     "categories": jsonCat,
//                     "dataset" : jsonDat

//                 }
//             });
//             apiChart.render();


//             //------------------- store traffic /hour -----------
//             chartData_t1 = pre_month_data.total.trends.hours;
//             jsonObj = [];
//             $.each(chartData_t1 , function (){
//                 item = {} ;
//                 item ["label"] = this.hour;
//                 item ["value"] = this.total;

//                 jsonObj.push(item);

//             });
//             var chartProperties = {
//                 //"caption": "STORE TRAFFIC/HOUR week",
//                 "caption": "",
//                 "xAxisName": "Hours",
//                 "yAxisName": "Customers",
//                 "rotatevalues": "1",
//                 "theme": "zune"
//             };

//             apiChart = new FusionCharts({
//                 type: 'column2d',
//                 renderAt: 'chart-container',
//                 width: '400',
//                 height: '350',
//                 dataFormat: 'json',
//                 dataSource: {
//                     "chart": chartProperties,
//                     "data": jsonObj

//                 }
//             });
//             apiChart.render();
//     }


//             var chartProperties = {
//                 //"caption": "STORE TRAFFIC/HOUR TODAY",
//                 "caption": "",
//                 "xAxisName": "Hours",
//                 "yAxisName": "Customers",
//                 "rotatevalues": "1",
//                 "theme": "zune"
//             };

//             apiChart = new FusionCharts({
//                 type: 'column2d',
//                 renderAt: 'chart-container',
//                 width: '400',
//                 height: '350',
//                 dataFormat: 'json',
//                 dataSource: {
//                     "chart": chartProperties,
//                     "data": jsonObj

//                 }
//             });
//             apiChart.render();
// }

function getMax(arr, prop) {
    var max;
    for (var i=0 ; i<arr.length ; i++) {
        if (!max || parseInt(arr[i][prop]) > parseInt(max[prop]))
            max = arr[i];
    }
    return max;
}

/*function avg(data){

    $.each(data, function (i, v) {
        total += v.duration;
    })
    var avg = total/data.medias.length;
    $('#avg').html(avg);
}*/
