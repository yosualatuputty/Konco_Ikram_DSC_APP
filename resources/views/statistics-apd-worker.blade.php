<?php
// URL untuk mendapatkan chart drowsy dan apd
$apdChartUrl = 'http://127.0.0.1:5000/api2/chart/apd';
?>

@extends('layouts.navbar')

@section('title', 'Statistics APD')

@section('content')
<link rel="stylesheet" href="{{ asset('css/stat-worker.css') }}">

<div class="main-container">
    <div class="container-opt" >
        <div class="dropdown">
            <button>OPERATION</button>
            <div class="dropdown-content">
                <a href="#">Medical</a>
                <a href="#">Manufacture</a>
            </div>
        </div>  <div class="dropdown">
            <button>234</button>
            <div class="dropdown-content">
                <a href="/statistics-apd">ALL</a>
                <a href="/worker">234</a>
                <a href="#">435</a>
                <a href="#">478</a>
            </div>
        </div>  <div class="dropdown">
            <button>DATE</button>
            <div class="dropdown-content">
                <a href="#">Link 1</a>
                <a href="#">Link 2</a>
                <a href="#">Link 3</a>
            </div>
        </div>  
    </div>




<div class="top-container">


    <div class="worker-info-container">

        <div class="worker">
            <div class="worker-photo">
                <img src="{{ asset('images/ikram.jpg') }}"  class="photo" alt="Flowers in Chania">
            </div>
            <div class="worker-info">
                <div class="worker-name">
                    Name: Ikram Sabila
                </div>
                <div class="worker-id">
                    ID: 234
                </div>
                <div class="worker-operation">
                    Operation:  Manufacturing
                </div>
            </div>

        </div>

        <div class="weekly-chart-container">
            <div class="weekly-text">Weekly Chart (Compliance)</div>
            <div class="hello-bro">
                <canvas class="canvas-chart" id="timeSeriesChart"></canvas>
            </div>

        </div>

    </div>

    <div class="video-container">
        <div class="video-text">
            Video
        </div>
        <div class="video">

        </div>
    </div>


</div>



    <div class="counts-container">
        <div class="violation">
            <div class="vio-text">
                Violation
            </div>
            <div class="vio-num">
                {{ $violation_count }}
            </div>
        </div>
        <div class="hours">
            <div class="hours-text">
                Avg/Day
            </div>
            <div class="hours-num">
                1.66
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

    {{-- </div>
    <div class="charts-container">
        <div class="pie-chart-container">
            <div class="pie-chart-text">
                Compliance Chart
            </div>
            <div class="pie-chart">
                Chart goes here
            </div>
        </div>
        <div class="vio-chart-container">
            <div class="vio-chart-text">
                Violation Chart
            </div>
            <div class="vio-chart">
                Chart goes here
            </div>
        </div>
        <div class="worker-chart-container">
            <div class="worker-chart-text">
               Worker Violation Table
            </div>
            <div class="worker-chart">
                Chart goes here
            </div>
        </div>
    </div>
</div>

<div class="activity-timeline-container">
    <div class="activity-timeline-text">Activity Timeline</div>
    <div class="activity-timeline-chart">

    </div>
</div>

<div class="one-hour-container">
    <div class="activity-timeline-text">1 Hour Average</div>
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

</script>

{{-- <script src="{{ asset('js/medical.js') }}"></script> --}}
@endsection
