@extends('layouts.navbar')

@section('title', 'Manufacturing Operation')

@section('content')
<link rel="stylesheet" href="{{ asset('css/medical.css') }}">
<div class="container">
    <h1>Manufacturing Operation</h1>
    <p>Welcome to the Manufacturing Operation page!</p>
    <video id="video" autoplay playsinline></video>
    <canvas id="canvas"></canvas>
    <div class="toggle"></div>
    <div class="camera-status" style="display: none;">Camera is off</div>
    <p  id="desc">This page contains all the information related to Manufacturing operations and procedures.</p>
</div>
<!-- <script src="{{ asset('js/manufacturing.php') }}"></script> -->
<?php include 'js/manufacturing.php';?>
@endsection