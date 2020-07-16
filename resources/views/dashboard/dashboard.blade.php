@extends('layouts.main')

@section('content')
    <nav class="navbar navbar-expand-md navbar-light" style="background-color: #e3f2fd;">
        <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/{role_prefix}">BKKBN</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/{role_prefix}">Home</a>
                </li>
            </ul>
        </div>
        <div class="mx-auto order-0">
            <a class="navbar-brand mx-auto" href="/{role_prefix}">Sistem Manajemen Arsip BKKBN Jawa Barat</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="btn btn-danger" href="/logout">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    
    <nav class="navbar navbar-expand-md navbar-light bg-light">
            <ul class="navbar nav nav-pills mr-auto">
                <li class="nav-item">
                    <a href="{{ url("$role/create/folder/$url_path") }}" class="btn btn-success">Tambah Folder</a>
                </li>
            </ul>
            <ul class="navbar nav nav-pills mr-auto">
                <li class="nav-item">
                    <a href="{{ url("$role/create/files/$url_path") }}" class="btn btn-success">Tambah File</a>
                </li>
            </ul>
    </nav>

    @yield('sub-content')

@endsection