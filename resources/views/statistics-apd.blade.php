@extends('layouts.navbar')

@section('title', 'Statistics APD')

@section('content')
<link rel="stylesheet" href="{{ asset('css/stat.css') }}">

<div class="main-container">
    <div class="container-opt" >
        <div class="dropdown">
            <button>OPERATION</button>
            <div class="dropdown-content">
                <a href="#">Medical</a>
                <a href="#">Manufacture</a>
            </div>
        </div>  <div class="dropdown">
            <button>WORKER ID</button>
            <div class="dropdown-content">
                <a href="/worker">234</a>
                <a href="#">435</a>
                <a href="#">478</a>
            </div>
        </div>  <div class="dropdown">
            <button>DATE</button>
            <div class="dropdown-content">
                @foreach($hours as $line)
                <tr>
                    {{-- <a href="#">{{ $line[0] }}</a> --}}
                </tr>
                @endforeach
                
            </div>
        </div>  
    </div>
    <div class="weekly-chart-container">
        <div class="weekly-text">Hourly Chart (Violation)</div>
        <div class="weekly-chart">
            <canvas class="canvas-chart" id="timeSeriesChart"></canvas>
        </div>
    </div>
    <div class="counts-container">
        <div class="violation">
            <div class="vio-text">
                Violation
            </div>
            <div class="vio-num">
                {{ $count }}
            </div>
        </div>
        <div class="hours">
            <div class="hours-text">
                Avg/Hours
            </div>
            <div class="hours-num">
                {{ number_format($count/$hours_total,2) }}
            </div>
        </div>  
        <div class="active">
            <div class="active-text">
                Active Time
            </div>
            <div class="active-num">
                00:00
            </div>
        </div>

    </div>
    {{-- <div class="charts-container">
        <div class="pie-chart-container">
            <div class="pie-chart-text">
                Compliance Chart
            </div>
            <div class="pie-chart">
                <canvas id="pieChart"></canvas>
            </div>
        </div> --}}
        {{-- <div class="vio-chart-container">
            <div class="vio-chart-text">
                Violation Chart
            </div>
            <div class="vio-chart">
                <canvas id="barChart"></canvas>
            </div>
        </div>
        <div class="worker-chart-container">
            <div class="worker-chart-text">
               Worker Violation Table
            </div>
            <div class="worker-chart">
                <table>
                    <thead>
                        <tr>
                            <th>Worker</th>
                            <th>Violation</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tableData as $row)
                        <tr>
                            <td>{{ $row[0] }}</td>
                            <td>{{ $row[1] }}</td>
                            <td>{{ $row[2] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> --}}
    {{-- </div>
</div> --}}

{{-- <div class="activity-timeline-container">
    <div class="activity-timeline-text">Activity Timeline</div>
    <div class="activity-timeline-chart">
        <canvas id="activity-chart" class="activity-chart"></canvas>
    </div>
</div>

<div class="one-hour-container">
    <div class="activity-timeline-text">{{  var_dump($new) }}</div>
    <div class="one-hour-chart">

    </div>
</div> --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('timeSeriesChart').getContext('2d');
    const labels = {!! json_encode($labels) !!}; // Pass PHP data to JavaScript
    const data = {!! json_encode($data) !!};     // Pass PHP data to JavaScript

    const chart = new Chart(ctx, {
        type: 'bar',  // Chart type
        data: {
            labels: labels,  // Time labels
            datasets: [{
                label: 'Violation',
                data: data,  // Mock data values
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const ctx_pie = document.getElementById('pieChart').getContext('2d');
        const labels_pie = {!! json_encode($labels_pie) !!};  // Labels (Categories)
        const data_pie = {!! json_encode($data_pie) !!};      // Data values

        const pieChart = new Chart(ctx_pie, {
            type: 'pie',  // Specify chart type as 'pie'
            data: {
                labels: labels_pie,
                datasets: [{
                    label: 'Category Distribution',
                    data: data_pie,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',  // Color for Category A
                        'rgba(54, 162, 235, 0.2)',  // Color for Category B
                        'rgba(255, 206, 86, 0.2)',  // Color for Category C
                        'rgba(75, 192, 192, 0.2)'   // Color for Category D
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,  // Make chart responsive
                maintainAspectRatio: false  // Allow it to fill the container
            }
        });

        // const ctx_vio = document.getElementById('barChart').getContext('2d');
        // const labels_vio = {!! json_encode($labels_vio) !!};  // Labels for the chart (e.g., January, February, etc.)
        // const data_vio = {!! json_encode($data_vio) !!};      // Data values for the chart

        // const barChart = new Chart(ctx_vio, {
        //     type: 'bar',  // Specify the chart type as 'bar'
        //     data: {
        //         labels: labels_vio,
        //         datasets: [{
        //             label: 'Sample Data',  // Name of the dataset
        //             data: data_vio,
        //             backgroundColor: [
        //                 'rgba(255, 99, 132, 0.2)',  // Bar color for January
        //                 'rgba(54, 162, 235, 0.2)',  // Bar color for February
        //                 'rgba(255, 206, 86, 0.2)',  // Bar color for March
        //                 'rgba(75, 192, 192, 0.2)'   // Bar color for April
        //             ],
        //             borderColor: [
        //                 'rgba(255, 99, 132, 1)',
        //                 'rgba(54, 162, 235, 1)',
        //                 'rgba(255, 206, 86, 1)',
        //                 'rgba(75, 192, 192, 1)'
        //             ],
        //             borderWidth: 1
        //         }]
        //     },
        //     options: {
        //         responsive: true,  // Make the chart responsive
        //         maintainAspectRatio: false,  // Allow the chart to fill the container
        //         scales: {
        //             y: {
        //                 beginAtZero: true  // Start the y-axis at 0
        //             }
        //         }
        //     }
        // });

        const ctx_timeline = document.getElementById('activity-chart').getContext('2d');
        const labels_timeline = {!! json_encode($labels_timeline) !!}; // Pass PHP data to JavaScript
        const data_timeline = {!! json_encode($data_timeline) !!};     // Pass PHP data to JavaScript

        const activity_chart = new Chart(ctx_timeline, {
            type: 'bar',  // Chart type
            data: {
                labels: labels_timeline,  // Time labels
                datasets: [{
                    label: 'Activity over Time',
                    data: data_timeline,  // Mock data values
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
</script>
@endsection
