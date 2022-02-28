	$(function () {
    var sin = [], cos = [];
    //for (var i = 0; i < 16; i += 0.5) {
        sin.push([1, 3.2]);
        sin.push([2, 3.9]);
        sin.push([3, 4.5]);
        sin.push([4, 5.0]);
        sin.push([5, 5.4]);
        sin.push([6, 5.7]);
        sin.push([7, 6.0]);
        sin.push([8, 6.3]);
        sin.push([9, 6.5]);
        sin.push([10, 6.7]);
        sin.push([11, 6.9]);
        sin.push([12, 7.0]);
        sin.push([13, 7.2]);
        sin.push([14, 7.4]);
        sin.push([15, 7.6]);
        sin.push([16, 7.7]);
        sin.push([17, 7.9]);
        sin.push([18, 8.1]);        
        sin.push([19, 8.2]);
        sin.push([20, 8.4]);
        sin.push([21, 8.6]);
        sin.push([22, 8.7]);
        sin.push([23, 8.9]);
        sin.push([24, 9.0]);
        sin.push([25, 9.2]);
        sin.push([26, 9.4]);
        sin.push([27, 9.5]);
        sin.push([28, 9.7]);
        sin.push([29, 9.8]);
        sin.push([30, 10.0]);
        
        cos.push([1, 5.5]);
        cos.push([2, 6.6]);
        cos.push([3, 7.5]);
        cos.push([4, 8.2]);
        cos.push([5, 8.8]);
        cos.push([6, 9.3]);
        cos.push([7, 9.8]);
        cos.push([8, 10.2]);
        cos.push([9, 10.5]);
        cos.push([10, 10.9]);
        cos.push([11, 11.2]);
        cos.push([12, 11.5]);
        cos.push([13, 11.8]);
        cos.push([14, 12.1]);
        cos.push([15, 12.4]);
        cos.push([16, 12.6]);
        cos.push([17, 12.9]);
        cos.push([18, 13.2]);        
        cos.push([19, 13.5]);
        cos.push([20, 13.7]);
        cos.push([21, 14.0]);
        cos.push([22, 14.3]);
        cos.push([23, 14.6]);
        cos.push([24, 14.8]);
        cos.push([25, 15.1]);
        cos.push([26, 15.4]);
        cos.push([27, 15.7]);
        cos.push([28, 16.0]);
        cos.push([29, 16.2]);
        cos.push([30, 16.5]);
    //}

    var plot = $.plot($("#graph"),
           [ { data: sin, label: "sin(x)"}, { data: cos, label: "cos(x)" } ], {
               series: {
                   lines: { show: true },
                   //points: { show: true }
               },
               grid: { hoverable: true, clickable: true },
               yaxis: { min: 0, max: 26, tickSize: 1 },
			   xaxis: { min: 0, max: 60, tickSize: 1 }
             });

    //function showTooltip(x, y, contents) {
//        $('<div id="tooltip" class="chart-tooltip">' + contents + '</div>').css( {
//            position: 'absolute',
//            display: 'none',
//            top: y - 45,
//            left: x - 9,
//			'z-index': '9999',
//			'color': '#fff',
//			'font-size': '11px',
//            opacity: 0.9
//        }).appendTo("body").fadeIn(200);
//    }
//
//    var previousPoint = null;
//    $("#graph").bind("plothover", function (event, pos, item) {
//        $("#x").text(pos.x.toFixed(2));
//        $("#y").text(pos.y.toFixed(2));
//
//        if ($("#graph").length > 0) {
//            if (item) {
//                if (previousPoint != item.dataIndex) {
//                    previousPoint = item.dataIndex;
//                    
//                    $("#tooltip").remove();
//                    var x = item.datapoint[0].toFixed(2),
//                        y = item.datapoint[1].toFixed(2);
//                    
//                    showTooltip(item.pageX, item.pageY,
//                                item.series.label + " of " + x + " = " + y);
//                }
//            }
//            else {
//                $("#tooltip").remove();
//                previousPoint = null;            
//            }
//        }
//    });

    //$("#graph").bind("plotclick", function (event, pos, item) {
//        if (item) {
//            $("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
//            plot.highlight(item.series, item.datapoint);
//        }
//    });
});
