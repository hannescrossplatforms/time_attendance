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
