/*
 * Generate and output SVG content from json Design
 * example:
 * phantomjs render-svg.js '{id:123,data:[{"shapeType":"circle","shapeSize":50,"shapeCount":4,"displacement":100}]}'
 */
var system = require('system');
if (system.args.length === 1) {
    console.log('command line param for json input');
    phantom.exit(1);
}

var page = require('webpage').create();
page.viewportSize = { width: 720, height : 720};
page.content = '<html><body><div id="canvas"></div></body></html>';

page.injectJs('/../public/lib/jquery-1.8.3.min.js');
page.injectJs('/../public/lib/raphael-min.js');
page.injectJs('/../public/lib/raphael.export.js');
page.injectJs('/../public/js/main.js');

var design = JSON.parse(system.args[1]);
var result = page.evaluate(
    function(design) {
        var canvas = makeCanvas('canvas');
        drawDesign(canvas, design);
        return canvas.toSVG();
    },
    design
);

console.log(result);
phantom.exit();