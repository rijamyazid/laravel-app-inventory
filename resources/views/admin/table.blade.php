@extends('dashboard.dashboard')

@section('sub-content')
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Opsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($folders as $folder)
                <tr>
                    <td><a href="{{ url("$role/folder/$folder->url_path") }}">{{$folder->name}}</a></td>
                    <td><a href="#" class="btn btn-primary">Edit</a> <a href="#" class="btn btn-danger">Hapus</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection