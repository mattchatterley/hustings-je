function startStreamGraph(data)
{
console.debug(data);

var n = data.length, // number of layers
    m = data[0].Values.length, // number of samples per layer
    x=0,
    stack = d3.layout.stack().offset("wiggle"),
    layers0 = stack(d3.range(n).map(function() { return data[x].Values; })),
    layers1 = stack(d3.range(n).map(function() { return data[x++].Values; }));
    //layers0 = stack(d3.range(n).map(function() { return bumpLayer(m); })),
    //layers1 = stack(d3.range(n).map(function() { return bumpLayer(m); }));

console.debug(data[0].Values);

var width = 960,
    height = 500;

var x = d3.scale.linear()
    .domain([0, m - 1])
    .range([0, width]);

var y = d3.scale.linear()
    .domain([0, d3.max(layers0.concat(layers1), function(layer) { return d3.max(layer, function(d) { return d.y0 + d.y; }); })])
    .range([height, 0]);

var color = d3.scale.linear()
    .range(["#aad", "#556"]);

var area = d3.svg.area()
    .x(function(d) { return x(d.x); })
    .y0(function(d) { return y(d.y0); })
    .y1(function(d) { return y(d.y0 + d.y); });

var svg = d3.select("body").append("svg")
    .attr("width", width)
    .attr("height", height);

svg.selectAll("path")
    .data(layers0)
  .enter().append("path")
    .attr("d", area)
    .style("fill", function() { return color(Math.random()); });

    // TODO: This isnt picking a random colour per set for some reason
}