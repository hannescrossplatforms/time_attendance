$( document ).ready(function() {

    pathname = $('#url').val();

    //---------- now ----------
    // $.ajax({

    //     url: pathname+'hipjam/chartJsondata',
    //     type: 'get',
    //     dataType: 'json',
    //     data : { 'period':'now','scanner_type':'internal' },
    //     success: function(data) {
    //         debugger;
    //         Data1 = data;
    //         Data2 = Data1.total.total;
    //         $('#customer_now').html(Data2);
    //         $('#new_now').html(Data1.total.new);

    //     }, error: function(xhr,err) {
    //         debugger;
    //     }
    // });

    //---------- today ----------
    // $.ajax({

    //     url: pathname+'hipjam/chartJsondata',
    //     type: 'get',
    //     dataType: 'json',
    //     data : { 'period':'day','scanner_type':'internal' },
    //     success: function(data) {
    //         Data1 = data;
    //         Data2 = Data1.total.total;
    //         $('#customer_today').html(Data2);
    //         $('#new_today').html(Data1.total.new);

    //     }, error: function(xhr,err) {
    //         debugger;
    //     }
    // });

    //---------- week ----------
    // $.ajax({

    //     url: pathname+'hipjam/chartJsondata',
    //     type: 'get',
    //     dataType: 'json',
    //     data : { 'period':'week','scanner_type':'internal' },
    //     success: function(data) {
    //         Data1 = data;
    //         Data2 = Data1.total.total;
    //         $('#new_week').html(Data1.total.new);
    //         //for onchange function
    //         week_data = data;

    //     }, error: function(xhr,err) {
    //         debugger;
    //     }
    // });

    //---------- OVERALL STORE TRAFFIC TREND ----------
    // $.ajax({

    //     url: pathname+'hipjam/chartJsondata',
    //     type: 'get',
    //     dataType: 'json',
    //     data : { 'period':'year','scanner_type':'internal' },
    //     success: function(data) {
    //         chartData1 = data;
    //         chartData2 = chartData1.total.trends.months;
    //         jsonObj = [];
    //         $.each(chartData2 , function (){
    //             item = {} ;
    //             item ["label"] = this.month;
    //             item ["value"] = this.total;

    //             jsonObj.push(item);

    //         });
    //         var chartProperties = {
    //             "caption": "OVERALL STORE TRAFFIC TREND",
    //             "xAxisName": "Month",
    //             "yAxisName": "Customers",
    //             "rotatevalues": "1",
    //             "theme": "zune"
    //         };

    //         apiChart = new FusionCharts({
    //             type: 'line',
    //             renderAt: 'chart-05',
    //             width: '1150',
    //             height: '350',
    //             dataFormat: 'json',
    //             dataSource: {
    //                 "chart": chartProperties,
    //                 "data": jsonObj

    //             }
    //         });
    //         apiChart.render();
    //     }
    // });

//------------- STORE TRAFFIC/HOUR TODAY ----------
    // $.ajax({

    //     url: pathname+'hipjam/chartJsondata',
    //     type: 'GET',
    //     dataType: 'json',
    //     data : { 'period':'day','scanner_type':'internal' },
    //     success: function(data) {
    //         chartData_t = data;
    //         chartData_t1 = chartData_t.total.trends.hours;
    //         jsonObj = [];
    //         $.each(chartData_t1 , function (){
    //             item = {} ;
    //             item ["label"] = this.hour;
    //             item ["value"] = this.total;

    //             jsonObj.push(item);

    //         });
    //         var chartProperties = {
    //             "caption": "STORE TRAFFIC/HOUR TODAY",
    //             "xAxisName": "Hours",
    //             "yAxisName": "Customers",
    //             "rotatevalues": "1",
    //             "theme": "zune"
    //         };

    //         apiChart = new FusionCharts({
    //             type: 'column2d',
    //             renderAt: 'chart-container',
    //             width: '550',
    //             height: '350',
    //             dataFormat: 'json',
    //             dataSource: {
    //                 "chart": chartProperties,
    //                 "data": jsonObj

    //             }
    //         });
    //         apiChart.render();



    //         //----------------------- ave today visit -new/returning -------
    //         chartData2 = chartData_t.total.trends.hours;
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
    //             item ["label"] = this.hour;

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
    //             "caption": "AVE STORE VISITS/HOUR (NEW vs. RETURNING)",
    //             "xAxisName": "Hours",
    //             "yAxisName": "Customers",
    //             "rotatevalues": "1",
    //             "theme": "zune"
    //         };

    //         apiChart = new FusionCharts({
    //             type: 'mscolumn3d',
    //             renderAt: 'chart-07',
    //             width: '550',
    //             height: '350',
    //             dataFormat: 'json',
    //             dataSource: {
    //                 "chart": chartProperties,
    //                 "categories": jsonCat,
    //                 "dataset" : jsonDat

    //             }
    //         });
    //         apiChart.render();

    //         //------------------------

    //     }
    // });


//------ STORE TRAFFIC THIS WEEK -------
    // $.ajax({

    //     url: pathname+'hipjam/chartJsondata',
    //     type: 'GET',
    //     dataType: 'json',
    //     data : { 'period':'week','scanner_type':'internal' },
    //     success: function(data) {
    //         chartData_w = data;
    //         chartData_w1 = chartData_w.total.trends.dates;
    //         jsonObj_w = [];
    //         $.each(chartData_w1 , function (){
    //             item_w = {} ;
    //             item_w ["label"] = this.date;
    //             item_w ["value"] = this.total;

    //             jsonObj_w.push(item_w);

    //         });
    //         var chartProperties = {
    //             "caption": "STORE TRAFFIC THIS WEEK",
    //             "xAxisName": "Date",
    //             "yAxisName": "Customers",
    //             "rotatevalues": "1",
    //             "theme": "zune"
    //         };

    //         apiChart = new FusionCharts({
    //             type: 'column2d',
    //             renderAt: 'date_week',
    //             width: '550',
    //             height: '350',
    //             dataFormat: 'json',
    //             dataSource: {
    //                 "chart": chartProperties,
    //                 "data": jsonObj_w

    //             }
    //         });
    //         apiChart.render();


    //         //----------------------- ave week visit -new/returning -------
    //         chartData2 = chartData_w.total.trends.weekdays;
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
    //             "caption": "AVE STORE VISITS/DAY (NEW vs. RETURNING) ",
    //             "xAxisName": "Day",
    //             "yAxisName": "Customers",
    //             "rotatevalues": "1",
    //             "theme": "zune"
    //         };

    //         apiChart = new FusionCharts({
    //             type: 'mscolumn3d',
    //             renderAt: 'chart-08',
    //             width: '550',
    //             height: '350',
    //             dataFormat: 'json',
    //             dataSource: {
    //                 "chart": chartProperties,
    //                 "categories": jsonCat,
    //                 "dataset" : jsonDat

    //             }
    //         });
    //         apiChart.render();

    //         //------------------------
    //     }
    // });


//------- OVERALL STORE TRAFFIC (NEW vs. RETURNING) -----
    /*$.ajax({

        //url: 'http://localhost/anusha/fusion/public/chart/chartdata',
        url: 'http://mrp.doteleven.co/venues/mrp0381?period=day&scanner_type=internal',
        type: 'GET',
        dataType: 'json',
        success: function(data) {*/
            // chartData1 = {"id":"mrp0381","name":"mrp V\u0026A Waterfront","franchise":"mrp","address":null,"coordinates":null,"timezone":"Africa/Johannesburg","scanners":[{"id":"mrp_01","updated_at":"2015-12-04T14:39:28.000+02:00","location":"Entrance","type":""},{"id":"mrp_02","updated_at":"2016-07-13T13:55:02.000+02:00","location":"PoS","type":null},{"id":"mrp_03","updated_at":"2016-07-13T13:54:55.000+02:00","location":"Three","type":null},{"id":"mrp_04","updated_at":"2016-07-13T13:54:17.000+02:00","location":"Shoes","type":null},{"id":"mrp_05","updated_at":"2016-06-22T12:28:30.000+02:00","location":"Five","type":null},{"id":"mrp_06","updated_at":"2016-04-13T08:04:21.000+02:00","location":"Six","type":null}],"period":{"start":"2015-07-13T13:55:23+00:00","end":"2016-07-13T13:55:23+00:00"},"total":{"total":725622,"new":573537,"average_session":"12:55","previous_period":{"total":0,"new":0,"average_session":0,"change":{"total":725622,"new":573537,"average_session":0}},"trends":{"hours":[{"hour":9,"total":32339,"new":21307,"average_session":"45:34"},{"hour":10,"total":62037,"new":43610,"average_session":"31:16"},{"hour":11,"total":94727,"new":67205,"average_session":"30:17"},{"hour":12,"total":119428,"new":74323,"average_session":"37:46"},{"hour":13,"total":135808,"new":65645,"average_session":"54:31"},{"hour":14,"total":149974,"new":58332,"average_session":"50:52"},{"hour":15,"total":160315,"new":52646,"average_session":"55:13"},{"hour":16,"total":168566,"new":47797,"average_session":"58:26"},{"hour":17,"total":173050,"new":43368,"average_session":"55:19"},{"hour":18,"total":182790,"new":40743,"average_session":"56:06"},{"hour":19,"total":185337,"new":33913,"average_session":"58:37"},{"hour":20,"total":180227,"new":24640,"average_session":"00:47"},{"hour":21,"total":103,"new":8,"average_session":"04:07"}],"months":[{"month":"January","total":64689,"new":30978,"average_session":"11:37"},{"month":"February","total":90987,"new":68585,"average_session":"03:31"},{"month":"March","total":123972,"new":104877,"average_session":"44:15"},{"month":"April","total":88251,"new":72473,"average_session":"02:01"},{"month":"May","total":75894,"new":60674,"average_session":"02:03"},{"month":"June","total":76638,"new":60414,"average_session":"02:25"},{"month":"July","total":46034,"new":35401,"average_session":"02:31"},{"month":"October","total":92612,"new":49228,"average_session":"09:29"},{"month":"November","total":89733,"new":44995,"average_session":"09:40"},{"month":"December","total":86925,"new":45912,"average_session":"56:19"}],"weekdays":[{"weekday":"Monday","total":110748,"new":75133,"average_session":"33:41"},{"weekday":"Tuesday","total":114643,"new":76996,"average_session":"55:14"},{"weekday":"Wednesday","total":111601,"new":73871,"average_session":"55:59"},{"weekday":"Thursday","total":115897,"new":76260,"average_session":"54:15"},{"weekday":"Friday","total":124112,"new":84178,"average_session":"52:45"},{"weekday":"Saturday","total":143394,"new":98642,"average_session":"03:11"},{"weekday":"Sunday","total":130658,"new":88457,"average_session":"04:43"}]}}};
            // chartData2 = chartData1.total.trends.months;
            // jsonObj = [];
            // jsonCat = [];

            // jsonCat_s = {};
            // jsonCat_s["category"] = [];


            // jsonDat = [];

            // item1 = {} ;
            // item1["seriesname"] = "new";
            // item1["data"] = [];

            // data = [];

            // $.each(chartData2 , function (){
            //     item = {} ;
            //     item ["label"] = this.month;

            //     jsonCat_s["category"].push(item);

            //     itemsnew = {};
            //     itemsnew ["value"] = this.new;
            //     item1["data"].push(itemsnew);

            //     item2 = {} ;
            //     item2 ["value"] = this.total - this.new;
            //     data.push(item2);

            // });
            // jsonCat = jsonCat_s;

            // jsonDat.push(item1);

            // item_r = {} ;
            // item_r["seriesname"] = "returning";
            // item_r["data"] = data;
            // jsonDat.push(item_r);

            // var chartProperties = {
            //     "caption": "OVERALL STORE TRAFFIC (NEW vs. RETURNING)",
            //     "xAxisName": "Month",
            //     "yAxisName": "Customers",
            //     "rotatevalues": "1",
            //     "theme": "zune"
            // };

            // apiChart = new FusionCharts({
            //     type: 'msline',
            //     renderAt: 'chart-06',
            //     width: '1150',
            //     height: '350',
            //     dataFormat: 'json',
            //     dataSource: {
            //         "chart": chartProperties,
            //         "categories": jsonCat,
            //         "dataset" : jsonDat

            //     }
            // });
            // apiChart.render();
        /*}
    });*/

    //---------- month ----------
    // $.ajax({

    //     url: pathname+'hipjam/chartJsondata',
    //     type: 'get',
    //     dataType: 'json',
    //     data : { 'period':'month','scanner_type':'internal' },
    //     success: function(data) {

    //         month_data = data;

    //     }
    // });

    //---------- last month ----------
    // $.ajax({

    //     url: pathname+'hipjam/customchartJsondata',
    //     type: 'get',
    //     dataType: 'json',
    //     data : { 'period':'custom','scanner_type':'internal','start':'2016-06-01','end':'2016-06-30' },
    //     success: function(data) {

    //         pre_month_data = data;

    //     }
    // });


});

function change_report_period()
{
    var time = $("#brandreportperiod").val();
    if(time == 'rep7day'){
        $('#rep_customer').html(week_data.total.total);
        $('#new_rep_customer').html(week_data.total.new);
        $('#rep_ave').html(week_data.total.average_session);
        $('#custom').hide();

        //$('#rep_max').html();
    }else if(time == 'repthismonth'){
        $('#rep_customer').html(month_data.total.total);
        $('#new_rep_customer').html(month_data.total.new);
        $('#custom').hide();
        $('#rep_ave').html(month_data.total.average_session);
        //$('#rep_max').html();
    }else if(time == 'replastmonth'){
        $('#rep_customer').html(pre_month_data.total.total);
        $('#new_rep_customer').html(pre_month_data.total.new);
        $('#custom').hide();
        $('#rep_ave').html(pre_month_data.total.average_session);
        //$('#rep_max').html();
    }else if(time == 'daterange'){
        $('#custom').show();

    }else if(time == ''){
        alert('Please Select');
    }else{
        alert('Undefined');
    }
}

function custom_report_period(){
    var from = $('#venuefrom').val();
    var to = $('#venueto').val();

    $('#rep_customer').html('loading...');
    $('#new_rep_customer').html('loading...');
    $('#rep_ave').html('loading...');

    $.ajax({

        url: pathname+'hipjam/customchartJsondata',
        type: 'get',
        dataType: 'json',
        data : { 'period':'custom','scanner_type':'internal','start':from,'end':to },
        success: function(data) {

            $('#rep_customer').html(data.total.total);
            $('#new_rep_customer').html(data.total.new);
            $('#rep_ave').html(data.total.average_session);

        }
    });
}

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
