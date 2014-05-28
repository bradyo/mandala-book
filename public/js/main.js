
function makeCanvas(parentDivId) {
    // set canvas size to 720 px but enable browser auto scaling below
    var width = 720;
    var height = 720;
    var canvas = new Raphael(parentDivId, width, height);
    canvas.setViewBox(0, 0, width, height, true);

    // allow svg to scale to parent div by removing width and height attributes
    var svg = $('#' + parentDivId + ' > svg');
    svg.removeAttr("width");
    svg.removeAttr("height");

    return canvas;
}

function drawDesign(canvas, design, currentLayerIndex) {
    currentLayerIndex = (typeof currentLayerIndex !== 'undefined' ? currentLayerIndex : -1);

    // use draw method based on design creation date (support for legacy draw code)
   if (design.createdAt >= "2014-05-02") {
       drawLayers(canvas, design.layers, currentLayerIndex);
   } else {
       drawLayersLegacy(canvas, design.layers, currentLayerIndex);
   }
}

function drawLayers(canvas, layers, currentLayerIndex) {
    canvas.clear();
    for (var layerIndex = 0; layerIndex < layers.length; layerIndex++) {
        var layer = layers[layerIndex];
        var isSelected = (currentLayerIndex == layerIndex);
        drawLayer(canvas, layer, isSelected);
    }
}

function drawLayer(canvas,  layer, isSelected) {
    // render by shape type
    var shapePath;
    switch (layer.shapeType) {
        case 'circle':
            drawCircleLayer(canvas, layer, isSelected);
            break;

        case 'triangle':
            shapePath = "M71.5,123.84163274117472C71.5,123.84163274117472,-71.5,123.84163274117472,-71.5,123.84163274117472C-71.5,123.84163274117472,0,0,0,0C0,0,71.5,123.84163274117472,71.5,123.84163274117472Z";
            drawPathLayer(canvas, layer, shapePath, isSelected);
            break;

        case 'star':
            shapePath = "M 233.000 253.000 L 256.511 265.361 L 252.021 239.180 L 271.042 220.639 L 244.756 216.820 L 233.000 193.000 L 221.244 216.820 L 194.958 220.639 L 213.979 239.180 L 209.489 265.361 L 233.000 253.000 Z";
            drawPathLayer(canvas, layer, shapePath, isSelected);
            break;

        case 'heart':
            shapePath = "M 263.41570,235.14588 C 197.17570,235.14588 143.41575,288.90587 143.41575,355.14588 C 143.41575,489.90139 279.34890,525.23318 371.97820,658.45392 C 459.55244,526.05056 600.54070,485.59932 600.54070,355.14588 C 600.54070,288.90588 546.78080,235.14587 480.54070,235.14588 C 432.49280,235.14588 391.13910,263.51631 371.97820,304.33338 C 352.81740,263.51630 311.46370,235.14587 263.41570,235.14588 z";
            drawPathLayer(canvas, layer, shapePath, isSelected);
            break;

        case 'square':
            shapePath = "M 0 0 L 0 1 L 1 1 L 1 0 Z";
            drawPathLayer(canvas, layer, shapePath, isSelected);
            break;

        case 'diamond':
            shapePath = "M47.5,-2.5C47.5,-2.5,72.5,37.5,72.5,37.5C72.5,37.5,47.5,62.5,47.5,62.5C47.5,62.5,22.5,37.5,22.5,37.5C22.5,37.5,47.5,-2.5,47.5,-2.5Z";
            drawPathLayer(canvas, layer, shapePath, isSelected);
            break;

        case 'leaf':
            shapePath = "M157.75,11.815c6.76,9.954,3.64,26.724-4.45,28.809,0.95,7.206-2.09,26.755-10.79,13.069-7.63-9.847-1.22,14.958,0.25,18.795,6.06,11.683-2.49,27.752-12.09,11.952-5.7-5.213-18.17-25.204-15.57-6.92,3.96,12.419,5.61,23.53,10.56,35.07,11.97,17.5,13.3,39.98,11.43,60.52-1.02,13.26-17.21-5.08-21.84-10.24-10.92-12.32-17.118-27.83-27.103-40.8,3.137,12.05-9.921,23.79-17.724,10.02-5.528-8.01-13.665-16.44-21.191-11.58-12.168-6.48-23.715-13.23-37.63-15.98-7.9165,3.49,11.754,6.38,12.68,12.93,6.508,5.44,4.67,12.26,6.875,16.11,13.185,4.66,14.118,18.15,0.248,15.78,9.108,9.44,28.902,10.19,32.643,23.26-4.054,10.56-23.465,2.97-25.315,7.86,9.901,7.17,24.356,6.85,32.842,13.18,12.699-1.32,24.135,6.24,32.645,14.8-6.395,12.56-26.276,4.35-37.86,8.38-10.709,9.06-27.548,6.32-40.982,5.29,12.194,5.7,26.878,11.42,39.67,11.12,9.76-1.22,7.494,8.51,7.058,9.92,8.807-0.11,29.144-11.85,30.514,0.82-6.556,6.4-4.382,10.48,3.28,4.49,14.79-9.3,33.21-12.47,49.66-15,12.95,2.4,28.18,0.33,41.46,7.38,7.53,2.18,15.28-10.34,25.58-5.35,5.79-0.32,17.22,5.93,14.06-2.98,6.6-3.88,21.75,3.73,21.03-4.71,7.51-3.98,30.1,1.68,29.57-4.41-14.22-1.9-28.17-6.23-40.04-10.77-9.36-0.64-37.09,4.13-31.28-11.17,11.03-8.52,25.04-14.26,39.17-13.03,6.57-3.99,12.81-7.86,22.06-10.12,5.14-1.35,17.73-5.08,4.83-5.65-7.63-4.08-30.37-0.06-26.22-12.57,9.47-8.16,24.21-9.69,30.77-21.18-13.7,7.6-11.28-12.86-0.12-13.89-1.08-12.34,12.57-19.48,21.58-27.663-4.98-2.274-21.37,9.133-30.56,12.843-7.53,7.17-21.42,11.86-20.41-2.11-8.84,8.68-12,24.99-25.37,28.25-6.64-2.88-0.83-31.74-9.27-15.79-12.05,16.68-16.42,40.22-35.36,51.06-11.01,0.43-5.95-18.36-6.36-26.08,1.42-12.6,8.08-23.91,16.87-32.68,3.62-6.33-4.85-3.59,1.34-10.39,3.85-6.932,15.8-22.842,9.46-26.434-7.97,4.316-21.54,20.72-27.79,13.772-1-11.724,8.58-39.489,3.72-40.908-2.41,9.104-17.37,11.077-11.85-1.57,2.28-10.562-8.97-8.532-5.9-19.852-0.06-6.263-1.9-13.709-8.78-15.653z";
            drawPathLayer(canvas, layer, shapePath, isSelected);
            break;

        default:
            var shapeTemplateId = 'shape-template-' + layer.shapeType;
            drawSvgShapeLayer(canvas, layer, shapeTemplateId);
            break;
    }
}

function drawCircleLayer(canvas, layer, isSelected) {
    var xCenter = 360;
    var yCenter = 360;
    for (var shapeIndex = 0; shapeIndex < layer.shapeCount; shapeIndex++) {
        var angle = 360 / layer.shapeCount * shapeIndex + layer.angleOffset;
        canvas.circle(xCenter, yCenter, layer.shapeSize)
            .transform("t" + layer.displacement + ",0" + "R" + angle + "," + xCenter + "," + yCenter)
            .attr(getPathAttributes(isSelected));
    }
}

function drawPathLayer(canvas, layer, shapePath, isSelected) {
    // compute normalize shape path
    var xCenter = 360;
    var yCenter = 360;
    var tmpShape = canvas.path(shapePath);
    var rect = tmpShape.getBBox();
    var shapeScaleFactor = 2 * (1 / Math.max(rect.width, rect.height));
    var scale = shapeScaleFactor * layer.shapeSize;
    var xOffset = rect.width / 2 + rect.x;
    var yOffset = rect.height / 2 + rect.y;
    var rotation = layer.rotation + 90;
    tmpShape.remove();

    shapePath = Raphael.transformPath(shapePath,
        "M0,0"
        + "T-" + xOffset + ",-" + yOffset                       // center shape around 0,0
        + "S" + scale + "," + scale + ",0,0"                    // normalize to default scale
        + "R" + rotation + ",0,0"                               // apply rotation around 0,0
        + "T" + (xCenter + layer.displacement) + "," + yCenter  // position to the right of center
    );

    for (var shapeIndex = 0; shapeIndex < layer.shapeCount; shapeIndex++) {
        var shape = canvas.path(shapePath);
        var angle = 360 / layer.shapeCount * shapeIndex + layer.angleOffset;
        shape.transform(
            "r" + angle + "," + xCenter + "," + yCenter   // rotate about center
            ).attr(getPathAttributes(isSelected));
    }
}

function getPathAttributes(isSelected) {
    return {
        "stroke-width": "1.1",
        "stroke": isSelected ? "#00e" : "#333",
        "fill": "none"
    };
}

/**
 * Legacy drawDesign function to be used on designs created before 2014-05-02
 */
function drawLayersLegacy(canvas, layers, currentLayerIndex) {
    currentLayerIndex = (typeof currentLayerIndex !== 'undefined' ? currentLayerIndex : null);

    canvas.clear();

    var xCenter = 360;
    var yCenter = 360;
    var layerCount = layers.length;
    for (var layerIndex = 0; layerIndex < layerCount; layerIndex++) {
        var color = "#333";
        var width = "1.5px";
        if (currentLayerIndex != null && layerIndex == currentLayerIndex) {
            color = "#00e";
        }

        var shapeType = layers[layerIndex].shapeType;
        var shapeCount = layers[layerIndex].shapeCount;
        var shapeSize = layers[layerIndex].shapeSize;
        var displacement = layers[layerIndex].displacement;

        for (var shapeIndex = 0; shapeIndex < shapeCount; shapeIndex++) {
            var angle = 360 / shapeCount * shapeIndex + layers[layerIndex].angleOffset;
            switch (shapeType) {
                case 'triangle':
                    var s = shapeSize / 2;
                    canvas
                        .path(
                            "M " + (-s) + " " + 0
                                + " L " + (s) + " " + 0
                                + " L " + 0 + " " + (s * Math.sqrt(3))
                                + " Z"
                        )
                        .transform(
                            "T" + xCenter + "," + yCenter
                                + "R270," + xCenter + "," + yCenter
                                + "T" + displacement + ",0"
                                + "R" + angle + "," + xCenter + "," + yCenter
                        )
                        .attr({"stroke-width": width, "stroke": color});
                    break;

                default:
                    canvas.circle(xCenter, yCenter, shapeSize)
                        .transform("t" + displacement + ",0" + "R" + angle + "," + xCenter + "," + yCenter)
                        .attr({"stroke-width": width, "stroke": color});
                    break;
            }
        }
    }
}