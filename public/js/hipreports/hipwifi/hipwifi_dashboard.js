
$(function() {

    $("#totalsessions").html(dashboarddata.sessions.total);
    $("#currentsessions").html(Math.round(dashboarddata.sessions.currentsessions) + " Online");

    $("#totaldwelltime").html(Math.round(dashboarddata.dwelltime.total)); // Added / 60 for ten percentil fix
    $("#avgdwelltime").html("Avg " + Math.round(dashboarddata.dwelltime.avg)); // Added / 60 for ten percentil fix   

  });