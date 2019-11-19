function printpreview() {
  $('#loadingDiv').show();
  pathname = $('#url').val();

  var fusioncharts_container = {};
  $("span[class='fusioncharts-container']").each(function(index, elem) {
    var spanId = $(this).attr('id');
    spanId = spanId.split('-');
    fusioncharts_container[spanId[1]] = $(this).html();
  });

  var fusionchartspans = fusioncharts_container;
  var fusionImg = $.ajax({
    type: 'POST',
    dataType: 'json',
    async: false,
    url: pathname + 'hippnp_convertsvgtoimage',
    data: {
      fusionchartspans: fusionchartspans
    },
    success: function(result) {
      $('#loadingDiv').hide();
    }
  });

  var fusionImages = fusionImg.responseJSON.result_img;
  $("span[class='fusioncharts-container']").each(function(index, elem) {
    $(this).removeAttr('style');
    var spanId = $(this).attr('id');
    spanId = spanId.split('-');
    var image_path = 'fc_images/image_temp/' + fusionImages['img_' + spanId[1]];
    $(this).html('<img src="' + image_path + '">');
  });

  var i = 1;
  $('#fusion-chart .col-sm-6').each(function(index, elem) {
    if (i % 2 == 0) {
      $(this).addClass('col-6-right-al');
    } else {
      $(this).addClass('col-6-left-al');
    }
    i++;
  });

  previewPDF();
}

function loadChartPopoutJS() {
  var opened_element = null;
  $(document).on('click', '.popup-chart', function(e) {
    console.info(e.target.toString());
    if (opened_element === null && e.target.toString() !== '[object HTMLButtonElement]') {
      opened_element = $(this);
      $(this).fadeOut('fast', function() {
        let chart_id = $(this).data('fusion-id');
        let chart = FusionCharts.items[chart_id];
        $(this).addClass('chart-popup');
        chart.resizeTo('100%', '100%');

        if (opened_element.has('button').length == 0) {
          opened_element.prepend('<button class="close-popup-chart">X</button>');
        }
        $(this).fadeIn('fast');
      });
    }
  });
  $(document).on('click', '.close-popup-chart', function() {
    if (opened_element !== null) {
      opened_element.fadeOut('fast', function() {
        let chart_id = opened_element.data('fusion-id');
        let chart = FusionCharts.items[chart_id];
        opened_element.removeClass('chart-popup');
        chart.resizeTo(400, 350);
        opened_element.fadeIn('fast');
        $('.close-popup-chart').remove();
        opened_element = null;
      });
    }
  });
}

function removeFusionCharts() {
  if (FusionCharts.items.staff_activity_chart !== undefined) {
    FusionCharts.items.staff_activity_chart.dispose();
  }

  if (FusionCharts.items.staff_visits_per_category_chart !== undefined) {
    FusionCharts.items.staff_visits_per_category_chart.dispose();
  }

  if (FusionCharts.items.staff_visits_per_store_chart !== undefined) {
    FusionCharts.items.staff_visits_per_store_chart.dispose();
  }

  if (FusionCharts.items.staff_wrk_avg_chart !== undefined) {
    FusionCharts.items.staff_wrk_avg_chart.dispose();
  }

  if (FusionCharts.items.staff_wrk_chart !== undefined) {
    FusionCharts.items.staff_wrk_chart.dispose();
  }
}
