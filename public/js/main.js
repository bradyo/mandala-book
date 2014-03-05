function drawDesign(canvas, layers, currentLayerIndex) {
    currentLayerIndex = (typeof currentLayerIndex !== 'undefined' ? currentLayerIndex : null);

    canvas.clear();

    var xCenter = canvas.width / 2;
    var yCenter = canvas.height / 2;
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
        var rotation = layers[layerIndex].rotation;

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

                case 'star':
                    var path = Raphael.pathToRelative("m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z");
                    canvas
                        .path(path)
                        .transform(
                            + "T" + xCenter + "," + yCenter
                                + "R270," + xCenter + "," + yCenter
                                + "T" + displacement + ",0"
                                + "R" + angle + "," + xCenter + "," + yCenter
                        )
                        .attr({"stroke-width": width, "stroke": color});
                    break;

                case 'heart':
                    canvas
                        .path("M140,20C 73,20 20,74 20,140 20,275 156,310 248,443 336,311 477,270 477,140"
                            + "477,74 423,20 357,20 309,20 267,48 248,89 229,48 188,20 140,20Z")
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