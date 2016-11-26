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
            <li><a href="/trend">Trend</a></li>
            <li><a href="/about">About</a></li>
        </ul>
    </div>
</nav>


<div class="container">
    <div class="mx-auto" style="width:100%;">
        <canvas id="canvasx"></canvas>
    </div>
</div>

<script>
var today = new Date();
var aMonth = today.getMonth();
var months= [];
var month = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
for (i=0; i<6; i++) {
    months.push(month[aMonth]);
    aMonth--;
    if (aMonth < 0) {
    aMonth = 11;
    }
}


var locationName = [];

        @foreach ($locations as $location)
        locationName.push('{{ $location->garbage_bin_location_name }}');
        @endforeach

var config = {
    type: 'line',
    data: {
    labels: months.reverse(),
    datasets: [{
        label: locationName[0],
        fill: false,
        backgroundColor: window.chartColors.blue,
        borderColor: window.chartColors.blue,
        data: [98,56,45,65,34,54]
    },
    {
        label: locationName[1],
        fill: false,
        backgroundColor: window.chartColors.green,
        borderColor: window.chartColors.green,
        data: [56,67,43,67,43,87]
    },
    {
        label: "Maximum Capacity",
        fill: false,
        backgroundColor: window.chartColors.red,
        borderColor: window.chartColors.red,
        data: [100,100,100,100,100,100]
    }]
    },
    options: {
        responsive: true,
        title:{
        display:true,
        text:'Six Month Trend'
    },
        tooltips: {
            mode: 'index',
            intersect: false
            },
        hover: {
            mode: 'nearest',
            intersect: true
        },
        scales: {
            xAxes: [{
            display: true,
            scaleLabel: {
            display: true,
            labelString: 'Month'
        }
        }],
            yAxes: [{
                display: true,
                scaleLabel: {
                    display: true,
                    labelString: 'Value'
                }
            }]
        }
    }
};

var ctxB = document.getElementById("canvas").getContext("2d");
var canvasx = new Chart(ctxB, config);
</script>

<footer class="footer">
    <div class="container">
        <p class="text-muted">MacEwan University 2016</p>
    </div>
</footer>
</body>
</html>