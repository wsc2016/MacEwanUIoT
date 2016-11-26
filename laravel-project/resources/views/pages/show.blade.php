<!DOCTYPE html>
<html>
<head>

    <title>Current Capacity</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.2/Chart.bundle.js'></script>
    <style>
        canvas{
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
    </style>

</head>
<body>


<img src="MacEwanLogo.gif" width="300" height="120">

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">Waste Management</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="/">Home</a></li>
            <li><a href="trend">Trend</a></li>
            <li><a href="about">About</a></li>
        </ul>
    </div>
</nav>


<div class="container">
    <div class="mx-auto" style="width:100%;">
        <!-- <canvas id="LineWithLine" width="400" height="300"></canvas> -->
        <canvas id="myChart"></canvas>
    </div>
</div>

<script>
    var sensorName = '{{ $sensor->sensor_name }}';
    var sensorDetails = ['{{ $sensor->sensor_name }}','{{ $sensor->sensor_brand }}','{{ $sensor->sensor_type }}','{{ $sensor->sensor_model }}'];
    var sensorReadings = [];
    var timeCreated = [];
    var locationInfo =[];
    var fillLine = [];
    fillLine.push(100);



    @foreach ($locations as $location)
    @if ($location->sensor_location_id == $sensor->sensor_location_id)
    locationInfo.push('{{ $location->garbage_bin_location_name }}');
    locationInfo.push('{{ $location->building_number }}');
    locationInfo.push('{{ $location->hallway_description }}');
    locationInfo.push('{{ $location->room_number }}');
    @endif
    @endforeach

    @foreach ($readings as $reading)
    @if ($reading->sensor_details_id == $sensor->sensor_details_id)
        sensorReadings.push(100 - parseInt(parseInt('{{ $reading->sensor_reading }}')/145 * 100));
        timeCreated.push('{{ $reading->time_created }}');
        fillLine.push(100);
    @endif
            @endforeach


            window.chartColors = {
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        blue: 'rgb(54, 162, 235)',
        purple: 'rgb(153, 102, 255)',
        grey: 'rgb(231,233,237)',
        brown: 'rgb(140, 114, 114)'
    };

    var i = parseInt('{{ $sensor->sensor_details_id }}') - 1;
    var colorChoice = ['rgba(255, 99, 132, 1)', 'rgba(255, 159, 64, 1)', 'rgba(255, 205, 86, 1)', 'rgba(75, 192, 192, 1)',
        'rgba(54, 162, 235, 1)', 'rgba(153, 102, 255, 1)', 'rgba(231,233,237, 1)', 'rgba(140, 114, 114, 1)', 'rgba(114, 140, 0, 1)',
        'rgba(14, 11, 114, 1)','rgba(255, 99, 132, 1)', 'rgba(255, 159, 64, 1)', 'rgba(255, 205, 86, 1)', 'rgba(75, 192, 192, 1)',
        'rgba(54, 162, 235, 1)', 'rgba(153, 102, 255, 1)', 'rgba(231,233,237, 1)', 'rgba(140, 114, 114, 1)', 'rgba(114, 140, 0, 1)',
        'rgba(14, 11, 114, 1)','rgba(255, 99, 132, 1)', 'rgba(255, 159, 64, 1)', 'rgba(255, 205, 86, 1)', 'rgba(75, 192, 192, 1)',
        'rgba(54, 162, 235, 1)', 'rgba(153, 102, 255, 1)', 'rgba(231,233,237, 1)', 'rgba(140, 114, 114, 1)', 'rgba(114, 140, 0, 1)',
        'rgba(14, 11, 114, 1)','rgba(255, 99, 132, 1)', 'rgba(255, 159, 64, 1)', 'rgba(255, 205, 86, 1)', 'rgba(75, 192, 192, 1)',
        'rgba(54, 162, 235, 1)', 'rgba(153, 102, 255, 1)', 'rgba(231,233,237, 1)', 'rgba(140, 114, 114, 1)', 'rgba(114, 140, 0, 1)',
        'rgba(14, 11, 114, 1)'];
    var colorChoiceAlpha = ['rgba(255, 99, 132, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(255, 205, 86, 0.2)', 'rgba(75, 192, 192, 0.2)',
        'rgba(54, 162, 235, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(231,233,237, 0.2)', 'rgba(140, 114, 114, 0.2)', 'rgba(114, 140, 0, 0.2)',
        'rgba(14, 11, 114, 0.2)','rgba(255, 99, 132, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(255, 205, 86, 0.2)', 'rgba(75, 192, 192, 0.2)',
        'rgba(54, 162, 235, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(231,233,237, 0.2)', 'rgba(140, 114, 114, 0.2)', 'rgba(114, 140, 0, 0.2)',
        'rgba(14, 11, 114, 0.2)','rgba(255, 99, 132, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(255, 205, 86, 0.2)', 'rgba(75, 192, 192, 0.2)',
        'rgba(54, 162, 235, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(231,233,237, 0.2)', 'rgba(140, 114, 114, 0.2)', 'rgba(114, 140, 0, 0.2)',
        'rgba(14, 11, 114, 0.2)','rgba(255, 99, 132, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(255, 205, 86, 0.2)', 'rgba(75, 192, 192, 0.2)',
        'rgba(54, 162, 235, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(231,233,237, 0.2)', 'rgba(140, 114, 114, 0.2)', 'rgba(114, 140, 0, 0.2)',
        'rgba(14, 11, 114, 0.2)'];

    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
                options: {
                    responsive: true,
                    title:{
                        display:true,
                        text: 'Name: ' + sensorDetails[0] + '  /  Brand: ' + sensorDetails[1] + '  /  Type: ' + sensorDetails[2] + '  /  Model: ' + sensorDetails[3] + ' / Location: ' + locationInfo[0] + ' / Building: ' + locationInfo[1] + ' / Hallway: ' + locationInfo[2] + ' / Room: ' + locationInfo[3]
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'DateTime'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Fill Capacity (%)'
                            }
                        }]
                    }
                },
                type: 'line',
                data: {
                    labels: timeCreated.sort(),
                    datasets: [{
                        label: sensorName,
                        data: sensorReadings,
                        backgroundColor: colorChoiceAlpha[i],
                        borderColor: colorChoice[i],
                        fill: true,
                        fillColor: colorChoiceAlpha[i],
                        strokeColor: colorChoice[i],
                        pointColor: colorChoice[i],
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: colorChoice[i]

                    },{
                                label: 'Maximum Capacity',
                                data: fillLine,
                                fill: false,
                                backgroundColor: 'rgb(255,0,0)',
                                borderColor: 'rgb(255,0,0)',




                    }]
                },


            }
    );


    ////////
/*
    var data = {
        labels: timeCreated.sort(),
        datasets: [{
            label: sensorName,
            data: sensorReadings,
            fillColor: colorChoiceAlpha[i],
            strokeColor: colorChoice[i],
            pointColor: colorChoice[i],
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: colorChoice[i]
        }]
    };

    var ctx = document.getElementById("LineWithLine").getContext("2d");

    Chart.types.Line.extend({
        name: "LineWithLine",
        initialize: function () {
            Chart.types.Line.prototype.initialize.apply(this, arguments);
        },
        draw: function () {
            Chart.types.Line.prototype.draw.apply(this, arguments);

            //var point = this.datasets[0].points[this.options.lineAtIndex];
            //var scale = this.scale;
            var firstPoint = this.datasets[0].points[0];
            //var scale = this.scale
            //console.log(this);

            // draw line
            this.chart.ctx.save();
            this.chart.ctx.beginPath();
            //this.chart.ctx.moveTo(scale.startPoint+12, point.y);
            this.chart.ctx.strokeStyle = '#ff0000';
            this.chart.ctx.strokeColor = '#ff0000';
            //this.chart.ctx.moveTo(firstPoint.startPoint, this.scale.calculateY(this.options.maxLevel));
            //this.chart.ctx.lineTo(this.chart.width, scale.Endpoint);
            this.chart.ctx.moveTo(firstPoint.x, this.scale.calculateY(this.options.maxLevel));
            this.chart.ctx.lineTo(this.chart.width, this.scale.calculateY(this.options.maxLevel));

            //this.chart.ctx.moveTo(scale.startPoint, scale.calculateY(this.options.maxLevel) );
            //this.chart.ctx.lineTo(scale.endPoint, scale.calculateY(this.options.maxLevel));
            //this.chart.ctx.lineTo(this.chart.width, point.y);
            this.chart.ctx.stroke();
            this.chart.ctx.closePath();

            // write TODAY
            this.chart.ctx.textAlign = 'center';
            this.chart.ctx.fillText("Maximum Capacity", this.chart.width-65, this.scale.calculateY(this.options.maxLevel)+10);

            this.chart.ctx.restore();
        }
    });

    new Chart(ctx).LineWithLine(data, {
        //datasetFill : false,
        maxLevel: 90,
        //lineAtIndex: 2
    });
*/




</script>


<footer class="footer">
    <div class="container">
        <p class="text-muted">MacEwan University 2016</p>
    </div>
</footer>


</body>
</html>