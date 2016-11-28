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
            <li><a href="/">Home</a></li>
            <li class="active"><a href="/trend">Trend</a></li>
            <li><a href="/about">About</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="mx-auto" style="width:95%;">
        <canvas id="canvas"></canvas>
    </div>
</div>

<script>
    var locationName = [];
    var nowDate = new Date();
    var compMonth = 0;
    var sum = 0;
    var avg = 0;
    var tempReadingArray1 = [];
    var tempReadingArray2 = [];
    var tempReadingArray3 = [];
    var tempReadingArray4 = [];
    var tempReadingArray5 = [];
    var tempReadingArray6 = [];
    var month1Array = [];
    var month2Array = [];
    var month3Array = [];
    var month4Array = [];
    var month5Array = [];
    var month6Array = [];
    var currReading = 0;
    var readCount = 0;

var today = new Date();
var currMonth = today.getMonth();
var months= [];
var newMonthList = [];
var monthList = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
for (var i = 0; i < 6; i++) {
    months.push(currMonth);
    newMonthList.push(monthList[currMonth]);
    currMonth--;
    if (currMonth < 0) {
    currMonth = 11;
    }
};

    <?php $locationCount = 0; ?>
        @foreach ($locations as $location)
            locationName.push('{{ $location->garbage_bin_location_name }}');
            <?php $locationCount++; ?>

                @foreach ($location->readings as $reading)
                    @if ($location->sensor_location_id == $reading->sensor_details_id)

                        nowDate = new Date('{{ $reading->time_created }}');
                        compMonth = nowDate.getMonth();
                        currReading = parseInt((parseInt('{{ $reading->sensor_reading }}')-5)/145 * 100);
                        readCount++;

                        if(months[0] == compMonth){
                            tempReadingArray1.push(currReading);

                        };
                        if(months[1] == compMonth){
                            tempReadingArray2.push(currReading);

                        };
                        if(months[2] == compMonth){
                            tempReadingArray3.push(currReading);

                        };
                        if(months[3] == compMonth){
                            tempReadingArray4.push(currReading);

                        };
                        if(months[4] == compMonth){
                            tempReadingArray5.push(currReading);

                        };
                        if(months[5] == compMonth){
                            tempReadingArray6.push(currReading);

                        };

                    @endif
                @endforeach

        <?php for( $j = 1; $j<7; $j++ ) { ?>
        <?php echo 'sum = 0;' ?>
        <?php echo 'avg = 0;' ?>
    <?php echo 'for( var i = 0; i < tempReadingArray'.$j.'.length; i++ ){
                        sum = sum + parseInt( tempReadingArray'.$j.'[i]);
                    }' ?>

    <?php echo 'avg = sum/tempReadingArray'.$j.'.length;' ?>

    <?php echo 'month'.$j.'Array.push(parseInt(avg));' ?>
        <?php } ?>

<?php echo 'tempReadingArray1 = [];' ?>
<?php echo 'tempReadingArray2 = [];' ?>
<?php echo 'tempReadingArray3 = [];' ?>
<?php echo 'tempReadingArray4 = [];' ?>
<?php echo 'tempReadingArray5 = [];' ?>
<?php echo 'tempReadingArray6 = [];' ?>

        @endforeach

var colorChoice = ['rgba(255, 99, 132, 1)', 'rgba(255, 159, 64, 1)', 'rgba(255, 205, 86, 1)', 'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)', 'rgba(153, 102, 255, 1)', 'rgba(231,233,237, 1)', 'rgba(140, 114, 114, 1)', 'rgba(114, 140, 0, 1)',
                        'rgba(14, 11, 114, 1)'];

var ctx = document.getElementById("canvas").getContext("2d");
var canvas = new Chart(ctx, {
    type: 'line',
    data: {
    labels: newMonthList.reverse(),
    datasets: [
            <?php $k = 0; ?>
            <?php for( $i = 0; $i<$locationCount; $i++ ) { ?>
                <?php if($k == 10) { ?>
                    <?php   $k = 0; ?>
                <?php } ?>

    <?php echo '{
        label: locationName['.$i.'],
        fill: false,
        backgroundColor: colorChoice['.$k.'],
        borderColor: colorChoice['.$k.'],
        data: [month1Array['.$i.'],month2Array['.$i.'],month3Array['.$i.'],month4Array['.$i.'],month5Array['.$i.'],month6Array['.$i.']]
    },' ?>
            <?php $k++; ?>
            <?php } ?>
    {
        label: "Maximum Capacity",
        fill: false,
        backgroundColor: 'rgb(255,0,0)',
        borderColor: 'rgb(255,0,0)',
                pointRadius: 0,
        data: [100,100,100,100,100,100]
    }]
    },
    options: {
        responsive: true,
        title:{
        display:true,
        text:'Six Month Average Capacity Trend By Location For 2016'
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
                    labelString: 'Average Capacity(%)'
                }
            }]
        }
    }
});
</script>

<footer class="panel-footer panel-custom">
    <div class="container">
        <p class="text-muted">MacEwan University 2016</p>
    </div>
</footer>

</body>
</html>