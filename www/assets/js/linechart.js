function startLineChart(rawData, placeholderId)
{
    console.debug(rawData);
//    var n = data.length, // number of layers
//    m = data[0].Values.length, // number of samples per layer

    var data = {
        labels: new Array(rawData[0].Values.length),
        datasets: new Array(rawData.length)
    };

    // set labels
    data.labels = rawData[0].Labels;

    // create datasets
    for(var i=0;i<rawData.length;i++)
    {
        var dataset = {};
        dataset.label = "DataSet " + (i+1); // change to real data

        var rnd = 75 + parseInt(Math.random() * (255-75), 10);

        console.debug(rnd);

        dataset.fillColor = "rgba(0,0,0,0)";
        dataset.strokeColor = "rgba("+rnd+",0,0,0.8)";
        pointColor = "rgba("+rnd+",220,220,1)",
        pointStrokeColor = "#fff",
        pointHighlightFill = "#fff",
        pointHighlightStroke = "rgba("+rnd+",220,220,1)",

        console.debug(dataset);

        if(rawData[0].Multidimensional)
        {
            dataset.data = expandYValues(rawData[i].Values);
        }
        else
        {
            dataset.data = rawData[i].Values.map(getYCoordinate);
        }

        data.datasets[i] = dataset;
    }

    console.debug(data);

    var options = {
        scaleBeginAtZero: false
    };

    var ctx = document.getElementById(placeholderId).getContext("2d");
    console.debug(ctx);
    var chart = new Chart(ctx).Line(data, options);

        // TODO: Need to add a key identifying which user is which line - somehow (TBD in JS as we know colours)

        // TODO: Not convinced current method of one up one down is right, we should really have a red layer (positive) and a blue (negative)???
}
