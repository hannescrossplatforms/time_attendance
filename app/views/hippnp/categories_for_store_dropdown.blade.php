<div class="col-md-4" style="width:30%;">
    <div class="col-md-4" style="width:43%; padding:6px 0px 0px 0px;">
        <label>Category</label>
    </div>
    <div class="col-md-4" style="width:57%;padding:0px 0px 0px 0px;">
        <select id="brandcategory" onchange="change_report_period()" class="form-control"
            name="brandcategory">
            <option value="">Select</option>
            @foreach($data['all_categories_for_filter'] as $category)
            <option value="{{ $category->id }}">
            {{ $category->name }}
            </option>
            @endforeach
        </select>
    </div>
</div>

<script>

    function change_report_period() {

        var time = $("#brandreportperiod").val();

        var category = $("#brandcategory").val();
        var store = $("#brandstore").val();
        var province = $("#brandprovince").val();

        if (time == 'daterange') {
            $('#custom').show();
        } else {
            $('#custom').hide();
            renderCharts(time, '', '', category, store, province);
        }
    }


    function renderCharts(time, start, end, category, store, province) {

        $.ajax({

            url: pathname + 'hippnp/periodchartJsondata',
            type: 'get',
            dataType: 'json',
            data: {
                'period': time,
                'start': start,
                'end': end,
                'category_id': category,
                'store_id': store,
                'province_id': province
            },
            success: function(data) {

                var chartProperties = {
                    "caption": "",
                    "xAxisName": "Category",
                    "yAxisName": "Total dwell time (minutes)",
                    "paletteColors": "#0075c2,#f8b81d,#3CB371",
                    "rotatevalues": "1",
                    "theme": "zune"
                };

                apiChart = new FusionCharts({
                    type: 'mscolumn2d',
                    renderAt: 'staff_wrk',
                    width: '400',
                    height: '350',
                    dataFormat: 'json',
                    dataSource: {
                        "chart": chartProperties,
                        "categories": [{
                            "category": data['category_list']
                        }],
                        "dataset": data['category_list_data']
                    }
                });

                apiChart.render();

                var chartProperties = {
                    "caption": "",
                    "xAxisName": "Category",
                    "yAxisName": "Average dwell time (minutes)",
                    "paletteColors": "#0075c2,#f8b81d,#3CB371",
                    "rotatevalues": "1",
                    "theme": "zune"
                };

                apiChart = new FusionCharts({
                    type: 'mscolumn2d',
                    renderAt: 'staff_wrk_avg',
                    width: '400',
                    height: '350',
                    dataFormat: 'json',
                    dataSource: {
                        "chart": chartProperties,
                        "categories": [{
                            "category": data['category_list']
                        }],
                        "dataset": data['category_list_data_average']

                    }
                });
                apiChart.render();

                var chartProperties = {
                    "caption": "",
                    "xAxisName": "Category",
                    "yAxisName": "Number of visits",
                    "paletteColors": "#0075c2,#f8b81d,#3CB371",
                    "rotatevalues": "1",
                    "theme": "zune"
                };

                apiChart = new FusionCharts({
                    type: 'mscolumn2d',
                    renderAt: 'staff_visits_per_category',
                    width: '400',
                    height: '350',
                    dataFormat: 'json',
                    dataSource: {
                        "chart": chartProperties,
                        "categories": [{
                            "category": data['category_list']
                        }],
                        "dataset": data['category_list_data_visits']

                    }
                });
                apiChart.render();

                // Number of visits per store

                var chartProperties = {
                    "caption": "",
                    "xAxisName": "Store",
                    "yAxisName": "Number of visits per store",
                    "paletteColors": "#0075c2,#f8b81d,#3CB371",
                    "rotatevalues": "1",
                    "theme": "zune"
                };

                apiChart = new FusionCharts({
                    type: 'mscolumn2d',
                    renderAt: 'staff_visits_per_store',
                    width: '400',
                    height: '350',
                    dataFormat: 'json',
                    dataSource: {
                        "chart": chartProperties,
                        "categories": [{
                            "category": data['category_list']
                        }],
                        "dataset": data['category_list_data_visits_store']

                    }
                });
                apiChart.render();


                var chartProperties = {
                    "caption": "",
                    "xAxisName": "Staff Activity",
                    "yAxisName": "Total dwell time (minutes)",
                    "paletteColors": "#0075c2,#f8b81d,#3CB371",
                    "rotatevalues": "1",
                    "theme": "zune"
                };

                staffActivityData = data['staff_list_data'];

                apiChart = new FusionCharts({
                    type: 'mscolumn2d',
                    renderAt: 'staff_activity',
                    width: '400',
                    height: '350',
                    dataFormat: 'json',
                    entityDef: data['entity_dev'],
                    dataSource: {
                        "chart": chartProperties,
                        "categories": [{
                            "category": data['staff_list']
                        }],
                        "dataset": data['staff_list_data']

                    },
                    events: {
                        "dataPlotClick": function (eventObj, dataObj) {
                            let object = staffActivityData[dataObj.datasetIndex];
                            let id = object.data[dataObj.dataIndex][0].id;
                            let staffMemeberID = parseInt(id);

                            window.location.replace("hippnp/periodchartJsondataStaff/" + staffMemeberID);

                        }
                    }
                });
                apiChart.render();






            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {

            }
        });
}

</script>