<html>
<head>

    <title>Current Capacity</title>
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

    <h2>Current Capacity</h2>

    <div class="container">
        <table>
                <tr>
                    @foreach ($sensors as $sensor)
                        @foreach ($locations as $location)
                            @if ($location->sensor_location_id == $sensor->sensor_location_id)
                                <td align="center">
                                <div width="25%" align="center">
                                    <canvas id="<?php echo 'myDoughnutChart' . $sensor->sensor_location_id ?>"></canvas>
                    Location: {{ $location->garbage_bin_location_name }} <br>
                    Name: <a href="{{ $sensor->sensor_location_id }}">{{ $sensor->sensor_name }}</a> <br>
                    Building: {{ $location->building_number }} <br>
                    {{ $location->hallway_description }} <br>
                    Room: {{ $location->room_number }}
                                </div>
                                </td>
                        @endif
                        @endforeach
                        @endforeach
                </tr>
        </table>
    </div>



<script>
    var sensorName = [];
    var readingOne;
    var sensorReadings = [];
    var timeCreated = [];
    var locationInfo =[];
    var locationInfo1 =[];

    @foreach ($sensors as $sensor)
        sensorName.push('{{ $sensor->sensor_name }}');
        @foreach ($readings as $reading)
            @if ($reading->sensor_details_id == $sensor->sensor_details_id)
            readingOne = '{{ $reading->sensor_reading }}';
            @endif
        @endforeach
        sensorReadings.push(readingOne);
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

    var data1 = {
        labels: [
            "Filled",
            "Empty"
        ],
        datasets: [
            {
                data: [150 - sensorReadings[0], sensorReadings[0]],
                label:locationInfo1[0],
                backgroundColor: [
                    "#DC143C",
                    "#32CD32"
                ],
                hoverBackgroundColor: [
                    "#DC143C",
                    "#32CD32"
                ]
            }]
    };
    var data2 = {
        labels: [
            "Filled",
            "Empty"
        ],
        datasets: [
            {
                data: [150 - sensorReadings[1], sensorReadings[1]],
                label:locationInfo1[1],
                backgroundColor: [
                    "#DC143C",
                    "#32CD32"
                ],
                hoverBackgroundColor: [
                    "#DC143C",
                    "#32CD32"
                ]
            }]
    };
    var data3 = {
        labels: [
            "Filled",
            "Empty"
        ],
        datasets: [
            {
                data: [150 - sensorReadings[2], sensorReadings[2]],
                label:locationInfo1[2],
                backgroundColor: [
                    "#DC143C",
                    "#32CD32"
                ],
                hoverBackgroundColor: [
                    "#DC143C",
                    "#32CD32"
                ]
            }]
    };
    var data4 = {
        labels: [
            "Filled",
            "Empty"
        ],
        datasets: [
            {
                data: [150 - sensorReadings[3], sensorReadings[3]],
                label:locationInfo1[3],
                backgroundColor: [
                    "#DC143C",
                    "#32CD32"
                ],
                hoverBackgroundColor: [
                    "#DC143C",
                    "#32CD32"
                ]
            }]
    };


    var ctx1 = document.getElementById('myDoughnutChart1').getContext('2d');
    var ctx2 = document.getElementById('myDoughnutChart2').getContext('2d');
    var ctx3 = document.getElementById('myDoughnutChart3').getContext('2d');
    var ctx4 = document.getElementById('myDoughnutChart4').getContext('2d');
    var myDoughnutChart1 = new Chart(ctx1, {
        type: 'doughnut',
        data: data1
    });
    var myDoughnutChart2 = new Chart(ctx2, {
        type: 'doughnut',
        data: data2
    });
    var myDoughnutChart3 = new Chart(ctx3, {
        type: 'doughnut',
        data: data3
    });
    var myDoughnutChart4 = new Chart(ctx4, {
        type: 'doughnut',
        data: data4
    });



</script>


</body>
</html>