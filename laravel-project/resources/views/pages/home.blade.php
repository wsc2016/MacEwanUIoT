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

<div class="container">

    <table class="table table-hover">
    <tbody>
        <tr class="active">
            <?php $count = 0 ?>
            <?php $colCount = 0 ?>

            {{-- load location data into arrays by sensor and generate chart--}}
            @foreach ($locations as $location)
                    <?php $count++ ?>
                    @foreach ($sensors as $sensor)
                            @if ($location->sensor_location_id == $sensor->sensor_location_id)
                            <?php $colCount++ ?>
                                <td class="align-text-bottom">
                                    <h5><b>Location:</b><br> {{ $location->garbage_bin_location_name }}</h5>
                                    <?php $s = $sensor->sensor_details_id ?>
                                    <div><canvas id="<?php echo 'myDoughnutChart'.$s ?>"></canvas></div>

                    <h5><b>Name:</b><br> <a href="{{ $sensor->sensor_location_id }}">{{ $sensor->sensor_name }}</a></h5>
                    <h5><b>Building:</b> {{ $location->building_number }}</h5>
                    <h5><b>Hallway:</b><br> {{ $location->hallway_description }}</h5>
                    <h5><b>Room:</b> {{ $location->room_number }}</h5>

                                </td>
                                {{-- insert new row after 8 charts --}}
                                <?php if ($colCount == 8) { ?>
                                    </tr><tr class="active">
                                    <?php $colCount = 0; ?>
                                <?php } ?>

                        @endif
                        @endforeach
                        @endforeach

        </tr>
    </tbody>
    </table>
    </div>

<script>
    var sensorName = [];
    var readingOne;
    var sensorReadings = [];
    var timeCreated = [];
    var locationInfo1 =[];
    var dataSet = [];
    var ctxSet = [];
    var myDoughnutChartSet = [];
    var locationArray = [];

    Chart.defaults.global.legend.display = false;

    //load sensor data into arrays
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

    //load location names into array
    @foreach ($locations as $location)
        locationInfo1.push('{{ $location->garbage_bin_location_name }}');
    @endforeach

    //assign colors
    window.chartColors = {
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        blue: 'rgb(54, 162, 235)',
        purple: 'rgb(153, 102, 255)',
        grey: 'rgb(231,233,237)'
    };

    //generate chart data
    <?php for( $i = 0; $i<$count; $i++ ) { ?>
        <?php echo 'dataSet.push({ labels: ["Filled","Remaining"],datasets: [{ data: [parseInt(((sensorReadings['.$i.']-5)/145) * 100), 100 - parseInt(((sensorReadings['.$i.']-5)/145) * 100)],label:locationInfo1['.$i.'],backgroundColor: ["#DC143C","#e5ffe8"], hoverBackgroundColor: ["#DC143C","#e5ffe8"]}] });' ?>
    <?php } ?>

    <?php for( $i = 0; $i<$count; $i++ ) { ?>
        <?php $j = $i + 1 ?>
        <?php echo "var ctx".$j." = document.getElementById('myDoughnutChart".$j."').getContext('2d'); " ?>
        <?php echo 'ctx'.$j.'.canvas.width = 125;' ?>
        <?php echo 'ctx'.$j.'.canvas.height = 60;' ?>
    <?php } ?>

    <?php for( $i = 0; $i<$count; $i++ ) { ?>
        <?php $j = $i + 1 ?>
        <?php echo 'myDoughnutChartSet.push(new Chart(ctx'.$j.', {responsive: true,maintainAspectRatio: false,type: "doughnut",data: dataSet['.$i.']})); ' ?>
    <?php } ?>


    <?php for( $i = 0; $i<$count; $i++ ) { ?>
        <?php $j = $i + 1 ?>
        <?php echo 'var myDoughnutChart'.$j.' = myDoughnutChartSet['.$i.']; ' ?>
    <?php } ?>

</script>

<footer class="panel-footer panel-custom">
    <div class="container">
        <p class="text-muted">MacEwan University 2016</p>
    </div>
</footer>

</body>
</html>