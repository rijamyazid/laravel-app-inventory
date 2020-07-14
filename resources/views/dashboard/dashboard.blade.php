@extends('layouts.main')

@section('content')
    <br>
    <a href="{{$role}}/folder/{{$url_path}}/create" class="btn btn-success">Tambah Folder</a>
    <br>
    <br>
    @yield('sub-content')

@endsection