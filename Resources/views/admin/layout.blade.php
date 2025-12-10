@extends('adminlte::page')

@section('title', $title ?? 'Theme Manager')

@section('content_header')
    <h1>{{ $header ?? 'Theme Manager' }}</h1>
@endsection

@section('content')
    <div class="container-fluid">
        @yield('admin-content')
    </div>
@endsection
