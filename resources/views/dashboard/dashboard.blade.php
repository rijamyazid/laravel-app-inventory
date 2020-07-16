@extends('layouts.main')

@section('content')
    
    <nav class="navbar navbar-expand-md navbar-light bg-light">
            <ul class="navbar nav nav-pills mr-auto">
                <li class="nav-item">
                    <a href="{{ url("$role/create/folder/$url_path") }}" class="btn btn-success">Tambah Folder</a>
                </li>
            </ul>
    </nav>

    @yield('sub-content')

@endsection