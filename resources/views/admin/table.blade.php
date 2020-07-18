@extends('dashboard.dashboard')

@section('sub-content')
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
                    <td>
                        <a href="{{ url("$role/edit/folder/$folder->id") }}" class="btn btn-primary">Edit</a> 
                        <a href="{{ url("$role/delete/folder/$folder->id") }}" class="btn btn-danger">Hapus</a></td>
                    </tr>
            @endforeach
            @foreach ($files as $file)
                <tr>
                    <td><a href="{{ Storage::disk('local')->url($file->folder->parent_path . '/' .
                                $file->folder->name . '/' .
                                $file->uuid) }}" target="_blank" >{{$file->filename}}</a></td>
                    <td>
                        <a href="{{ url("$role/destroy/file/$file->uuid") }}" class="btn btn-danger">Hapus</a> 
                        <a href="{{ url("$role/download/file/$file->uuid") }}" class="btn btn-success">Download</a></td>
                    </tr>
            @endforeach
        </tbody>
    </table>
@endsection