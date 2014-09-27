function startBarChart(rawData)
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

        dataset.fillColor = "rgba("+rnd+",0,0,0.5)";
        dataset.strokeColor = "rgba("+rnd+",0,0,0.8)";
        dataset.highlightFill = "rgba("+rnd+",0,0,0.75)";
        dataset.highlightStroke = "rgba("+rnd+",0,0,1)";

        console.debug(dataset);

        dataset.data = rawData[i].Values.map(getYCoordinate);

        data.datasets[i] = dataset;
    }

    console.debug(data);

    var options = {};

    var ctx = document.getElementById("chart-placeholder").getContext("2d");
    console.debug(ctx);
    var chart = new Chart(ctx).Bar(data); //, options);

        // TODO: Need to add a key identifying which user is which line - somehow (TBD in JS as we know colours)
}

function getYCoordinate(element)
{
    return element.y;
}

/*
Example usage

var myBarChart = new Chart(ctx).Bar(data, {}});
Data structure

var data = {
    labels: ["January", "February", "March", "April", "May", "June", "July"],
    datasets: [
        {
            label: "My First dataset",
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "rgba(220,220,220,0.75)",
            highlightStroke: "rgba(220,220,220,1)",
            data: [65, 59, 80, 81, 56, 55, 40]
        },
        {
            label: "My Second dataset",
            fillColor: "rgba(151,187,205,0.5)",
            strokeColor: "rgba(151,187,205,0.8)",
            highlightFill: "rgba(151,187,205,0.75)",
            highlightStroke: "rgba(151,187,205,1)",
            data: [28, 48, 40, 19, 86, 27, 90]
        }
    ]
};
The bar chart has the a very similar data structure to the line chart, and has an array of datasets, each with colours and an array of data. Again, colours are in CSS format. We have an array of labels too for display. In the example, we are showing the same data as the previous line chart example.

The label key on each dataset is optional, and can be used when generating a scale for the chart.

Chart Options

These are the customisation options specific to Bar charts. These options are merged with the global chart configuration options, and form the options of the chart.

{
    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
    scaleBeginAtZero : true,

    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines : true,

    //String - Colour of the grid lines
    scaleGridLineColor : "rgba(0,0,0,.05)",

    //Number - Width of the grid lines
    scaleGridLineWidth : 1,

    //Boolean - If there is a stroke on each bar
    barShowStroke : true,

    //Number - Pixel width of the bar stroke
    barStrokeWidth : 2,

    //Number - Spacing between each of the X value sets
    barValueSpacing : 5,

    //Number - Spacing between data sets within X values
    barDatasetSpacing : 1,

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

}
You can override these for your Chart instance by passing a second argument into the Bar method as an object with the keys you want to override.

For example, we could have a bar chart without a stroke on each bar by doing the following:

new Chart(ctx).Bar(data, {
    barShowStroke: false
});
// Th

*/