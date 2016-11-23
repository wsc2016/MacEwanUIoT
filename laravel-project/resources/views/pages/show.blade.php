<html>
<head>

    <title>Sensor Data</title>
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

<div class="container">
    <div style="width:75%;">
        <canvas id="myChart"></canvas>
    </div>
</div>

<script>
    var sensorName = '{{ $sensor->sensor_name }}';
    var sensorDetails = ['{{ $sensor->sensor_name }}','{{ $sensor->sensor_brand }}','{{ $sensor->sensor_type }}','{{ $sensor->sensor_model }}'];
    var sensorReadings = [];
    var timeCreated = [];
    var locationInfo =[];



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
        sensorReadings.push('{{ $reading->sensor_reading }}');
        timeCreated.push('{{ $reading->time_created }}');
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
    var colorChoice = ['rgb(255, 99, 132)', 'rgb(255, 159, 64)', 'rgb(255, 205, 86)', 'rgb(75, 192, 192)',
        'rgb(54, 162, 235)', 'rgb(153, 102, 255)', 'rgb(231,233,237)', 'rgb(140, 114, 114)'];
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
                options: {
                    responsive: true,
                    title:{
                        display:true,
                        text: 'Name: ' + sensorDetails[0] + '  /  Brand: ' + sensorDetails[1] + '  /  Type: ' + sensorDetails[2] + '  /  Model: ' + sensorDetails[3]
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
                                labelString: 'Distance (cm)'
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
                        fill: false,
                        backgroundColor: colorChoice[i],
                        borderColor: colorChoice[i]

                    }]
                },

            }
    );
</script>


</body>
</html>