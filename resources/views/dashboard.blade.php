@extends('layouts.app')

@section('title', 'Dashboard - Daily Log System')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
</nav>

<h1 class="mb-4">Dashboard</h1>

<div class="card">
    <div class="card-body">
        <h2 class="card-title">Welcome {{ Auth::user()->name }}</h2>
        <p class="card-text">This is the dashboard page for user {{ Auth::user()->name }}</p>
    </div>
</div>
@endsection
