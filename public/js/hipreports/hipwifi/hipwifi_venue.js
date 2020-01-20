
$(document).on('click', '#submitvenuedaterange', function (event) {
  from = $("#venuefrom").val();
  to = $("#venueto").val();
  event.preventDefault();

  $("#loadingModal").modal('show');
  buildDateRangeReportTableVenue("venue");

});

$(document).on('change', '#venuereportperiod, #venuelist', function () {

  if ($("#venuereportperiod").val() == "daterange") {
    $("#venuedaterange").show();
  } else {
    $("#venuedaterange").hide();
    processVenueReports();
  }

});

function buildDateRangeReportTableVenue(report) {

  data = {
    'venue_id': venue_id,
    'reportperiod': "daterange",
    'from': from,
    'to': to,
    'queryname': 'builddaterangereporttable'
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function () {
      $("#loadingModal").modal('hide');
      processVenueReports();
    }
  });
}

function processVenueReports() {

  // $( document ).on( 'change', '#venuereportperiod, #venuebrandcompare', function () {

  vchartWidth = "100%";
  vchartHeight = "300";

  reportperiod = $("#venuereportperiod").val();
  if (reportperiod == "rep7day") {
    from = moment().subtract(7, 'days').format('YYYY-MM-DD');
    to = moment().format('YYYY-MM-DD');
  } else if (reportperiod == "repthismonth") {
    from = moment().startOf('month').format('YYYY-MM-DD');
    to = moment().format('YYYY-MM-DD');
  } else if (reportperiod == "replastmonth") {
    from = moment().subtract(1, 'month').startOf('month').format('YYYY-MM-DD');
    to = moment().subtract(1, 'month').endOf('month').format('YYYY-MM-DD');
  }

  console.log(from);
  console.log(to);
  console.log(venue_id);

  venuebrandcompare = $("#venuebrandcompare").val();
  console.log("venuebrandcompare : " + venuebrandcompare);
  console.log("reportperiod : " + reportperiod);

  $("#age").html("Loading...");
  $("#gender").html("Loading...");
  $("#income").html("Loading...");

  $("#newvsreturning").html("Loading...");
  $("#newvsreturningtext").hide();

  $("#totalstoredwelltime").html("");
  $("#avgstoredwelltime").html("Loading...");

  $("#totalwifisessions").html("");
  $("#avgwifisessions").html("Loading...");

  $("#totalwifidatatotal").html("");
  $("#avgwifidatatotal").html("Loading...");

  $("#totalnumberofpeople").html("");
  $("#avgnumberofpeople").html("Loading...");

  $("#totalfirsttimeusers").html("");
  $("#avgfirsttimeusers").html("Loading...");

  $("#venueavgdatapersession").html("");
  $("#brandavgdatapersession").html("Loading...");

  $("#venueavgtimepersession").html("");
  $("#brandavgtimepersession").html("Loading...");

  $("#dwelltimebysessionduration").html("Loading...");
  $("#dwelltimebyhour").html("Loading...");

  $("#venueuptime").html("Loading...");
  $("#venuebrandavguptime").html("Loading...");

  //////// venuedetails ///
  data = {
    'venue_id': venue_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'venuebrandcompare': venuebrandcompare,
    'queryname': 'venuedetails'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function (venuedetailsdata) {
      showVenueDetails(venuedetailsdata);
    }
  });

  //////// age ///
  data = {
    'venue_id': venue_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'venuebrandcompare': venuebrandcompare,
    'queryname': 'age'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function (age) {
      showAge(age);
    }
  });


  //////// gender ///
  data = {
    'venue_id': venue_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'venuebrandcompare': venuebrandcompare,
    'queryname': 'gender'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function (genderdata) {
      showGender(genderdata);
    }
  });

  //////// income ///
  data = {
    'venue_id': venue_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'venuebrandcompare': venuebrandcompare,
    'queryname': 'income'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function (incomedata) {
      showIncome(incomedata);
    }
  });


  //////// uptime ///
  data = {
    'venue_id': venue_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'venuebrandcompare': venuebrandcompare,
    'queryname': 'uptime'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function (uptime) {
      showVenueUptime(uptime);
    }
  });


  //////// avgjamvenuedwelltime ///
  data = {
    'venue_id': venue_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'venuebrandcompare': venuebrandcompare,
    'queryname': 'avgjamvenuedwelltime'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function (avgjamvenuedwelltimedata) {
      showAvgjamvenuedwelltimePeople(avgjamvenuedwelltimedata);
    }
  });


  //////// newvsreturning ///
  data = {
    'venue_id': venue_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'venuebrandcompare': venuebrandcompare,
    'queryname': 'newvsreturning'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function (newvsreturningdata) {
      showNewvsreturning(newvsreturningdata);
      $("#newvsreturningtext").show();
    }
  });



  //////// numberofpeople ///
  data = {
    'venue_id': venue_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'venuebrandcompare': venuebrandcompare,
    'queryname': 'numberofpeople'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function (numberofpeople) {
      showVenueNumberofPeople(numberofpeople);
    }
  });


  //////// firsttimeusers ///
  //Hannes hier
  data = {
    'venue_id': venue_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'venuebrandcompare': venuebrandcompare,
    'queryname': 'firsttimeusers'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function (firsttimeusers) {
      showFirstTimeUsers(firsttimeusers);

    }
  });

  //////// avgdatapersession ///
  data = {
    'venue_id': venue_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'venuebrandcompare': venuebrandcompare,
    'queryname': 'avgdatapersession'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function (avgdatapersession) {
      showAvgDataPerSessions(avgdatapersession);

    }
  });


  //////// totalwifisessions ///
  data = {
    'venue_id': venue_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'venuebrandcompare': venuebrandcompare,
    'queryname': 'totalwifisessions'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function (totalwifisessionsdata) {
      showTotalWifiSessions(totalwifisessionsdata);
    }
  });

  //////// wifidatatotal ///
  data = {
    'venue_id': venue_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'venuebrandcompare': venuebrandcompare,
    'queryname': 'wifidatatotal'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function (wifidatatotaldata) {
      showWifiDataTotal(wifidatatotaldata);
    }
  });

  //////// avgtimepersession ///
  data = {
    'venue_id': venue_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'venuebrandcompare': venuebrandcompare,
    'queryname': 'avgtimepersession'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function (avgtimepersessiondata) {
      console.log(avgtimepersessiondata);
      showAvgTimePerSession(avgtimepersessiondata);
    }
  });


  //////// dwelltimebysessionduration ///
  data = {
    'venue_id': venue_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'venuebrandcompare': venuebrandcompare,
    'queryname': 'dwelltimebysessionduration'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function (dwelltimebysessionduration) {
      showDwellTimeBySessionDuration(dwelltimebysessionduration);
    }
  });

  //////// dwelltimebyhour ///
  data = {
    'venue_id': venue_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'venuebrandcompare': venuebrandcompare,
    'queryname': 'dwelltimebyhour'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: venuedatasingleurl,
    success: function (dwelltimebyhour) {
      showDwellTimeByHour(dwelltimebyhour);
    }
  });

  /////////////////// OLD CODE BEGIN - ALL QUERIES IN A SINGLE CALL
  // $.ajax({
  //   type: "GET",
  //   dataType: 'json',
  //   contentType: "application/json",
  //   data: { 
  //     'venue_id': venue_id, 
  //     'reportperiod': reportperiod,
  //     'from': from, 
  //     'to': to,
  //     'venuebrandcompare': venuebrandcompare
  //   },
  //   url: venuedataurl,
  //   success: function(venuejson) {
  //     showVenueLevelReport(venuejson);
  //     $("#newvsreturningtext").show();

  //   }
  // });
  /////////////////// OLD CODE BEGIN - ALL QUERIES IN A SINGLE CALL

}

function showVenueDetails(venuedetailsdata) {
  $("#sitename").html(venuedetailsdata.sitename);
}

function showAge(agedata) {
  console.log("agedata : " + agedata);
  var age = new FusionCharts({
    type: "mscolumn2d",
    renderAt: "age",
    width: vchartWidth,
    height: vchartHeight,
    dataFormat: "json",
    dataSource: agedata
  });
  age.render("age");
}

function showGender(genderdata) {
  var gender = new FusionCharts({
    type: "stackedbar2d",
    renderAt: "gender",
    width: vchartWidth,
    height: vchartHeight,
    dataFormat: "json",
    dataSource: genderdata
  });
  gender.render("gender");
}

function showIncome(incomedata) {
  var income = new FusionCharts({
    type: "stackedbar2d",
    renderAt: "income",
    width: vchartWidth,
    height: vchartHeight,
    dataFormat: "json",
    dataSource: incomedata
  });
  income.render("income");
}

function showVenueUptime(uptime) {

  $("#venueuptime").html(uptime.total);
  $("#venuebrandavguptime").html("Brand Avg " + uptime.avg);
  // $("#venuebrandavguptime").html("Brand Avg " + Math.round(uptime.avg, 2)); 
}

function showAvgjamvenuedwelltimePeople(avgjamvenuedwelltimedata) {
  $("#avgjamvenuedwelltime").html(avgjamvenuedwelltimedata);
}

function showNewvsreturning(newvsreturningdata) {
  var newvsreturning = new FusionCharts({
    type: "pie2d",
    renderAt: "newvsreturning",
    width: vchartWidth,
    height: vchartHeight,
    dataFormat: "json",
    dataSource: newvsreturningdata.graph
  });
  $("#newthisstore").html(newvsreturningdata.firsttimeusers.total);
  $("#returningthisstore").html(newvsreturningdata.numberofpeople.total - newvsreturningdata.firsttimeusers.total);
  $("#newbrandavg").html(Math.round(newvsreturningdata.firsttimeusers.avg));
  $("#returningbrandavg").html(Math.round(newvsreturningdata.numberofpeople.avg - newvsreturningdata.firsttimeusers.avg));
  newvsreturning.render("newvsreturning");
}


function showVenueNumberofPeople(numberofpeople) {

  $("#totalnumberofpeople").html(numberofpeople.total);
  $("#avgnumberofpeople").html("Brand Avg " + Math.round(numberofpeople.avg));
}


function showFirstTimeUsers(firsttimeusers) {
  //Hannes hier
  debugger;
  $("#totalfirsttimeusers").html(firsttimeusers.total);
  $("#avgfirsttimeusers").html("Brand Avg " + Math.round(firsttimeusers.avg));
}


function showAvgDataPerSessions(avgdatapersession) {
  $("#venueavgdatapersession").html(Math.round(avgdatapersession.venue));
  $("#brandavgdatapersession").html("Brand Avg " + Math.round(avgdatapersession.brand));
}


function showTotalWifiSessions(totalwifisessionsdata) {
  $("#totalwifisessions").html(totalwifisessionsdata.total);
  $("#avgwifisessions").html("Brand Avg " + Math.round(totalwifisessionsdata.avg));
}

function showWifiDataTotal(wifidatatotaldata) {
  $("#totalwifidatatotal").html(wifidatatotaldata.total.toFixed(2));
  $("#avgwifidatatotal").html("Brand Avg " + wifidatatotaldata.avg.toFixed(2));
}

function showAvgTimePerSession(avgtimepersessiondata) {
  $("#venueavgtimepersession").html(Math.round(avgtimepersessiondata.venue));
  $("#brandavgtimepersession").html("Brand Avg " + Math.round(avgtimepersessiondata.brand));
}

function showDwellTimeBySessionDuration(dwelltimebysessionduration) {

  console.log("showDwellTimeBySessionDuration : " + dwelltimebysessionduration);
  var age = new FusionCharts({
    type: "mscolumn2d",
    renderAt: "dwelltimebysessionduration",
    width: popupchartWidth,
    height: popupchartHeight,
    dataFormat: "json",
    dataSource: dwelltimebysessionduration
  });
  age.render("dwelltimebysessionduration");
}


function showDwellTimeByHour(dwelltimebyhour) {

  console.log("showDwellTimeByHour : " + dwelltimebyhour);
  var age = new FusionCharts({
    type: "mscolumn2d",
    renderAt: "dwelltimebyhour",
    width: popupchartWidth,
    height: popupchartHeight,
    dataFormat: "json",
    dataSource: dwelltimebyhour
  });
  age.render("dwelltimebyhour");
}


function showVenueLevelReport(venuejson) {
  // console.log(venuejson);

  $("#sitename").html(venuejson.sitename);

  var age = new FusionCharts({
    type: "mscolumn2d",
    renderAt: "age",
    width: vchartWidth,
    height: vchartHeight,
    dataFormat: "json",
    dataSource: venuejson.age
  });
  age.render("age");

  var gender = new FusionCharts({
    type: "stackedbar2d",
    renderAt: "gender",
    width: vchartWidth,
    height: vchartHeight,
    dataFormat: "json",
    dataSource: venuejson.gender
  });
  gender.render("gender");

  var income = new FusionCharts({
    type: "mscolumn2d",
    renderAt: "income",
    width: vchartWidth,
    height: vchartHeight,
    dataFormat: "json",
    dataSource: venuejson.income
  });
  income.render("income");

  var newvsreturning = new FusionCharts({
    type: "pie2d",
    renderAt: "newvsreturning",
    width: vchartWidth,
    height: vchartHeight,
    dataFormat: "json",
    dataSource: venuejson.newvsreturning
  });
  newvsreturning.render("newvsreturning");

  $("#avgjamvenuedwelltime").html(venuejson.avgjamvenuedwelltime);

  $("#newthisstore").html(venuejson.firsttimeusers.total);
  $("#returningthisstore").html(venuejson.numberofpeople.total - venuejson.firsttimeusers.total);
  $("#newbrandavg").html(Math.round(venuejson.firsttimeusers.avg));
  $("#returningbrandavg").html(Math.round(venuejson.numberofpeople.avg - venuejson.firsttimeusers.avg));

  $("#totalstoredwelltime").html(venuejson.storedwelltime.total);
  $("#avgstoredwelltime").html("Brand Avg " + Math.round(venuejson.storedwelltime.avg));

  $("#totalwifisessions").html(venuejson.totalwifisessions.total);
  $("#avgwifisessions").html("Brand Avg " + Math.round(venuejson.totalwifisessions.avg));

  $("#totalwifidatatotal").html(venuejson.wifidatatotal.total.toFixed(2));
  $("#avgwifidatatotal").html("Brand Avg " + venuejson.wifidatatotal.avg.toFixed(2));

  $("#totalnumberofpeople").html(venuejson.numberofpeople.total);
  $("#avgnumberofpeople").html("Brand Avg " + Math.round(venuejson.numberofpeople.avg));

  $("#totalfirsttimeusers").html(venuejson.firsttimeusers.total);
  $("#avgfirsttimeusers").html("Brand Avg " + Math.round(venuejson.firsttimeusers.avg));

  $("#venueavgdatapersession").html(Math.round(venuejson.avgdatapersession.venue));
  $("#brandavgdatapersession").html("Brand Avg " + Math.round(venuejson.avgdatapersession.brand));

  $("#venueavgtimepersession").html(Math.round(venuejson.avgtimepersession.venue));
  $("#brandavgtimepersession").html("Brand Avg " + Math.round(venuejson.avgtimepersession.brand));
};

//printpreview for hipwifi venue charts starts here

function printVenuePreview() {
  $('#loadingDiv').show();
  pathname = $('#url').val();

  var fusioncharts_container = {};
  $("span[class='fusioncharts-container']").each(function (index, elem) {
    var spanId = $(this).attr('id');
    spanId = spanId.split("-");
    fusioncharts_container[spanId[1]] = $(this).html();
  });

  var fusionchartspans = fusioncharts_container;
  var fusionImg = $.ajax({
    type: 'POST',
    dataType: 'json',
    async: false,
    url: pathname + 'hipwifi_convertsvgtoimage',
    data: {
      'fusionchartspans': fusionchartspans
    },
    success: function (result) {
      $('#loadingDiv').hide();
    }

  });

  var fusionImages = fusionImg.responseJSON.result_img;
  $("span[class='fusioncharts-container']").each(function (index, elem) {
    $(this).removeAttr('style');
    var spanId = $(this).attr('id');
    spanId = spanId.split("-");
    var image_path = 'fc_images/image_temp/' + fusionImages['img_' + spanId[1]];
    $(this).html('<img src="' + image_path + '">');

  });

  var i = 1;
  $("#fusion-chart .col-sm-6").each(function (index, elem) {

    if (i % 2 == 0) {
      $(this).addClass('col-6-right-al');
    } else {
      $(this).addClass('col-6-left-al');
    }
    i++;
  });

  previewVenuePDF();

}