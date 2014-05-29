
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

        case 'teardrop':
            shapePath = 'M12.042,0c-16.171,21.888-15.939,42.001,0,42.001C27.982,42.001,28.212,21.888,12.042,0z';
            drawPathLayer(canvas, layer, shapePath, isSelected);
            break;

        case 'leaf':
            shapePath = "M137.03,227.89c-4.39,0.41-8.62,1.88-13,2.38-10.27,1.2-29.194,2.09-30.99,1.57l-8.08-3.29-15.96-3.55c-2.452-0.81-15.64-7.64-17-10l23-0.53c3.476-0.8,15.552-6.41,20-6.47,1.744-0.02,19.86-3.94,19.12-7-0.38-1.57-9.74-9.04-11.12-9.72-2.16-1.08-12.768-3.86-15-4.66l-25-10.62c1.32-3.98,17.394-6.6,18.19-10.06,0.368-1.59-5.07-8.48-6.21-9.24-2.568-1.72-17.422-12.29-17.99-15.7,4.66-0.47,0.732-12.08,0-13.17-1.128-1.68-5.608-13.79-6.96-15.79-1.732-2.57-10.662-10.58-12.03-14.04,4.5-1.068,29.068,10.99,33,12.64,1.796,0.75,10.33,2.55,11.99,3.79,2.728,2.04,13.584,15.02,15.88,14.17,1.61-0.59,5.68-11.14,6.13-12.6,2.44,1.53,11.51,16.45,12.73,19,1.56,3.25,12.06,20.16,14.28,23,1.14,1.45,13.53,8.88,13.97,4v-4-16c-0.07-6.33-13.25-45.66-14.74-52l-3.4-17c-0.25-0.88-0.49-6.856,0-7.64,0.98-1.276,6.71,0.998,7.43,1.75,1.12,1.172,11.92,13.766,13.54,13.29,1.34-0.396,3.13-8.304,3.01-9.4-0.42-3.66-4.86-22.672-3.82-27,0.88,0.364,7.83,3.126,8.69,2.75,1.2-0.516,2.7-8.906,3.3-10.75l5.38-11c1.52-4.292,2.83-14.688,4.21-18.095,1.39,3.2176,2.7,13.614,4.22,17.906l5.38,11c0.6,1.844,2.1,10.234,3.3,10.75,0.86,0.376,7.81-2.386,8.69-2.75,1.04,4.328-3.4,23.34-3.82,27-0.12,1.096,1.67,9.004,3.01,9.4,1.62,0.476,12.42-12.118,13.54-13.29,0.72-0.752,6.45-3.026,7.43-1.75,0.49,0.784,0.25,6.76,0,7.64l-3.4,17c-1.49,6.34-14.67,45.669-14.74,51.999v16,4c0.44,4.88,12.83-2.55,13.97-4,2.22-2.84,12.72-19.75,14.28-23,1.22-2.55,10.29-17.47,12.73-19,0.45,1.46,4.52,12.01,6.13,12.6,2.3,0.85,13.15-12.13,15.88-14.17,1.66-1.24,10.19-3.04,11.99-3.79,3.93-1.65,28.5-13.707,33-12.639-1.37,3.459-10.3,11.469-12.03,14.039-1.35,2-5.83,14.11-6.96,15.79-0.73,1.09-4.66,12.7,0,13.17-0.57,3.41-15.42,13.98-17.99,15.7-1.14,0.76-6.58,7.65-6.21,9.24,0.8,3.46,16.87,6.08,18.19,10.06l-25,10.62c-2.23,0.8-12.84,3.58-15,4.66-1.38,0.68-10.74,8.15-11.12,9.72-0.74,3.06,17.38,6.98,19.12,7,4.45,0.06,16.52,5.67,20,6.47l23,0.53c-1.36,2.36-14.55,9.19-17,10l-15.96,3.55-8.08,3.29c-1.8,0.52-20.72-0.37-30.99-1.56-4.38-0.51-8.61-1.99-13-2.38-21.62-1.94-43.5-1.81-65.11,0.18z";
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