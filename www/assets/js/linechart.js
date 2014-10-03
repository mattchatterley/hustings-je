function startLineChart(rawData, placeholderId)
{
    console.debug(rawData);
    console.debug(rawData[0]);
//    var n = data.length, // number of layers
//    m = data[0].Values.length, // number of samples per layer

    var data = {
        labels: new Array(rawData[0].Labels.length),
        datasets: new Array(0)
    };

//console.debug(rawData[0].Labels.length);
//console.debug(rawData.length);
    // set labels
    data.labels = rawData[0].Labels;

    // figure out how to allocate colours, based on a range of 80-255
    var startColour = 80;
    var available = 255 - startColour;
    var perStep = Math.floor(available / rawData.length);

    var current = startColour;
//console.debug(current);
//console.debug(perStep);
    // create datasets
    for(var i=0;i<rawData.length;i++)
    {
        var dataset = {};
        dataset.label = "@" + rawData[i].ScreenName + ( rawData[0].Multidimensional ? " - positive" : "");

        //var seedColour = 65 + parseInt(Math.random() * 120);        
        var rSeed = 0 + Math.floor(Math.random() * 200);        
        var gSeed = 0 + Math.floor(Math.random() * 200);        
        var bSeed = 0 + Math.floor(Math.random() * 200);        

        var rLight = rSeed + 30;
        var rDark = rSeed - 30;
        var gLight = gSeed + 30;
        var gDark = gSeed - 30;
        var bLight = bSeed + 30;
        var bDark = bSeed - 30;

        dataset.fillColor = "rgba(0,0,0,0)";
        dataset.strokeColor = "rgba("+rLight+","+gLight+","+bLight+",0.8)";
        pointColor = "rgba("+rLight+","+gLight+","+bLight+",1)",
        pointStrokeColor = "#fff",
        pointColor = "rgba("+rLight+","+gLight+","+bLight+",1)",
        pointHighlightFill = "#fff",
        pointHighlightStroke = "rgba("+rLight+","+gLight+","+bLight+",1)",

        dataset.data = rawData[i].Values.map(getYCoordinate);
        //console.debug(dataset.data);

        data.datasets.push(dataset);

        if(rawData[0].Multidimensional)
        {
            //console.debug("multi - adding 2nd set");
            var dataset = {};
            dataset.label = "@" + rawData[i].ScreenName + ( rawData[0].Multidimensional ? " - negative" : "");

            dataset.fillColor = "rgba(0,0,0,0)";
            dataset.strokeColor = "rgba("+rDark+","+gDark+","+bDark+",0.8)";
            pointColor = "rgba("+rDark+","+gDark+","+bDark+",1)",
            pointStrokeColor = "#fff",
            pointHighlightFill = "#fff",
            pointHighlightStroke = "rgba("+rDark+","+gDark+","+bDark+",1)",

            dataset.data = rawData[i].Values.map(getYCoordinate2);
            //console.debug(dataset.data);

            data.datasets.push(dataset);
        }        
    }

    Chart.defaults.global.animationSteps = 30;

//    console.debug(data);

    var options = {
        scaleBeginAtZero: false,
        datasetFill: false,
        showTooltips: true,
        responsive: true,
        multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>",
        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\">&nbsp;</span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
    };

    var ctx = document.getElementById(placeholderId).getContext("2d");
  //  console.debug(ctx);
    var chart = new Chart(ctx).Line(data, options);

    var legend = chart.generateLegend();
    console.debug(legend);
    $('#' + placeholderId + "-legend").append(legend);
        // TODO: Need to add a key identifying which user is which line - somehow (TBD in JS as we know colours)
}
