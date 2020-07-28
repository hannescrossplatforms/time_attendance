let chart = null;
let chart_element = $('#dashboard_master_chart');
var active_series = [];
var active_data = [];

Array.prototype.remove = function(set){return this.filter(
    function(e,i,a){return set.indexOf(e)<0}
)};

$(document).ready(function () {
    let location_dropdown = $('#master_graph_chart_location');
    if (location_dropdown.length !== 0) {
        location_dropdown.select2({
            multiple: true
        });
    }
});


// CHART FUNCTIONS



function addDataToChart() {
    // dom.disableContrastingMetics(selected_type, false);
    if (dom.filters().type === 'ooh') {
        master_chart.addSeries(active_data, 'total_reach', `Total Reach`, 'int');
        master_chart.addSeries(active_data, 'new_reach_rate', `New Reach Rate`, 'perc');
        master_chart.addSeries(active_data, 'return_reach_rate', `Return Reach Rate`, 'perc');
        master_chart.addSeries(active_data, 'reach_frequency', `Frequency`, 'int');
        master_chart.addSeries(active_data, 'gross_rate_point', `Gross Rate Point`, 'int');
        master_chart.addSeries(active_data, 'average_dwell_time', `Average Dwell Time`, 'int');
        master_chart.addSeries(active_data, 'strike_rate', `Strike Rate`, 'perc');
        master_chart.addSeries(active_data, 'strike_time', `Strike Time`, 'int');
        master_chart.addSeries(active_data, 'strike_distance', `Strike Distance`, 'int');
        master_chart.addSeries(active_data, 'potential_sales', `Potential Sales`, 'int');
        master_chart.addSeries(active_data, 'roi', `ROI`, 'perc');
        master_chart.addSeries(active_data, 'cpa', `CPA`, 'int');
    } else {
        master_chart.addSeries(active_data, 'total_customers_in_store', `Total Customers in Store`, 'int');
        master_chart.addSeries(active_data, 'new_customers_in_store', `New Customers in Store`, 'int');
        master_chart.addSeries(active_data, 'returning_customers_in_store', `Returning Customers in Store`, 'int');
        master_chart.addSeries(active_data, 'total_conversions', `Total Conversions`, 'int');
        master_chart.addSeries(active_data, 'conversion_rate', `Conversion Rate`, 'perc');
        master_chart.addSeries(active_data, 'bounce_rate', `Bounce Rate`, 'perc');
        master_chart.addSeries(active_data, 'high_dwell_customers', `High Dwell Customers`, 'int');
        master_chart.addSeries(active_data, 'average_time_spent_in_store', `Average Time Spent in Store`, 'int');

    }

    master_chart.bindEvents();


}

var master_chart = {};
master_chart.init = function() {
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
        chart = am4core.create('dashboard_master_chart', am4charts.XYChart);
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
}

master_chart.addSeries = function(data, y, name, type) {
        var series = null;
        if (chart.yAxes.length === 0) {
            if (dom.filters().view === 'date') {
                var cat_axis = chart.xAxes.push(new am4charts.DateAxis());
            } else {
                var cat_axis = chart.xAxes.push(new am4charts.CategoryAxis());
                cat_axis.dataFields.category = 'day_cat';
            }
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());    
        }
        
        if (dom.filters().chart_type === 'line') {
            series = new am4charts.LineSeries();
        } else {
            series = new am4charts.ColumnSeries();
        }
        // debugger;
        chart.data = data;
        series.dataFields.valueY = y;
        
        if (dom.filters().view === 'date')
            series.dataFields.dateX = 'date';
        else
            series.dataFields.categoryX = 'day_cat';
            
        series.name = name;
        series.data_type = type;
        series.tooltipText = "{name}: [bold]{valueY}[/]";
        if (type === 'perc') {
            series.showOnInit = false;
            series.hide();
        }
    
        chart.series.push(series);
        active_series.push(series); 
};

var type_showing = 'int';
master_chart.bindEvents = function() {
    chart.legend.itemContainers.template.events.on("hit", function(ev) {
        let series = ev.target.dataItem.dataContext;
        let was_showing = !series.isHiding && !series.isHidden;
        
        let data_type = series.data_type;
        if (type_showing === data_type) {
            if (was_showing) {
                series.show();
            } else { 
                series.hide();
            }
        } else if (type_showing !== data_type) {
            if (was_showing) {
                series.hide();
            } else {
                type_showing = data_type
                let series_to_remove = active_series.filter((sseries) => {return sseries.data_type !== data_type});
                let series_to_activate = active_series.filter((sseries) => {return sseries.data_type === data_type});

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
var dom = {};
dom.filters = function() {
    let type = $('#master_graph_type');
    let span = $('#master_graph_span');
    let view = $('#master_graph_view_by');
    let chart_type = $('#master_graph_chart_type');
    let location = $('#master_graph_chart_location');
    return {
        type: type.val(),
        span: span.val(),
        view: view.val(),
        location: location.val() !== '' ? location.val().join(',') : '0',
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
    chart.dispose();
    master_chart.init();
    resetSeries();
    initSeries();
});

$(document).on('change', '#master_graph_chart_type', function() {
    chart.dispose();
    master_chart.init();
    resetSeries();
    initSeries();
});

$(document).on('change', '#master_graph_type', function() {
    chart.dispose();
    master_chart.init();
    $(`#master_graph_chart_location option[data-track-type="${dom.filters().type}"]`).removeAttr('disabled');
    $(`#master_graph_chart_location option[data-track-type!="${dom.filters().type}"]`).attr('disabled', 'disabled')
    resetSeries();
    initSeries();
});

$(document).on('change', '#master_graph_chart_location', function() {
    chart.dispose();
    master_chart.init();
    resetSeries();
    initSeries();
});

$(document).on('change', '#master_graph_view_by', function() {
    chart.dispose();
    master_chart.init();
    resetSeries();
    initSeries();
});


function resetSeries() {
    if (chart.series.length !== 0) {
        active_series.forEach(function(series) {
            chart.series.removeIndex(
                chart.series.indexOf(series)
            );
        });
    }
    active_series = [];
}

function initSeries() {
    server.getFirebaseData(function (data) {
        active_data = data;
        addDataToChart();
        chart.events.on('ready', () => {
            let initial_display = active_series.filter((s) => {return s.data_type === 'perc'});
            initial_display.map((s) => {s.hide()})
        })
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
        try {
            fs.db = firebase.firestore()
            fs.all = fs.db.collection
            callback();
        } catch {
            firebase.initializeApp(config);
            fs.db = firebase.firestore()
            fs.all = fs.db.collection
            callback();
        }
    });
};

// SERVER FUNCTIONS
var server = {};

server.getFirebaseData = function(callback) {
    let range = date_func.range();
    let promise_array = [];
    let master_promise = [];
    let inter_json = [];
    let selected_type = dom.filters().type;
    let hour_array = [];
    // Get all venue IDs
    server.getIDsWithName(function(obj_id) {
        console.log(obj_id)
        // Loop through each date
        range.forEach(function(date) {
            // Zeroise running totals for new date
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

            // Declare dates for date furture date checking
            let series_date = moment(date);
            let todays_date = moment();
            // If the date requested is not in the future
            if (series_date <= todays_date) {
                // Loop through venue IDs
                obj_id.forEach(function(id_obj) {
                    let id = id_obj.id;
                    
                    if (dom.filters().view === 'hour') {
                        // ## ----------------------------------------------------- ## //
                        // ## ------------------- DATA FOR HOUR ------------------- ## //
                        // ## ----------------------------------------------------- ## //
                        promise_array.push(fs.db.collection(id).doc(date).collection('hourly').get().then(function(hourly_collection){
                            if (selected_type === 'ooh') {
                                let hourly_docs = hourly_collection.docs;
                                // debugger;
                                if (hourly_docs.length !== 0) {
                                    $.each(hourly_docs, function(hour) {
                                        let current_hour = this.id;
                                        let current_instance = this.data();
                                        let existing_position = $.grep(hour_array, function(ha) {return ha.day_cat === current_hour})[0];
                                        current_instance['day_cat'] = current_hour;
                                        if (existing_position === undefined) {   
                                            console.log('INIT HOUR');
                                            hour_array.push({
                                                day_cat: current_hour,
                                                total_reach: current_instance.customers,
                                                new_reach: current_instance.new_customers,
                                                reach_frequency: 1,
                                                gross_rate_point: current_instance.customers,
                                                average_dwell_time: Math.floor((current_instance.average_dwell || 0) / (range.length * 24)),
                                                strike_rate: 0,
                                                strike_time: 0,
                                                strike_distance: 0,
                                                potential_sales: 0,
                                                roi: 0,
                                                cpa: 0
                                            })     
                                        } else {
                                            $.each(hour_array, function(i, mp) {
                                                if (mp.day_cat === current_hour){
                                                    mp['total_reach'] = mp.total_reach + current_instance.customers;
                                                    mp['new_reach'] = mp.new_reach + current_instance.new_customers;
                                                    mp['reach_frequency'] = 1;
                                                    mp['gross_rate_point'] = mp.total_reach + current_instance.customers;
                                                    mp['average_dwell_time'] = mp.average_dwell_time + Math.floor((current_instance.average_dwell || 0) / (range.length * 24));
                                                    mp['strike_rate'] = 0;
                                                    mp['strike_time'] = 0;
                                                    mp['strike_distance'] = 0;
                                                    mp['potential_sales'] = 0;
                                                    mp['roi'] = 0;
                                                    mp['cpa'] = 0;
                                                }
                                            });
                                        }
                                    })
                                } else {
                                    console.log(`Hourly data doesn't exist for this date, skip `)
                                  // Hourly data doesn't exist for this date, skip  
                                }
                            } else {
                                let hourly_docs = hourly_collection.docs;
                                if (hourly_docs.length !== 0) {
                                    $.each(hourly_docs, function(hour) {
                                        let current_hour = this.id;
                                        let current_instance = this.data();
                                        let existing_position = $.grep(hour_array, function(ha) {return ha.day_cat === current_hour})[0];
                                        current_instance['day_cat'] = current_hour;

                                        if (existing_position === undefined) {   
                                            hour_array.push({
                                                day_cat: current_hour,
                                                total_customers_in_store: current_instance.customers,
                                                new_customers_in_store: current_instance.new_customers,
                                                returning_customers_in_store: (current_instance.customers - current_instance.new_customers),
                                                total_conversions: 0,
                                                conversion_rate: 0,
                                                bounce_rate: Math.floor((current_instance.bounces / current_instance.total_sessions) / (range.length * 24)),
                                                high_dwell_customers: 0,
                                                average_time_spent_in_store: Math.floor((current_instance.average_dwell || 0) / (range.length * 24))
                                            })     
                                        } else {
                                            $.each(hour_array, function(i, mp) {
                                                if (mp.day_cat === current_hour){
                                                    mp.total_customers_in_store = mp.total_customers_in_store + current_instance.customers;
                                                    mp.new_customers_in_store = mp.new_customers_in_store + current_instance.new_customers;
                                                    mp.returning_customers_in_store = mp.returning_customers_in_store + (current_instance.customers - current_instance.new_customers);
                                                    mp.total_conversions = mp.total_conversions + 0;
                                                    mp.conversion_rate = mp.conversion_rate + 0;
                                                    mp.bounce_rate = mp.bounce_rate + Math.floor((current_instance.bounces / current_instance.total_sessions) / (range.length * 24));
                                                    mp.high_dwell_customers = 0;
                                                    mp.average_time_spent_in_store = mp.average_time_spent_in_store + Math.floor((current_instance.average_dwell || 0) / (range.length * 24))
                                                }
                                            });
                                        }
                                    })
                                } else {
                                  // Hourly data doesn't exist for this date, skip  
                                }
                            }
                        }));
                        // ## ----------------------------------------------------- ## //
                        // ## ----------------------------------------------------- ## //
                        // ## ----------------------------------------------------- ## //
                    } else {
                        // ## ----------------------------------------------------- ## //
                        // ## ------DATA FOR DATE | DAY OF WEEK | MONTH | YEAR----- ## //
                        // ## ----------------------------------------------------- ## //
                        promise_array.push(fs.db.collection(id).doc(date).get().then(function(doc){
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
                        // ## ----------------------------------------------------- ## //
                        // ## ----------------------------------------------------- ## //
                        // ## ----------------------------------------------------- ## //
                    }
                    
                });   
                master_promise.push(
                    Promise.allSettled(promise_array).then(([result]) => {
                        if (dom.filters().view === 'hour') {
                            // skip this as the data is already compiled and needs no further processing
                        } else {
                            if (selected_type === 'ooh') {
                                inter_json.push({
                                    date: new Date(date), 
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
                                    date: new Date(date), 
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
                        }
                    })
                )
            }  
        });
        Promise.allSettled(master_promise).then(([result]) => {
            switch(dom.filters().view) {
                case 'date':
                    console.log(`MASTER DATA: ${inter_json}`)
                    callback(inter_json);
                  break;
                case 'hour':
                    server.calculateAveragesForHour(hour_array, function(filtered_json) {
                        callback(filtered_json);
                    })
                  break;
                case 'day':
                    server.filterForDay(inter_json, 'dddd', function(filtered_json) {
                        callback(filtered_json);
                    });
                break;
                case 'month':
                    server.filterForDay(inter_json, 'MMMM', function(filtered_json) {
                        callback(filtered_json);
                    });
                break;
                case 'year':
                    server.filterForDay(inter_json, 'YYYY', function(filtered_json) {
                        callback(filtered_json);
                    });
                break;
                default:
                    callback(inter_json);
            }
        });
    });
}

server.calculateAveragesForHour = function(json, callback) {
    $.each(json, function(j) {
        let current_hour_stats = this;
        current_hour_stats['new_reach_rate'] = Math.ceil((current_hour_stats.new_reach / current_hour_stats.total_reach) * 100);
        current_hour_stats['return_reach_rate'] = Math.floor(((current_hour_stats.total_reach - current_hour_stats.new_reach) / current_hour_stats.total_reach) * 100);
    });
    json.sort(function(a, b) {
        return parseFloat(a.day_cat) - parseFloat(b.day_cat);
    });
    callback(json);
}

server.filterForDay = function(json, filter, callback) {
    let day_array = []
    $.each(json, function(obj) {                    
        let current_instance = this;
        let week_day = moment(current_instance.date).format(filter);
        let existing_position = $.grep(day_array, function(da) {return da.day_cat === week_day})[0];
        
        current_instance['day_cat'] = week_day;
        if (existing_position === undefined) {        
            day_array.push(current_instance); 
        } else { 
            $.each(day_array, function(i, mp) {
                if (mp.day_cat === week_day){
                    if (dom.filters().type === 'ooh') {
                        console.log(`Current: ${mp.total_reach}; Adding: ${current_instance.total_reach}`)
                        mp['total_reach'] = mp.total_reach + current_instance.total_reach;
                        mp['new_reach_rate'] = mp.new_reach_rate + Math.floor(current_instance.new_reach_rate / json.length);
                        mp['return_reach_rate'] = mp.return_reach_rate + Math.ceil(current_instance.return_reach_rate / json.length);
                        mp['reach_frequency'] = mp.reach_frequency + current_instance.reach_frequency;
                        mp['gross_rate_point'] = mp.gross_rate_point + current_instance.gross_rate_point;
                        mp['average_dwell_time'] = mp.average_dwell_time + Math.floor(current_instance.average_dwell_time / json.length);
                        mp['strike_rate'] = 0;
                        mp['strike_time'] = 0;
                        mp['strike_distance'] = 0;
                        mp['potential_sales'] = 0;
                        mp['roi'] = 0;
                        mp['cpa'] = 0;
                    } else {
                        mp.total_customers_in_store = mp.total_customers_in_store + current_instance.total_customers_in_store;
                        mp.new_customers_in_store = mp.new_customers_in_store + current_instance.new_customers_in_store;
                        mp.returning_customers_in_store = mp.returning_customers_in_store + current_instance.returning_customers_in_store;
                        mp.total_conversions = mp.total_conversions + current_instance.total_conversions;
                        mp.conversion_rate = mp.conversion_rate + (Math.floor(current_instance.conversion_rate / json.length));
                        mp.bounce_rate = mp.bounce_rate + Math.floor(current_instance.bounce_rate / json.length);
                        mp.high_dwell_customers = mp.high_dwell_customers + current_instance.high_dwell_customers;
                        mp.average_time_spent_in_store = mp.average_time_spent_in_store + Math.floor(current_instance.average_time_spent_in_store / json.length)
                    }
                }
            });
        }
    });
    callback(day_array);
};

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
    if ($('#dashboard_master_chart').length !== 0) {
        fs.init(function () {
            master_chart.init();
            initSeries();
        })
    }
})

