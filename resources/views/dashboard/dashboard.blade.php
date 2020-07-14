@extends('layouts.main')

@section('content')
    <nav class="navbar navbar-expand-md navbar-light bg-light">
            <ul class="navbar nav nav-pills mr-auto">
                <li class="nav-item">
                    <a href="{{$role}}/folder/{{$url_path}}/create" class="btn btn-success">Tambah Folder</a>
                </li>
            </ul>

            <ul class="navbar nav nav-pills ">
                <li class="nav-item">
                    <a class="btn btn-danger" href="/logout">Logout</a>
                </li>
            </ul>
    </nav>

    @yield('sub-content')

@endsection