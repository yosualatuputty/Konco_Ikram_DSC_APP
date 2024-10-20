@extends('layouts.navbar')

@section('title', 'Medical Operation')

@section('content')
<link rel="stylesheet" href="{{ asset('css/medical.css') }}">
<div class="container">
    <h1>Medical Operation</h1>
    <p>Welcome to the Medical Operation page!</p>
    <video id="video" autoplay playsinline></video>
    <canvas id="canvas"></canvas>
    <div class="toggle"></div>
    <div class="camera-status" style="display: none;">Camera is off</div>
    <p>This page contains all the information related to medical operations and procedures.</p>
</div>
<script src="{{ asset('js/medical.js') }}"></script>
@endsection