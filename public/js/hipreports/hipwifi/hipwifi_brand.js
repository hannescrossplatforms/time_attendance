

$(document).on('click', '#downloaduserprofiledata', function (event) {

  event.preventDefault();
  downloaduserprofiledata();

});

$(document).on('click', '#downloadlistcustomerusage', function (event) {

  event.preventDefault();
  downloadlistcustomerusage();

});


$(document).on('click', '#submitdaterange', function (event) {
  from = $("#brandfrom").val();
  to = $("#brandto").val();
  brand_id = $('#brandlist').val();
  event.preventDefault();

  $("#loadingModal").modal('show');

  if ($('#brandtab').hasClass('active')) {
    processBrandReports();
  }
  else {
    buildDateRangeReportTableBrand("brand");
  }


});

$(document).on('change', '#brandreportperiod, #brandlist', function () {

  if ($('#brandlist option:selected').data('userdatabtn')) {
    $('#userdatabtn').show();
  } else {
    $('#userdatabtn').hide();
  }

  if ($('#brandlist option:selected').data('logindatabtn')) {
    $('#logindatabtn').show();
  } else {
    $('#logindatabtn').hide();
  }

  if ($("#brandreportperiod").val() == "daterange") {
    $("#branddaterange").show();
  } else {
    $("#branddaterange").hide();
    // debugger;
    processBrandReports();
  }

  showStatsForBrand();

});



function downloadlistcustomerusage() {

  reportperiod = $("#brandreportperiod").val();
  from = $("#brandfrom").val();
  to = $("#brandto").val();

  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: downloadlistcustomerusageurl,
    success: function (filepath) {
      // Initiate the download
      document.getElementById('my_iframe').src = filepath;
    }
  });
}

function downloaduserprofiledata() {

  reportperiod = $("#brandreportperiod").val();
  from = $("#brandfrom").val();
  to = $("#brandto").val();

  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: downloaduserprofiledataurl,
    success: function (filepath) {
      // Initiate the download
      document.getElementById('my_iframe').src = filepath;
    }
  });
}

function buildDateRangeReportTableBrand(report) {

  data = {
    'brand_id': brand_id,
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
    url: branddatasingleurl,
    success: function () {
      $("#loadingModal").modal('hide');
      processBrandReports();
    },
    error: function (e) {
      $("#loadingModal").modal('hide');
    }
  });
}

function processBrandReports() {

  chartWidth = "100%";
  chartHeight = "300";

  console.log("from before = " + from);
  console.log("to before = " + to);

  reportperiod = $("#brandreportperiod").val();
  if (reportperiod == "daterange") {
  } else if (reportperiod == "rep7day") {
    from = moment().subtract(7, 'days').format('YYYY-MM-DD');
    to = moment().format('YYYY-MM-DD');
  } else if (reportperiod == "repthismonth") {
    from = moment().startOf('month').format('YYYY-MM-DD');
    to = moment().format('YYYY-MM-DD');
  } else if (reportperiod == "replastmonth") {
    from = moment().subtract(1, 'month').startOf('month').format('YYYY-MM-DD');
    to = moment().subtract(1, 'month').endOf('month').format('YYYY-MM-DD');
  }

  console.log("frommm = " + from);
  console.log(to);
  brand_id = $('#brandlist').val();
  console.log(brand_id);

  $("#brandage").html("Loading...");
  $("#brandgender").html("Loading...");
  $("#brandincome").html("Loading...");

  $("#brandavgjamvenuedwelltime").html("Loading...");
  console.log("Loading...");

  $("#brandnewthisstore").html("Loading...");
  $("#brandreturningthisstore").html("Loading...");
  $("#brandnewbrandavg").html("Loading...");
  $("#brandreturningbrandavg").html("Loading...");

  $("#brandtotalstoredwelltime").html("Loading...");
  $("#brandavgstoredwelltime").html("Loading...");

  $("#brandtotalwifisessions").html("Loading...");
  $("#brandavgwifisessions").html("Loading...");

  $("#brandtotalwifidatatotal").html("Loading...");
  $("#brandavgwifidatatotal").html("Loading...");

  $("#brandtotalnumberofpeople").html("Loading...");
  $("#brandavgnumberofpeople").html("Loading...");

  $("#brandtotalfirsttimeusers").html("Loading...");
  $("#brandavgfirsttimeusers").html("Loading...");

  $("#brandvenueavgdatapersession").html("Loading...");
  $("#brandbrandavgdatapersession").html("Loading...");

  $("#brandvenueavgtimepersession").html("Loading...");
  $("#brandbrandavgtimepersession").html("Loading...");

  $("#branddwelltimebysessionduration").html("Loading...");
  $("#branddwelltimebyhour").html("Loading...");

  $("#brandavguptime").html("Loading...");


  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: {
      'brand_id': brand_id,
      'reportperiod': reportperiod,
      'from': from,
      'to': to
    },
    url: branddataurl,
    success: function (brandjson) {
      debugger;
      $("#loadingModal").modal('hide');
      console.log(brandjson);
      showBrandPerformanceGraphs(brandjson);
    },
    error: function (e) {
      //Error hannes
      $("#loadingModal").modal('hide');
      debugger;
    }
  });

  //////// age ///
  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'queryname': 'age'
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: branddatasingleurl,
    success: function (agedata) {
      console.log('---------------');
      console.log(agedata);
      console.log('---------------');

      showBrandAge(agedata);
    }
  });

  //////// gender ///
  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'queryname': 'gender'
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: branddatasingleurl,
    success: function (data) {
      console.log(data);
      showBrandGender(data);
    }
  });

  //////// income ///
  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'queryname': 'income'
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: branddatasingleurl,
    success: function (data) {
      console.log(data);
      showBrandIncome(data);
    }
  });

  //////// newvsreturning ///
  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'queryname': 'newvsreturning'
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: branddatasingleurl,
    success: function (data) {
      console.log(data);
      showBrandNewVsReturning(data);
    }
  });

  //////// brandavgjamvenuedwelltime ///
  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'queryname': 'avgjamvenuedwelltime'
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: branddatasingleurl,
    success: function (data) {
      console.log(data);
      showBrandAvgJamVenueDwellTime(data);
    }
  });

  //////// brandtotalstoredwelltime ///
  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'queryname': 'brandtotalstoredwelltime'
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: branddatasingleurl,
    success: function (data) {
      console.log(data);
      showBrandTotalStoreDwellTime(data);
    }
  });

  //////// brandavguptime ///
  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'queryname': 'brandavguptime'
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: branddatasingleurl,
    success: function (data) {
      console.log("brandavguptime : " + data);
      showBrandBrandAvgUptime(data);
    }
  });

  //////// brandtotalwifisessions ///
  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'queryname': 'brandtotalwifisessions'
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: branddatasingleurl,
    success: function (data) {

      console.log("brandtotalwifisessions : " + data);
      showBrandTotalWifiSessions(data);
    }
  });

  //////// brandtotalwifidatatotal ///
  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'queryname': 'brandtotalwifidatatotal'
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: branddatasingleurl,
    success: function (data) {
      console.log("brandtotalwifidatatotal : " + data);
      showBrandTotalWifiDataTotal(data);
    }
  });

  //////// brandtotalnumberofpeople ///
  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'queryname': 'brandtotalnumberofpeople'
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: branddatasingleurl,
    success: function (data) {
      console.log("brandtotalnumberofpeople : " + data);
      showBrandTotalNumberOfPeople(data);
    }
  });

  //////// brandtotalfirsttimeusers ///
  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'queryname': 'brandtotalfirsttimeusers'
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: branddatasingleurl,
    success: function (data) {

      console.log("brandtotalfirsttimeusers : " + data);
      showBrandTotalFirstTimeUsers(data);
    },
    error: function (xhr, err) {

    }
  });

  //////// brandvenueavgdatapersession ///
  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'queryname': 'brandvenueavgdatapersession'
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: branddatasingleurl,
    success: function (data) {
      console.log("brandvenueavgdatapersession : " + data);
      showBrandVenueAvgDataPerSession(data);
    }
  });

  //////// brandvenueavgtimepersession ///
  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'queryname': 'brandvenueavgtimepersession'
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: branddatasingleurl,
    success: function (data) {
      console.log("brandvenueavgtimepersession : " + data);
      showBrandVenueAvgTimePerSession(data);
    }
  });

  //////// dwelltimebysessionduration ///
  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'queryname': 'dwelltimebysessionduration'
  };

  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: branddatasingleurl,
    success: function (dwelltimebysessionduration) {
      showBrandDwellTimeBySessionDuration(dwelltimebysessionduration);
    }
  });

  //////// dwelltimebyhour ///
  data = {
    'brand_id': brand_id,
    'reportperiod': reportperiod,
    'from': from,
    'to': to,
    'queryname': 'dwelltimebyhour'
  };
  $.ajax({
    type: "GET",
    dataType: 'json',
    contentType: "application/json",
    data: data,
    url: branddatasingleurl,
    success: function (dwelltimebyhour) {
      showBrandDwellTimeByHour(dwelltimebyhour);
    }
  });

}


function showBrandAge(data) {
  var age = new FusionCharts({
    type: "mscolumn2d",
    renderAt: "brandage",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: data
  });
  age.render("brandage");
}

function showBrandGender(data) {
  var gender = new FusionCharts({
    type: "stackedbar2d",
    renderAt: "brandgender",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: data
  });
  gender.render("brandgender");
}

function showBrandIncome(data) {
  var age = new FusionCharts({
    type: "mscolumn2d",
    renderAt: "brandincome",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: data
  });
  age.render("brandincome");
}


function showBrandNewVsReturning(data) {
  var newvsreturning = new FusionCharts({
    type: "pie2d",
    renderAt: "brandnewvsreturning",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: data
  });
  newvsreturning.render("brandnewvsreturning");


  $("#brandnewthisstore").html(data.total);
  $("#brandreturningthisstore").html(data.total - data.total);
  $("#brandnewbrandavg").html(Math.round(data.avg));
  $("#brandreturningbrandavg").html(Math.round(data.avg - data.avg));
}

function showBrandAvgJamVenueDwellTime(data) {
  $("#brandavgjamvenuedwelltime").html(data);
}

function showBrandTotalStoreDwellTime(data) {
  $("#brandtotalstoredwelltime").html(data.total);
  $("#brandavgstoredwelltime").html(Math.round(data.avg));
}

function showBrandBrandAvgUptime(data) {
  $("#brandavguptime").html(data);
}

function showBrandTotalWifiSessions(data) {
  $("#brandtotalwifisessions").html(data.total);
  $("#brandavgwifisessions").html(Math.round(data.avg));
}

function showBrandTotalWifiDataTotal(data) {
  $("#brandtotalwifidatatotal").html(data.total.toFixed(2));
  $("#brandavgwifidatatotal").html(data.avg.toFixed(2));
}

function showBrandTotalNumberOfPeople(data) {
  $("#brandtotalnumberofpeople").html(data.total);
  $("#brandavgnumberofpeople").html(Math.round(data.avg));
}

function showBrandTotalFirstTimeUsers(data) {
  $("#brandtotalfirsttimeusers").html(data.total);

  $("#brandavgfirsttimeusers").html(Math.round(data.avg));
}

function showBrandVenueAvgDataPerSession(data) {
  $("#brandvenueavgdatapersession").html(Math.round(data.venue));
  $("#brandbrandavgdatapersession").html(Math.round(data.brand));
}

function showBrandVenueAvgTimePerSession(data) {
  $("#brandvenueavgtimepersession").html(Math.round(data.venue));
  $("#brandbrandavgtimepersession").html(Math.round(data.brand));
}

function showBrandDwellTimeBySessionDuration(branddwelltimebysessionduration) {
  // debugger; // do not remove
  console.log("showDwellTimeBySessionDuration : " + branddwelltimebysessionduration);
  var age = new FusionCharts({
    type: "mscolumn2d",
    renderAt: "branddwelltimebysessionduration",
    width: popupchartWidth,
    height: popupchartHeight,
    dataFormat: "json",
    dataSource: branddwelltimebysessionduration
  });
  age.render("branddwelltimebysessionduration");
}


function showBrandDwellTimeByHour(branddwelltimebyhour) {

  console.log("showDwellTimeByHour : " + branddwelltimebyhour);
  var age = new FusionCharts({
    type: "mscolumn2d",
    renderAt: "branddwelltimebyhour",
    width: popupchartWidth,
    height: popupchartHeight,
    dataFormat: "json",
    dataSource: branddwelltimebyhour
  });
  age.render("branddwelltimebyhour");
}


function showBrandPerformanceGraphs(brandData) {
  var highest5Sessions = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol1row1",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["highest5Sessions"]
  });
  highest5Sessions.render("chartcol1row1");

  var highest5Sessions = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol1row1",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["highest5Sessions"]
  });
  highest5Sessions.render("chartcol1row1");

  var highest5Unique = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol1row2",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["highest5Uniquedata"]
  });
  highest5Unique.render("chartcol1row2");

  var highest5AvgTime = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol1row3",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["highest5AvgTimedata"]
  });
  highest5AvgTime.render("chartcol1row3");

  var lowest5Sessions = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol2row1",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["lowest5Sessionsdata"]
  });
  lowest5Sessions.render("chartcol2row1");

  var lowest5Unique = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol2row2",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["lowest5Uniquedata"]
  });
  lowest5Unique.render("chartcol2row2");

  var lowest5AvgTime = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol2row3",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["lowest5AvgTimedata"]
  });
  lowest5AvgTime.render("chartcol2row3");

  var biggestSessionIncrease = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol3row1",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    showPercentValues: "1",
    dataSource: brandData["biggestSessionIncreasedata"]
  });
  biggestSessionIncrease.render("chartcol3row1");

  var biggestUniquesIncrease = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol3row2",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["biggestUniquesIncreasedata"]
  });
  biggestUniquesIncrease.render("chartcol3row2");

  var biggestAdminDrop = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol3row3",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["biggestAdminDropdata"]
  });
  biggestAdminDrop.render("chartcol3row3");

  var biggestSessionDecrease = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol3row1",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["biggestSessionDecreasedata"]
  });
  biggestSessionDecrease.render("chartcol4row1");

  var biggestUniquesDecrease = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol3row2",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["biggestUniquesDecreasedata"]
  });
  biggestUniquesDecrease.render("chartcol4row2");

  // debugger;
  var biggestAdminIncrease = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol3row3",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["biggestAdminIncreasedata"]
  });

  biggestAdminIncrease.render("chartcol4row3");

  var totalDwellTime = new FusionCharts({
    type: "column2d",
    renderAt: "dwelltimeChart",
    width: "100%",
    height: "400",
    dataFormat: "json",
    dataSource: brandData["totalDwellTimedata"]
  });
  totalDwellTime.render("dwelltimeChart");
}

function showbrandLevelReport(brandData) {
  chartWidth = "100%";
  chartHeight = "300";

  console.log("showbrandLevelReport : " + brandData);

  // BEGIN - Show Brand Data Averages For Demographics And Usage
  var age = new FusionCharts({
    type: "mscolumn2d",
    renderAt: "brandage",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData.age
  });
  age.render("brandage");

  var gender = new FusionCharts({
    type: "stackedbar2d",
    renderAt: "brandgender",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData.gender
  });
  console.log(brandData.gender);
  gender.render("brandgender");

  var income = new FusionCharts({
    type: "mscolumn2d",
    renderAt: "brandincome",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData.income
  });
  income.render("brandincome");

  var newvsreturning = new FusionCharts({
    type: "pie2d",
    renderAt: "brandnewvsreturning",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData.newvsreturning
  });
  newvsreturning.render("brandnewvsreturning");

  $("#brandavgjamvenuedwelltime").html(brandData.avgjamvenuedwelltime);
  console.log("avgjamvenuedwelltime" + brandData.avgjamvenuedwelltime);

  $("#brandnewthisstore").html(brandData.firsttimeusers.total);
  $("#brandreturningthisstore").html(brandData.numberofpeople.total - brandData.firsttimeusers.total);
  $("#brandnewbrandavg").html(Math.round(brandData.firsttimeusers.avg));
  $("#brandreturningbrandavg").html(Math.round(brandData.numberofpeople.avg - brandData.firsttimeusers.avg));

  $("#brandtotalstoredwelltime").html(brandData.storedwelltime.total);
  $("#brandavgstoredwelltime").html(Math.round(brandData.storedwelltime.avg));

  $("#brandtotalwifisessions").html(brandData.totalwifisessions.total);
  $("#brandavgwifisessions").html(Math.round(brandData.totalwifisessions.avg));

  $("#brandtotalwifidatatotal").html(brandData.wifidatatotal.total.toFixed(2));
  $("#brandavgwifidatatotal").html(brandData.wifidatatotal.avg.toFixed(2));

  $("#brandtotalnumberofpeople").html(brandData.numberofpeople.total);
  $("#brandavgnumberofpeople").html(Math.round(brandData.numberofpeople.avg));

  $("#brandtotalfirsttimeusers").html(brandData.firsttimeusers.total);

  $("#brandavgfirsttimeusers").html(Math.round(brandData.firsttimeusers.avg));

  $("#brandvenueavgdatapersession").html(Math.round(brandData.avgdatapersession.venue));
  $("#brandbrandavgdatapersession").html(Math.round(brandData.avgdatapersession.brand));

  $("#brandvenueavgtimepersession").html(Math.round(brandData.avgtimepersession.venue));
  $("#brandbrandavgtimepersession").html(Math.round(brandData.avgtimepersession.brand));
  // END - Show Brand Data Averages For Demographics And Usage





  var highest5Sessions = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol1row1",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["highest5Sessions"]
  });
  highest5Sessions.render("chartcol1row1");

  var highest5Sessions = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol1row1",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["highest5Sessions"]
  });
  highest5Sessions.render("chartcol1row1");

  var highest5Unique = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol1row2",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["highest5Uniquedata"]
  });
  highest5Unique.render("chartcol1row2");

  var highest5AvgTime = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol1row3",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["highest5AvgTimedata"]
  });
  highest5AvgTime.render("chartcol1row3");

  var lowest5Sessions = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol2row1",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["lowest5Sessionsdata"]
  });
  lowest5Sessions.render("chartcol2row1");

  var lowest5Unique = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol2row2",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["lowest5Uniquedata"]
  });
  lowest5Unique.render("chartcol2row2");

  var lowest5AvgTime = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol2row3",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["lowest5AvgTimedata"]
  });
  lowest5AvgTime.render("chartcol2row3");

  var biggestSessionIncrease = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol3row1",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    showPercentValues: "1",
    dataSource: brandData["biggestSessionIncreasedata"]
  });
  biggestSessionIncrease.render("chartcol3row1");

  var biggestUniquesIncrease = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol3row2",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["biggestUniquesIncreasedata"]
  });
  biggestUniquesIncrease.render("chartcol3row2");

  // var biggestAdminDrop = new FusionCharts({
  //   type: "column2d",
  //   renderAt: "chartcol3row3",
  //   width: chartWidth,
  //   height: chartHeight,
  //   dataFormat: "json",
  //   dataSource: brandData["biggestAdminDropdata"]
  // });
  // biggestAdminDrop.render("chartcol3row3");

  var biggestSessionDecrease = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol3row1",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["biggestSessionDecreasedata"]
  });
  biggestSessionDecrease.render("chartcol4row1");

  var biggestUniquesDecrease = new FusionCharts({
    type: "column2d",
    renderAt: "chartcol3row2",
    width: chartWidth,
    height: chartHeight,
    dataFormat: "json",
    dataSource: brandData["biggestUniquesDecreasedata"]
  });
  biggestUniquesDecrease.render("chartcol4row2");

  // var biggestAdminIncrease = new FusionCharts({
  //   type: "column2d",
  //   renderAt: "chartcol3row3",
  //   width: chartWidth,
  //   height: chartHeight,
  //   dataFormat: "json",
  //   dataSource: brandData["biggestAdminIncreasedata"]
  // });
  // biggestAdminIncrease.render("chartcol4row3");

  var totalDwellTime = new FusionCharts({
    type: "column2d",
    renderAt: "dwelltimeChart",
    width: "100%",
    height: "400",
    dataFormat: "json",
    dataSource: brandData["totalDwellTimedata"]
  });
  totalDwellTime.render("dwelltimeChart");
}


FusionCharts.ready(function () {

  console.log("FusionCharts ready function");



});


//printpreview for hiptna charts starts here

function printpreview() {
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

  previewPDF();

}


