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
var stack = d3.layout.stack().offset("wiggle").values(function(d) { return d.Values; });
console.debug('stack');
console.debug(stack);

    var layers = stack(data);
  console.debug('layers');
  console.debug(layers);
//console.debug(layers);

var width = 960,
    height = 500;

// TODO: Need the max value of x here
var m = 176;


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


var area = d3.svg.area()
    .x(function(d) { console.debug(d); return x(d.x); })
    .y0(function(d) {console.debug(d);  return y(d.y0); })
    .y1(function(d) { console.debug(d); return y(d.y0 + d.y); });

//console.debug(area);
//console.debug("here3");

var svg = d3.select("#d3-placeholder").append("svg")
    .attr("width", width)
    .attr("height", height);


svg.selectAll("path")
    .data(layers)
  .enter().append("path")
    .attr("d", area)
    .style("fill", function() { return color(Math.random()); });
}
/*
svg.selectAll("path")
    .data(stack(data))
  .enter().append("path")
    .attr("d", function(d) { return area(d.Values); })
    .style("fill", function() { return color(Math.random()); })
  .append("title")
    .text(function(d) { return d.ScreenName; });

console.debug("streamgraph complete");
}*/
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


function altStreamGraph(data)
{
  var n = 20, // number of layers
    m = 200, // number of samples per layer
    stack = d3.layout.stack().offset("wiggle"),
    layers0 = stack(d3.range(n).map(function() { return data[0].Values; })),
    layers1 = stack(d3.range(n).map(function() { return data[0].Values; }));
    //layers0 = stack(d3.range(n).map(function() { return bumpLayer(m); })),
    //layers1 = stack(d3.range(n).map(function() { return bumpLayer(m); }));

console.debug(data);
console.debug(data.Values);
console.debug(data["Values"]);
console.debug(data[0].Values);
console.debug(bumpLayer(m));

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
}

function bumpLayer(n) {

  function bump(a) {
    var x = 1 / (.1 + Math.random()),
        y = 2 * Math.random() - .5,
        z = 10 / (.1 + Math.random());
    for (var i = 0; i < n; i++) {
      var w = (i / n - y) * z;
      a[i] += x * Math.exp(-w * w);
    }
  }

  var a = [], i;
  for (i = 0; i < n; ++i) a[i] = 0;
  for (i = 0; i < 5; ++i) bump(a);
  return a.map(function(d, i) { return {x: i, y: Math.max(0, d)}; });
}