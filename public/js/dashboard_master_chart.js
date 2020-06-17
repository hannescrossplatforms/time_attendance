let chart = null;
let chart_element = $('#dashboard_master_chart');
var active_series = [];
var active_data = [];

Array.prototype.remove = function(set){return this.filter(
    function(e,i,a){return set.indexOf(e)<0}
)};

// CHART FUNCTIONS

$(document).on('change', '#master_graph_type', function() {
    let type_to_display = $(this).val();
    let ooh_metrics = $('#ooh_metric_options');
    let venue_metrics = $('#venue_metric_options');

    resetSeries();
    initSeries();

    $('.selectable-metric').each(function(i, obj) {
        $(obj).prop('checked', false).change();
    });
    dom.disableContrastingMetics(null, true);

    if (type_to_display === 'ooh') {
        venue_metrics.slideUp('fast', function() {
            ooh_metrics.slideDown('fast');
            $('#total_reach').click();
        });
    } else {
        ooh_metrics.slideUp('fast', function() {
            venue_metrics.slideDown('fast');
            $('#total_customers_in_store').click();
        });
    }
});



function addOrRemoveMetric(selected_metric, selected_type, checked) {
    dom.disableContrastingMetics(selected_type, false);

    switch(selected_metric) {
        case 'total_reach':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'total_reach', 'date', `${venue[0].venue_name} - Total Reach`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'total_reach'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'total_reach'});
            }
            
            break;
        case 'new_reach_rate':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'new_reach_rate', 'date', `${venue[0].venue_name} - New Reach Rate`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'new_reach_rate'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'new_reach_rate'});
            }
            break;
        case 'return_reach_rate':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'return_reach_rate', 'date', `${venue[0].venue_name} - Return Reach Rate`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'return_reach_rate'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'return_reach_rate'});
            }
            break;
        case 'reach_frequency':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'reach_frequency', 'date', `${venue[0].venue_name} - Reach Frequency`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'reach_frequency'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'reach_frequency'});
            }
            break;
        case 'gross_rate_point':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'gross_rate_point', 'date', `${venue[0].venue_name} - Gross Rate Point`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'gross_rate_point'});
                master_chart.removeSeries(series_to_remove);
                
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'gross_rate_point'});
            }
            break;
        case 'average_dwell_time':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'average_dwell_time', 'date', `${venue[0].venue_name} - Average Dwell Time`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'average_dwell_time'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'average_dwell_time'});
            }
            break;
        case 'strike_rate':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'strike_rate', 'date', `${venue[0].venue_name} - Strike Rate`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'strike_rate'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'strike_rate'});
            }
            break;
        case 'strike_time':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'strike_time', 'date', `${venue[0].venue_name} - Strike Time`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'strike_time'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'strike_time'});
            }
            break;
        case 'strike_distance':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'strike_distance', 'date', `${venue[0].venue_name} - Strike Distance`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'strike_distance'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'strike_distance'});
            }
            break;
        case 'potential_sales':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'potential_sales', 'date', `${venue[0].venue_name} - Potential Sales`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'potential_sales'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'potential_sales'});
            }
            break;
        case 'roi':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'roi', 'date', `${venue[0].venue_name} - ROI`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'roi'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'roi'});
            }
            break;
        case 'cpa':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'cpa', 'date', `${venue[0].venue_name} - CPA`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'cpa'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'cpa'});
            }
            break;
        //====================================================== VENUE METRICS
        case 'total_customers_in_store':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'total_customers_in_store', 'date', `${venue[0].venue_name} - Total Customers In Store`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'total_customers_in_store'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'total_customers_in_store'});
            }
            break;

        case 'new_customers_in_store':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'new_customers_in_store', 'date', `${venue[0].venue_name} - New Customers In Store`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'new_customers_in_store'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'new_customers_in_store'});
            }
            break;

        case 'returning_customers_in_store':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'returning_customers_in_store', 'date', `${venue[0].venue_name} - Returning Customers In Store`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'returning_customers_in_store'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'returning_customers_in_store'});
            }
            break;

        case 'total_conversions':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'total_conversions', 'date', `${venue[0].venue_name} - Total Conversions`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'total_conversions'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'total_conversions'});
            }
            break;

        case 'conversion_rate':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'conversion_rate', 'date', `${venue[0].venue_name} - Conversion Rate`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'conversion_rate'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'conversion_rate'});
            }
            break;

        case 'bounce_rate':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'bounce_rate', 'date', `${venue[0].venue_name} - Bounce Rate`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'bounce_rate'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'bounce_rate'});
            }
            break;

        case 'high_dwell_customers':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'high_dwell_customers', 'date', `${venue[0].venue_name} - High Dwell Customers`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'high_dwell_customers'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'high_dwell_customers'});
            }
            break;

        case 'average_time_spent_in_store':
            if(checked) {
                active_data.forEach(function(venue) { 
                    master_chart.addSeries(venue, 'average_time_spent_in_store', 'date', `${venue[0].venue_name} - Average Time Spent In Store`);
                });
            } else {
                let series_to_remove = $.grep(active_series, function (o, i) {return o.dataFields.valueY === 'average_time_spent_in_store'});
                master_chart.removeSeries(series_to_remove);
                active_series = $.grep(active_series, function (o, i) {return o.dataFields.valueY !== 'average_time_spent_in_store'});
            }
            break;

        default:
          // code block
      }
}
$(document).on('change', '.selectable-metric', function() {
    let selected_metric = $(this).attr('id');
    let selected_type = $(this).data('data-type');
    let checked = this.checked;
    
    addOrRemoveMetric(selected_metric, selected_type, checked);
});

var master_chart = {};
master_chart.init = function() {
    am4core.ready(function() {

        am4core.useTheme(am4themes_animated);

        // let series_data = [];
        // var now = new Date();
        // for (var d = new Date(2020, 05, 01); d <= now; d.setDate(d.getDate() + 1)) {
        //     series_data.push({date: new Date(d), conversions: getRandomInt(5,55), sessions:getRandomInt(100,500)})
        // }


                
        
    
        chart = am4core.create('dashboard_master_chart', am4charts.XYChart);
        // chart.colors.step = 2;
        // chart.data = series_data;

        // var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        // var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

        // var seriesNew = chart.series.push(new am4charts.LineSeries());
        // seriesNew.dataFields.valueY = "conversions";
        // seriesNew.dataFields.dateX = "date";
        // seriesNew.name = "Conversions";
        // seriesNew.tooltipText = "{name}: [bold]{valueY}[/]";

        // var seriesReturning = chart.series.push(new am4charts.LineSeries());
        // seriesReturning.dataFields.valueY = "sessions";
        // seriesReturning.dataFields.dateX = "date";
        // seriesReturning.name = "Sessions";
        // seriesReturning.tooltipText = "{name}: [bold]{valueY}[/]";

        // var seriesTest = chart.series.push(new am4charts.LineSeries());
        // seriesTest.dataFields.valueY = "sessions";
        // seriesTest.dataFields.dateX = "date";
        // seriesTest.name = "Tests";
        // seriesTest.tooltipText = "{name}: [bold]{valueY}[/]";

        chart.legend = new am4charts.Legend();
        chart.cursor = new am4charts.XYCursor();

    });
}

master_chart.removeSeries = function(series) {
    series.forEach(function(series) {
        chart.series.removeIndex(
            chart.series.indexOf(series)
        );
    });
    // active_series.remove(series);
}

master_chart.addSeries = function(data, y, x, name) {
    if (chart.yAxes.length === 0) {
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());    
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());    
    }
    var series = null;
    if (dom.filters().chart_type === 'line') {
        series = new am4charts.LineSeries();
    } else {
        series = new am4charts.ColumnSeries();
    }

    series.data = data;
    series.dataFields.valueY = y;
    series.dataFields.dateX = x;
    series.name = name;
    series.tooltipText = "{name}: [bold]{valueY}[/]";
    // series.stroke = am4core.color("red").lighten(-0.5);
    chart.series.push(series);
    active_series.push(series);
};

// URL FUNCTIONS
 // -- Nothing Yet --
// DOM FUNCTIONS
var dom = {};
dom.filters = function() {
    let type = $('#master_graph_type');
    let span = $('#master_graph_span');
    let view = $('#master_graph_view_by');
    let chart_type = $('#master_graph_chart_type');
    return {
        type: type.val(),
        span: span.val(),
        view: view.val(),
        chart_type: chart_type.val(),
        date_from: date_func.startDate(span.val()),
        date_to: date_func.endDate(span.val())
    }
}

dom.disableContrastingMetics = function(selected_type, remove_all) {
    var selected_types = $('.selectable-metric:checked');
    if (remove_all) {
        $(`.selectable-metric`).removeAttr('disabled');
    } else {
        if (selected_types.length === 0) {
            $(`.selectable-metric[data-data-type!=${selected_type}]`).removeAttr('disabled');
        } else {
            $(`.selectable-metric[data-data-type!=${selected_type}]`).attr('disabled', 'disabled');
        }
    }
}

$(document).on('change', '#master_graph_span', function() {
    resetSeries();
    initSeries();
});

$(document).on('change', '#master_graph_chart_type', function() {
    resetSeries();
    initSeries();
});

function resetSeries() {
    active_series.forEach(function(series) {
        chart.series.removeIndex(
            chart.series.indexOf(series)
        );
    });
    active_series = [];
}

function initSeries() {
    let selected_type = dom.filters().type;
        server.getFirebaseData(function (data) {
            let counter = 0;
            active_data = data;
            
            data.forEach(function(venue) {
                counter++;

                $('.selectable-metric').each(function () {
                    if (this.checked) {
                        let id = $(this).attr('id');
                        let title = id.split('_').map(function(word) {return word.substr(0,1).toUpperCase()+word.substr(1)}).join(' ');
                        master_chart.addSeries(venue, id, 'date', `${venue[0].venue_name} - ${title}`);
                    }
                });
                
                
            });
        });
}

// #region Date Functions
var date_func = {};

date_func.range = function() {
    let span = dom.filters().span;
    let start_date = date_func.startDate(span);
    let end_date = date_func.endDate(span);
    let range = [];
    let active_date = start_date;

    while (active_date !== end_date) {
        range.push(active_date);
        active_date = moment(active_date||start_date).add(1, 'day').format('YYYY-MM-DD')
    }
    range.push(active_date);
    return range;

};
date_func.startDate = function (span) {
    let today = moment();
    switch (span) {
        case 'yesterday':
            return today.subtract(1, 'day').format('YYYY-MM-DD');
            break;
        case 'today':
            return today.format('YYYY-MM-DD')
            break;
        case 'this_week':
            return today.startOf('week').add(1, 'day').format('YYYY-MM-DD'); // Week's data needs to start on Monday
            break;
        case 'last_week':
            return today.startOf('week').subtract(1, 'day').startOf('week').add(1, 'day').format('YYYY-MM-DD');
            break;
        case 'this_month':
            return today.startOf('month').format('YYYY-MM-DD');
            break;
        case 'last_month':
            return today.startOf('month').subtract(1, 'day').startOf('month').format('YYYY-MM-DD');
            break;
        case 'custom':
            return $('#custom_filter_date_from').val();
            break;
        default:
            return today.startOf('week').format('YYYY-MM-DD');
    }
}
date_func.endDate = function (span) {
    let today = moment();
        switch (span) {
            case 'yesterday':
                return today.subtract(1, 'day').format('YYYY-MM-DD');
                break;
            case 'today':
                return today.format('YYYY-MM-DD')
                break;
            case 'this_week':
                return today.endOf('week').add(1, 'day').format('YYYY-MM-DD'); // Week's data needs to end on Sunday
                break;
            case 'last_week':
                return today.startOf('week').subtract(1, 'day').endOf('week').add(1, 'day').format('YYYY-MM-DD');
                break;
            case 'this_month':
                return today.endOf('month').format('YYYY-MM-DD');
                break;
            case 'last_month':
                return today.startOf('month').subtract(1, 'day').endOf('month').format('YYYY-MM-DD');
                break;
            case 'custom':
                return $('#custom_filter_date_to').val();
                break;
            default:
                return today.endOf('week').format('YYYY-MM-DD');
        }
}
// #endregion

// FireStore Functions
var fs = {};
fs.init = function(callback) {
    $.getScript('https://www.gstatic.com/firebasejs/7.1.0/firebase-firestore.js', () => {
        let config = {
          apiKey: "AIzaSyDUxh-Quw0-D6V7Q2Pjcwgeco7R7x08hWw",
          authDomain: "tracks-e61f4.firebaseapp.com",
          databaseURL: "https://tracks-e61f4.firebaseio.com",
          projectId: "tracks-e61f4",
          storageBucket: "",
          messagingSenderId: "798983478031",
          appId: "1:798983478031:web:f81f6341211ab4dfd7bc7a",
          measurementId: "G-9B1XXZ1MXG"
        };
        // firebase.initializeApp(config);
        // firebase.analytics();

        this.db = firebase.firestore()
        this.all = db.collection
        callback();
    });
};

// SERVER FUNCTIONS
var server = {};
server.getFirebaseData = function(callback) {
    let range = date_func.range();
    let promise_array = [];
    let inter_json = [];
    let selected_type = dom.filters().type;
    server.getIDsWithName(function(obj_id) {
        obj_id.forEach(function(id_obj) {
            let id = id_obj.id;
            let name = id_obj.name;
            range.forEach(function(date) {
                promise_array.push(fs.db.collection(id).doc(date).get().then(function(doc){
                    if (selected_type === 'ooh') {
                        if (doc.exists) {
                            let data = doc.data();
                            if (inter_json[id] === undefined) 
                                inter_json[id] = [];
                            inter_json[id].push({
                                date: new Date(date), 
                                venue_name: name, 
                                total_reach:  data.customers_in_store_today,
                                new_reach_rate: Math.floor((data.new_customers_today / data.customers_in_store_today) * 100),
                                return_reach_rate: Math.ceil(((data.customers_in_store_today - data.new_customers_today) / data.customers_in_store_today) * 100),
                                reach_frequency: 1,
                                gross_rate_point: data.customers_in_store_today,
                                average_dwell_time: data.average_dwell,
                                strike_rate: 0,
                                strike_time: 0,
                                strike_distance: 0,
                                potential_sales: 0,
                                roi: 0,
                                cpa: 0
                            });
                        } else {
                            if (inter_json[id] === undefined) 
                                inter_json[id] = [];
                            inter_json[id].push({
                                date: new Date(date), 
                                venue_name: name, 
                                total_reach: 0,
                                new_reach_rate: 0,
                                return_reach_rate: 0,
                                reach_frequency: 0,
                                gross_rate_point: 0,
                                average_dwell_time: 0,
                                strike_rate: 0,
                                strike_time: 0,
                                strike_distance: 0,
                                potential_sales: 0,
                                roi: 0,
                                cpa: 0
                            });
                        }
                    } else {
                        if (doc.exists) {
                            let data = doc.data();
                            // debugger;
                            if (inter_json[id] === undefined) 
                                inter_json[id] = [];
                            inter_json[id].push({
                                date: new Date(date), 
                                venue_name: name, 
                                total_customers_in_store:  data.customers_in_store_today,
                                new_customers_in_store: data.new_customers_today,
                                returning_customers_in_store: (data.customers_in_store_today - data.new_customers_today),
                                total_conversionsn: 0,
                                conversion_rate: 0,
                                bounce_rate: Math.ceil(data.bounce_rate),
                                high_dwell_customers: data.high_dwell_customers,
                                average_time_spent_in_store: Math.ceil(data.average_dwell / 60)
                            });
                        
                        } else {
                            if (inter_json[id] === undefined) 
                                inter_json[id] = [];
                            inter_json[id].push({
                                date: new Date(date), 
                                venue_name: name, 
                                total_customers_in_store: 0,
                                new_customers_in_store: 0,
                                returning_customers_in_store: 0,
                                total_conversionsn: 0,
                                conversion_rate: 0,
                                bounce_rate: 0,
                                high_dwell_customers: 0,
                                average_time_spent_in_store: 0
                            });
                        }
                    }
                }));
            });
        });
        Promise.allSettled(promise_array).then(([result]) => {
            callback(inter_json);
        });
    });
}


server.getIDs = function (callback) {
    $.get('/get_ids', dom.filters(), function(data) {
        callback(data.split(','))
    });
}

server.getIDsWithName = function (callback) {
    $.get('/get_ids_with_name', dom.filters(), function(data) {
        callback(data);
    });
};

server.getOOHSiteData = function () {
    $.get('/get_ooh_site_data', dom.filters(), function(data) {
        debugger;
    });
}

server.getVenueData = function () {
    $.get('/get_venue_site_data', dom.filters, function(data) {
        
    });
}

$(document).ready(function () {
    fs.init(function () {
        master_chart.init();
        server.getFirebaseData(function (data) {
            active_data = data;
            $('#total_reach').click();
        });
    })
    
})

