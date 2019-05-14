    function showmembergraphs(external_user_id, name)  {

      modalhtml = 
        '<div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\n\
          <div class="modal-dialog memberModalClass">\n\
            <div class="modal-content">\n\
              <div class="modal-header">\n\
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>\n\
                <h6 class="modal-title" id="myModalLabel">' + name + '</h6>\n\
              </div>\n\
              <div class="modal-body">\n\
                <div id="memberAbsence"></div> \n\
                <div id="memberTimeManagement"></div> \n\
                <div id="memberWSproximity"></div> \n\
            </div>\n\
          </div>\n\
        </div>\n\
      </div>';
      $( '#memberGraphModalhtml' ).html(modalhtml);

      link = '<a href="#" id="memberModalLink" data-toggle="modal" data-target="#memberModal"></a>';
      $( '#memberModalLinkDiv' ).html(link);
      $('#memberModalLink').click();




      var chartProperties = {
          "caption": "",
          "xAxisName": "Day", // This whould be the full name in the format "Surname, first names"
          "yAxisName": "Absenteeism",
          "rotatevalues": "1",
          "theme": "zune",
          "showYAxisValues": "0"
      };

      apiChart = new FusionCharts({
          type: 'column2d',
          renderAt: 'memberAbsence',
          width: '700',
          height: '150',
          dataFormat: 'json',
          dataSource: {
              "chart": chartProperties,
        "data": [
            {
                "label": "01-07-2016",
                "value": "1",
                "displayValue": " ",
                "tooltext": "Holiday"
            },
            {
                "label": "02-07-2016",
                "value": "0",
            },
            {
                "label": "03-07-2016",
                "value": "1",
                "displayValue": " ",
                "color": "#008867",
                "tooltext": "Sick"
            },
            {
                "label": "04-07-2016",
                "value": "0",
            },
            {
                "label": "05-07-2016",
                "value": "0",
            },
         ]

          }
      });
      apiChart.render();

      var chartProperties = {
          "caption": "",
          "xAxisName": "Day", // This whould be the full name in the format "Surname, first names"
          "yAxisName": "Lateness",
          "rotatevalues": "1",
          "theme": "zune"
      };

      apiChart = new FusionCharts({
          type: 'column2d',
          renderAt: 'memberTimeManagement',
          width: '700',
          height: '150',
          dataFormat: 'json',
          dataSource: {
              "chart": chartProperties,
        "data": [
            {
                "label": "01-07-2016",
                "value": "0",
            },
            {
                "label": "02-07-2016",
                "value": "25",
            },
            {
                "label": "03-07-2016",
                "value": "0",
            },
            {
                "label": "04-07-2016",
                "value": "0",
            },
            {
                "label": "05-07-2016",
                "value": "6",
            },
         ]

          }
      });
      apiChart.render();

      var chartProperties = {
          "caption": "",
          "xAxisName": "Day", // This whould be the full name in the format "Surname, first names"
          "yAxisName": "WS Proximity",
          "rotatevalues": "1",
          "theme": "zune"
      };

      apiChart = new FusionCharts({
          type: 'column2d',
          renderAt: 'memberWSproximity',
          width: '700',
          height: '150',
          dataFormat: 'json',
          dataSource: {
              "chart": chartProperties,
        "data": [
            {
                "label": "01-07-2016",
                "value": "0",
            },
            {
                "label": "02-07-2016",
                "value": "32",
            },
            {
                "label": "03-07-2016",
                "value": "0",
            },
            {
                "label": "04-07-2016",
                "value": "7",
            },
            {
                "label": "05-07-2016",
                "value": "15",
            },
         ]

          }
      });
      apiChart.render();

    }