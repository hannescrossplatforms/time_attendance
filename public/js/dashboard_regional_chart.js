let regional_chart = null;
let regional_chart_element = $('#regional_dashboard_master_chart');
var regional_active_series = [];
var regional_active_data = [];

Array.prototype.remove = function(set){return this.filter(
    function(e,i,a){return set.indexOf(e)<0}
)};


// CHART FUNCTIONS



function addRegionalDataToChart() {
    if (regionalDom.filters().type === 'ooh') {
        regional_master_chart.addSeries(regional_active_data, 'total_reach', `Total Reach`, 'int');
        regional_master_chart.addSeries(regional_active_data, 'new_reach_rate', `New Reach Rate`, 'perc');
        regional_master_chart.addSeries(regional_active_data, 'return_reach_rate', `Return Reach Rate`, 'perc');
        regional_master_chart.addSeries(regional_active_data, 'reach_frequency', `Frequency`, 'int');
        regional_master_chart.addSeries(regional_active_data, 'gross_rate_point', `Gross Rate Point`, 'int');
        regional_master_chart.addSeries(regional_active_data, 'average_dwell_time', `Average Dwell Time`, 'int');
        regional_master_chart.addSeries(regional_active_data, 'strike_rate', `Strike Rate`, 'perc');
        regional_master_chart.addSeries(regional_active_data, 'strike_time', `Strike Time`, 'int');
        regional_master_chart.addSeries(regional_active_data, 'strike_distance', `Strike Distance`, 'int');
        regional_master_chart.addSeries(regional_active_data, 'potential_sales', `Potential Sales`, 'int');
        regional_master_chart.addSeries(regional_active_data, 'roi', `ROI`, 'perc');
        regional_master_chart.addSeries(regional_active_data, 'cpa', `CPA`, 'int');
    } else {
        regional_master_chart.addSeries(regional_active_data, 'total_customers_in_store', `Total Customers in Store`, 'int');
        regional_master_chart.addSeries(regional_active_data, 'new_customers_in_store', `New Customers in Store`, 'int');
        regional_master_chart.addSeries(regional_active_data, 'returning_customers_in_store', `Returning Customers in Store`, 'int');
        regional_master_chart.addSeries(regional_active_data, 'total_conversions', `Total Conversions`, 'int');
        regional_master_chart.addSeries(regional_active_data, 'conversion_rate', `Conversion Rate`, 'perc');
        regional_master_chart.addSeries(regional_active_data, 'bounce_rate', `Bounce Rate`, 'perc');
        regional_master_chart.addSeries(regional_active_data, 'high_dwell_customers', `High Dwell Customers`, 'int');
        regional_master_chart.addSeries(regional_active_data, 'average_time_spent_in_store', `Average Time Spent in Store`, 'int');

    }

    regional_master_chart.bindEvents();


}

var regional_master_chart = {};
regional_master_chart.init = function() {
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
        regional_chart = am4core.create('regional_dashboard_master_chart', am4charts.XYChart);
        regional_chart.legend = new am4charts.Legend();
        regional_chart.cursor = new am4charts.XYCursor();
    });
}

regional_master_chart.removeSeries = function(series) {
    series.forEach(function(series) {
        regional_chart.series.removeIndex(
            regional_chart.series.indexOf(series)
        );
    });
}

regional_master_chart.addSeries = function(data, y, name, type) {
        var series = null;
        if (regional_chart.yAxes.length === 0) {
            var cat_axis = regional_chart.xAxes.push(new am4charts.CategoryAxis());
            cat_axis.dataFields.category = 'day_cat';
            var valueAxis = regional_chart.yAxes.push(new am4charts.ValueAxis());    
        }
        
        // if (regionalDom.filters().chart_type === 'line') {
        //     series = new am4charts.LineSeries();
        // } else {
            series = new am4charts.ColumnSeries();
        // }

        regional_chart.data = data;
        series.dataFields.valueY = y;
        
        series.dataFields.categoryX = 'day_cat';
            
        series.name = name;
        series.data_type = type;
        series.tooltipText = "{name}: [bold]{valueY}[/]";
        if (regionalDom.filters().chart_type === 'line') {
            series.stacked = true;
        }
        if (type === 'perc') {
            series.showOnInit = false;
            series.hide();
        }
    
        regional_chart.series.push(series);
        regional_active_series.push(series); 
};

var regional_type_showing = 'int';
regional_master_chart.bindEvents = function() {
    regional_chart.legend.itemContainers.template.events.on("hit", function(ev) {
        let series = ev.target.dataItem.dataContext;
        let was_showing = !series.isHiding && !series.isHidden;
        
        let data_type = series.data_type;
        if (regional_type_showing === data_type) {
            if (was_showing) {
                series.show();
            } else { 
                series.hide();
            }
        } else if (regional_type_showing !== data_type) {
            if (was_showing) {
                series.hide();
            } else {
                regional_type_showing = data_type
                let series_to_remove = regional_active_series.filter((sseries) => {return sseries.data_type !== data_type});
                let series_to_activate = regional_active_series.filter((sseries) => {return sseries.data_type === data_type});

                let event_hide_series = series_to_remove.map((sseries) => {sseries.hide()});
                let event_show_series = series_to_activate.map((sseries) => {sseries.show()});

                 window.setTimeout(function(e) {
                    series.show();
                }, 200)
            }
        }  
    })
}



// URL FUNCTIONS
 // -- Nothing Yet --
// DOM FUNCTIONS
var regionalDom = {};
regionalDom.filters = function() {
    let type = $('#regional_metrics_graph_type');
    let span = $('#regional_metrics_graph_span');
    let group = $('#regional_metrics_group_by');
    let chart_type = $('#regional_metrics_chart_type');
    return {
        type: type.val(),
        span: span.val(),
        group: group.val(),
        chart_type: chart_type.val(),
        date_from: date_func.startDate(span.val()),
        date_to: date_func.endDate(span.val())
    }
}

$(document).on('change', '#regional_metrics_graph_type', function() {
    regional_chart.dispose();
    regional_master_chart.init();
    resetRegionalSeries();
    initRegionalSeries();
});

$(document).on('change', '#regional_metrics_graph_span', function() {
    regional_chart.dispose();
    regional_master_chart.init();
    resetRegionalSeries();
    initRegionalSeries();
});


$(document).on('change', '#regional_metrics_group_by', function() {
    regional_chart.dispose();
    regional_master_chart.init();
    resetRegionalSeries();
    initRegionalSeries();
});

$(document).on('change', '#regional_metrics_chart_type', function() {
    regional_chart.dispose();
    regional_master_chart.init();
    resetRegionalSeries();
    initRegionalSeries();
});


function resetRegionalSeries() {
    if (regional_chart.series.length !== 0) {
        regional_active_series.forEach(function(series) {
            regional_chart.series.removeIndex(
                regional_chart.series.indexOf(series)
            );
        });
    }
    regional_active_series = [];
}

function initRegionalSeries() {
    regional_server.getFirebaseData(function (data) {
        regional_active_data = data;
        addRegionalDataToChart();
        regional_chart.events.on('ready', () => {
            let initial_display = regional_active_series.filter((s) => {return s.data_type === 'perc'});
            initial_display.map((s) => {s.hide()})
        })
    });
}

// #region Date Functions
var regional_date_func = {};

regional_date_func.range = function() {
    let span = regionalDom.filters().span;
    let start_date = regional_date_func.startDate(span);
    let end_date = regional_date_func.endDate(span);
    let range = [];
    let active_date = start_date;

    while (active_date !== end_date) {
        range.push(active_date);
        active_date = moment(active_date||start_date).add(1, 'day').format('YYYY-MM-DD')
    }
    range.push(active_date);
    return range;

};
regional_date_func.startDate = function (span) {
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
regional_date_func.endDate = function (span) {
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
var regional_fs = {};
regional_fs.init = function(callback) {
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
        try {
            regional_fs.db = firebase.firestore()
            regional_fs.all = regional_fs.db.collection
            callback();
        } catch {
            firebase.initializeApp(config);
            regional_fs.db = firebase.firestore()
            regional_fs.all = regional_fs.db.collection
            callback();
        }
        // if (!firebase)
        //     firebase.initializeApp(config);
        // firebase.analytics();
    });
};

// SERVER FUNCTIONS
var regional_server = {};
regional_server.getFirebaseData = function(callback) {
    let range = regional_date_func.range();
    let promise_array = [];
    let master_promise = [];
    let inter_json = [];
    let selected_type = regionalDom.filters().type;
    let selected_group = regionalDom.filters().group;
    let hour_array = [];
    // Get all venue IDs
    regional_server.getIDsWithRegion(function(region_groups) {
        let total_reach = 0,
        new_reach = 0,
        return_reach = 0,
        reach_frequency = 0,
        gross_rate_point = 0,
        dwell_time = 0,
        strike_rate = 0,
        strike_time = 0,
        strike_distance = 0,
        potential_sales = 0,
        roi = 0,
        cpa = 0;

        let total_customers_in_store = 0,
        new_customers_in_store = 0,
        returning_customers_in_store = 0,
        total_conversions = 0,
        conversion_rate = 0,
        bounce_rate = 0,
        high_dwell_customers = 0,
        average_time_spent_in_store = 0,
        bounces = 0;

        region_groups.forEach(function(region_with_ids) {
            console.log('in region_groups')
            let ids = region_with_ids.ids.split(',');
            debugger;
            console.log(`IDs Are: ${ids}`);
            ids.forEach(function(venue_id) {
                console.log('in IDs')
                range.forEach(function(date) {
                    console.log('in range')
                    // Declare dates for date furture date checking
                    let series_date = moment(date);
                    let todays_date = moment();
                    // If the date requested is not in the future
                    if (series_date <= todays_date) {
                        promise_array.push(regional_fs.db.collection(venue_id).doc(date).get().then(function(doc){
                            if (selected_type === 'ooh') {
                                if (doc.exists) {
                                    let data = doc.data();
                                    total_reach += data.customers_in_store_today;
                                    new_reach += data.new_customers_today;
                                    return_reach += data.customers_in_store_today - data.new_customers_today;
                                    reach_frequency = 1;
                                    gross_rate_point += data.customers_in_store_today;
                                    dwell_time += data.average_dwell;
                                    strike_rate += 0;
                                    strike_time += 0;
                                    strike_distance += 0;
                                    potential_sales += 0;
                                    roi += 0;
                                    cpa += 0;
                                }
                            } else {
                                if (doc.exists) {
                                    let data = doc.data();
                                    total_customers_in_store += data.customers_in_store_today;
                                    new_customers_in_store += data.new_customers_today;
                                    returning_customers_in_store += (data.customers_in_store_today - data.new_customers_today);
                                    total_conversions += 0;
                                    conversion_rate += 0;
                                    bounces = (data.bounces || 0);
                                    // bounce_rate += (data.bounces || 0);
                                    high_dwell_customers += (data.high_dwell_customers || 0);
                                    average_time_spent_in_store += Math.ceil(data.average_dwell / 60);
                                }
                            }
                        }));
                    }
                });
            });
            master_promise.push(
                Promise.allSettled(promise_array).then(([result]) => {
                    if (selected_type === 'ooh') {
                        inter_json.push({
                            day_cat: selected_group === 'province' ? region_with_ids.province_name  : region_with_ids.city_name,
                            // venue_name: name, 
                            total_reach:  total_reach,
                            new_reach_rate: Math.floor((new_reach / total_reach) * 100),
                            return_reach_rate: Math.ceil(((return_reach) / total_reach) * 100),
                            reach_frequency: 1,
                            gross_rate_point: total_reach,
                            average_dwell_time: Math.floor(dwell_time / range.length),
                            strike_rate: 0,
                            strike_time: 0,
                            strike_distance: 0,
                            potential_sales: 0,
                            roi: 0,
                            cpa: 0
                        });
                    } else {
                        inter_json.push({
                            day_cat: selected_group === 'province' ? region_with_ids.province_name  : region_with_ids.city_name,
                            // venue_name: name, 
                            total_customers_in_store: total_customers_in_store,
                            new_customers_in_store: new_customers_in_store,
                            returning_customers_in_store: returning_customers_in_store,
                            total_conversions: 0,
                            conversion_rate: 0,
                            bounce_rate: Math.floor(((bounces || 0) / total_customers_in_store) * 100),
                            high_dwell_customers: high_dwell_customers,
                            average_time_spent_in_store: Math.floor(average_time_spent_in_store / range.length)
                        });
                    }
                })
            )
        });   
        Promise.allSettled(master_promise).then(([result]) => {
            console.log(`REGIONAL DATA: ${inter_json}`)
            callback(inter_json);
        });       
    });
    
}

regional_server.getIDs = function (callback) {
    $.get('/get_ids', regionalDom.filters(), function(data) {
        callback(data.split(','))
    });
}

regional_server.getIDsWithName = function (callback) {
    $.get('/get_ids_with_name', regionalDom.filters(), function(data) {
        callback(data);
    });
};

regional_server.getIDsWithRegion = function(callback) {
    $.get('/get_ids_with_region', regionalDom.filters(), function(data) {
        callback(data);
    });
}

regional_server.getOOHSiteData = function () {
    $.get('/get_ooh_site_data', regionalDom.filters(), function(data) {
        
    });
}

regional_server.getVenueData = function () {
    $.get('/get_venue_site_data', regionalDom.filters, function(data) {
        
    });
}

$(document).ready(function () {
    if ($('#regional_dashboard_master_chart').length !== 0) {
        regional_fs.init(function () {
            regional_master_chart.init();
            initRegionalSeries();
        })
    }
})

