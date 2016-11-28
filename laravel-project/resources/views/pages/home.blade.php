<!DOCTYPE html>
<html>
<head>

    <title>MacEwan University Waste Management</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">

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

<h2>Current Capacity</h2>

<div class="container-fluid">


    <table class="table table-hover">
    <tbody>
        <tr class="success">
                    <?php $count = 0 ?>
                    <?php $rowCount = 0?>
                    @foreach ($sensors as $sensor)
                        <?php $count++ ?>
                        <?php $rowCount++ ?>
                        @foreach ($locations as $location)
                            @if ($location->sensor_location_id == $sensor->sensor_location_id)
                                    <td class="align-text-bottom">
                                    <?php $s = $sensor->sensor_location_id ?>
                                    <div><canvas id="<?php echo 'myDoughnutChart'.$s ?>"></canvas></div>
                    <h6>Location: <small>{{ $location->garbage_bin_location_name }}</small></h6>
                    <h6>Name: <small><a href="{{ $sensor->sensor_location_id }}">{{ $sensor->sensor_name }}</a></small></h6>
                    <h6>Building: <small>{{ $location->building_number }}</small></h6>
                    <h6>Hallway: <small>{{ $location->hallway_description }}</small></h6>
                    <h6>Room: <small>{{ $location->room_number }}</small></h6>
                                </td>
                                <?php if ($rowCount == 8) { ?>
                                </tr><tr class="danger">
                                <?php } elseif ($rowCount == 16) { ?>
                                </tr><tr class="info">
                                <?php } elseif ($rowCount == 24) { ?>
                                </tr><tr class="warning">
                                <?php } elseif ($rowCount == 32) { ?>
                                </tr><tr class="active">
                                <?php } ?>
                        @endif
                        @endforeach
                        @endforeach

        </tr>
    </tbody>
    </table>
    </div>
<!--
<div class="container-fluid">
    <div class="mx-auto" style="width:100%;">
        <canvas id="canvas"></canvas>
    </div>
</div>
-->


<script>
    var sensorName = [];
    var readingOne;
    var sensorReadings = [];
    var timeCreated = [];
    var locationInfo =[];
    var locationInfo1 =[];
    var dataSet = [];
    var ctxSet = [];
    var myDoughnutChartSet = [];
    var locationArray = [];

    @foreach ($sensors as $sensor)
        sensorName.push('{{ $sensor->sensor_name }}');
        @foreach ($sensor->readings as $reading)
            @if ($reading->sensor_details_id == $sensor->sensor_details_id)
            readingOne = '{{ $reading->sensor_reading }}';
            @endif
        @endforeach
        sensorReadings.push(readingOne);
        @foreach ($locations as $location)
        @if ($location->sensor_location_id == $sensor->sensor_location_id)
            locationArray.push('{{ $location->garbage_bin_location_name }}');
        @endif
        @endforeach
@endforeach

    @foreach ($locations as $location)
    locationInfo.push('{{ $location->sensor_location_id }}')
        locationInfo1.push('{{ $location->garbage_bin_location_name }}');
        locationInfo.push('{{ $location->building_number }}');
        locationInfo.push('{{ $location->hallway_description }}');
        locationInfo.push('{{ $location->room_number }}');

        @endforeach




            window.chartColors = {
                red: 'rgb(255, 99, 132)',
                orange: 'rgb(255, 159, 64)',
                yellow: 'rgb(255, 205, 86)',
                green: 'rgb(75, 192, 192)',
                blue: 'rgb(54, 162, 235)',
                purple: 'rgb(153, 102, 255)',
                grey: 'rgb(231,233,237)'
            };
/*
    dataSet.push({
        labels: [
            "Filled",
            "Remaining"
        ],
        datasets: [
            {
                data: [100 - parseInt((sensorReadings[0]/145) * 100), parseInt((sensorReadings[0]/145) * 100)],
                responsive: true, maintainAspectRatio: true,

                label:locationInfo1[0],
                backgroundColor: [
                    "#DC143C",
                    "#e5ffe8"
                ],
                hoverBackgroundColor: [
                    "#DC143C",
                    "#e5ffe8"
                ]
            }]
    });
    dataSet.push({
        labels: [
            "Filled",
            "Remaining"
        ],
        datasets: [
            {
                data: [100 - parseInt((sensorReadings[1]/145) * 100), parseInt((sensorReadings[1]/145) * 100)],
                label:locationInfo1[1],
                backgroundColor: [
                    "#DC143C",
                    "#e5ffe8"
                ],
                hoverBackgroundColor: [
                    "#DC143C",
                    "#e5ffe8"
                ]
            }]
    });
    dataSet.push({
        labels: [
            "Filled",
            "Remaining"
        ],
        datasets: [
            {
                data: [100 - parseInt((sensorReadings[2]/145) * 100), parseInt((sensorReadings[2]/145) * 100)],
                label:locationInfo1[2],
                backgroundColor: [
                    "#DC143C",
                    "#e5ffe8"
                ],
                hoverBackgroundColor: [
                    "#DC143C",
                    "#e5ffe8"
                ]
            }]
    });
    dataSet.push({
        labels: [
            "Filled",
            "Remaining"
        ],
        datasets: [
            {
                data: [100 - parseInt((sensorReadings[3]/145) * 100), parseInt((sensorReadings[3]/145) * 100)],
                label:locationInfo1[3],
                backgroundColor: [
                    "#DC143C",
                    "#e5ffe8"
                ],
                hoverBackgroundColor: [
                    "#DC143C",
                    "#e5ffe8"
                ]
            }]
    });
*/
            <?php for( $i = 0; $i<$count; $i++ ) { ?>
            <?php echo 'dataSet.push({ labels: ["Filled","Remaining"],datasets: [{ data: [parseInt(((sensorReadings['.$i.']-5)/145) * 100), 100 - parseInt(((sensorReadings['.$i.']-5)/145) * 100)],label:locationInfo1['.$i.'],backgroundColor: ["#DC143C","#e5ffe8"], hoverBackgroundColor: ["#DC143C","#e5ffe8"]}] });' ?>
            <?php } ?>

            <?php for( $i = 0; $i<$count; $i++ ) { ?>
            <?php $j = $i + 1 ?>
            <?php echo "var ctx".$j." = document.getElementById('myDoughnutChart".$j."').getContext('2d'); " ?>
            <?php echo 'ctx'.$j.'.canvas.width = 150;' ?>
            <?php echo 'ctx'.$j.'.canvas.height = 100;' ?>
            <?php } ?>


    /*
    var ctx1 = document.getElementById('myDoughnutChart1').getContext('2d');
    var ctx2 = document.getElementById('myDoughnutChart2').getContext('2d');
    var ctx3 = document.getElementById('myDoughnutChart3').getContext('2d');
    var ctx4 = document.getElementById('myDoughnutChart4').getContext('2d');
*/

    <?php for( $i = 0; $i<$count; $i++ ) { ?>
    <?php $j = $i + 1 ?>
    <?php echo 'myDoughnutChartSet.push(new Chart(ctx'.$j.', {responsive: true,maintainAspectRatio: false,type: "doughnut",data: dataSet['.$i.']})); ' ?>
    <?php } ?>


    <?php for( $i = 0; $i<$count; $i++ ) { ?>
    <?php $j = $i + 1 ?>
    <?php echo 'var myDoughnutChart'.$j.' = myDoughnutChartSet['.$i.']; ' ?>
    <?php } ?>
/*
    var myDoughnutChart1 = new Chart(ctx1, {
        responsive: true,
        type: 'doughnut',
        data: dataSet[0]
    });
    var myDoughnutChart2 = new Chart(ctx2, {
        type: 'doughnut',
        data: dataSet[1]
    });
    var myDoughnutChart3 = new Chart(ctx3, {
        type: 'doughnut',
        data: dataSet[2]
    });
    var myDoughnutChart4 = new Chart(ctx4, {
        type: 'doughnut',
        data: dataSet[3]
    });
*/
</script>

<footer class="panel-footer panel-custom">
    <div class="container">
        <p class="text-muted">MacEwan University 2016</p>
    </div>
</footer>

</body>
</html>