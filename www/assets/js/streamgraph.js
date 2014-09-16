function startStreamGraph(data)
{
    //var n = 20, // number of layers
    //m = 200, // number of samples per layer
    //var stack = d3.layout.stack().offset("wiggle");
    //layers0 = stack(d3.range(n).map(function() { return bumpLayer(m); })),
    //layers1 = stack(d3.range(n).map(function() { return bumpLayer(m); }));
/*data = [
  {
    "ScreenName": "apples",
    "Values": [
      { "x": 0, "y":  91},
      { "x": 1, "y": 290}
    ]
  },
  {  
    "ScreenName": "oranges",
    "Values": [
      { "x": 0, "y":  9},
      { "x": 1, "y": 49}
    ]
  }
];*/

// note that we need to turn x into a number, not a date
console.debug('data');
console.debug(data);
// we have an array of arrays of objects (users, points)
var stack = d3.layout.stack().offset("wiggle").values(function(d) { console.debug(d); return d.Values; });
console.debug('stack');
console.debug(stack);

    var layers = stack(data);
  console.debug('layers');
  console.debug(layers);
//console.debug(layers);

var width = 960,
    height = 500;

// TODO: Need the max value of x here
var m = 2;


var x = d3.scale.linear()
    .domain([0, m - 1])
    .range([0, width]);

//    .domain([0, d3.max(layers0.concat(layers1), function(layer) { return d3.max(layer, function(d) { return d.y0 + d.y; }); })])
var y = d3.scale.linear()
    .domain([0, d3.max(layers, function(layer) {  return d3.max(layer.Values, function(d) { return d.y0 + d.y; }); })])
    .range([height, 0]);

var color = d3.scale.linear()
    .range(["#aad", "#556"]);


var area = d3.svg.area()
    .x(function(d) { return x(d.x); })
    .y0(function(d) { return y(d.y0); })
    .y1(function(d) { return y(d.y0 + d.y); });

/*
var area = d3.svg.area()
    .x(function(d) { console.debug(d); return x(d.x); })
    //.x(function(d) { return x(1 + d.y); }) // TODO: Need a real X value in here
    .y0(function(d) {console.debug(d);  return y(d.y0); }) // TODO: we only have a single y, so this should be 0???
    .y1(function(d) { console.debug(d); return y(d.y0 + d.y); });
*/
//console.debug(area);
console.debug("here3");

var svg = d3.select("#d3-placeholder").append("svg")
    .attr("width", width)
    .attr("height", height);

/*
svg.selectAll("path")
    .data(layers)
  .enter().append("path")
    .attr("d", area(d.Values))
    .style("fill", function() { return color(Math.random()); });
*/
svg.selectAll("path")
    .data(stack(data))
  .enter().append("path")
    .attr("d", function(d) { return area(d.Values); })
    .style("fill", function() { return color(Math.random()); })
  .append("title")
    .text(function(d) { return d.ScreenName; });

console.debug("streamgraph complete");
}
 /*
function transition() {
  d3.selectAll("path")
      .data(function() {
        var d = layers1;
        layers1 = layers0;
        return layers0 = d;
      })
    .transition()
      .duration(2500)
      .attr("d", area);
}*/
