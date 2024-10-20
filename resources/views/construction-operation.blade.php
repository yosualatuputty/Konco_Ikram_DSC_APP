@extends('layouts.navbar')

@section('title', 'Construction Operation')

@section('content')
<link rel="stylesheet" href="{{ asset('css/medical.css') }}">
<div class="container">
    <h1>Construction Operation</h1>
    <p>Welcome to the Construction Operation page!</p>
    <video id="video" autoplay playsinline></video>
    <canvas id="canvas"></canvas>
    <div class="toggle"></div>
    <div class="camera-status" style="display: none;">Camera is off</div>
    <p>This page contains all the information related to Construction operations and procedures.</p>
</div>
<script src="{{ asset('js/construction.js') }}"></script>
@endsection