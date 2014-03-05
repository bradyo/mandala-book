/*
 * Generate and output SVG content from json Design data
 * example: phantomjs render-svg.js '[{"shapeType":"circle","shapeSize":206,"shapeCount":4,"displacement":377,"angleOffset":0}]'
 */
var system = require('system');
if (system.args.length === 1) {
    console.log('command line param for json input');
    phantom.exit(1);
}
var layers = JSON.parse(system.args[1]);

var page = require('webpage').create();
page.viewportSize = { width: 800, height : 800 };
page.content = '<html><body><div id="canvas"></div></body></html>';

page.injectJs('/../public/lib/jquery-1.8.3.min.js');
page.injectJs('/../public/lib/raphael-min.js');
page.injectJs('/../public/lib/raphael.export.js');
page.injectJs('/../public/js/main.js');
var result = page.evaluate(function(layers) {
    var canvasWidth = 720;
    var canvasHeight = 720;
    var canvas = new Raphael('canvas', canvasWidth, canvasHeight);
    drawDesign(canvas, layers);
    return canvas.toSVG();
}, layers);

console.log(result);
phantom.exit();