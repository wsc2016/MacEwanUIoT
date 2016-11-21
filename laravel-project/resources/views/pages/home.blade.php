<!DOCTYPE html>
<html>
<head>
    <title>IoT</title>

    <script src="http://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script type="text/javascript">

        window.onload = function () {
            var chart = new CanvasJS.Chart("chartContainer", {
                theme: "theme2",//theme1
                title:{
                    text: "Basic Column Chart - CanvasJS"
                },
                animationEnabled: false,   // change to true
                data: [
                    {
                        // Change type to "bar", "area", "spline", "pie",etc.
                        type: "column",
                        dataPoints: [
                            { label: "apple",  y: 10  },
                            { label: "orange", y: 15  },
                            { label: "banana", y: 25  },
                            { label: "mango",  y: 30  },
                            { label: "grape",  y: 28  }
                        ]
                    }
                ]
            });
            chart.render();
        }
    </script>




</head>
<body>
<div id="chartContainer" style="height: 500px; width: 75%;"></div>
</body>
</html>


//
</body>
</html>