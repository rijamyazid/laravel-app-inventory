@extends('dashboard.dashboard')

@section('sub-content')
    @if ($sessions['role'] == 'super_admin')
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
    @else
        @if ($sessions['role'] == $role)
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
        @endif
    @endif
    
    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Opsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($folders as $folder)
                <tr>
                    <td>
                        <a class="nav-link" href="{{ url("$role/folder/$folder->url_path") }}">
                            <span class="mr-3" data-feather="folder"></span>
                            {{ $folder->name }}
                        </a>
                    </td>
                    <td>
                        @if ($sessions['role'] == 'super_admin')
                            <a href="{{ url("$role/edit/folder/$folder->id") }}" class="btn btn-primary">Edit</a> 
                            <a href="{{ url("$role/delete/folder/$folder->id") }}" class="btn btn-danger">Hapus</a></td>  
                        @else
                            @if ($sessions['role'] == $role)
                                <a href="{{ url("$role/edit/folder/$folder->id") }}" class="btn btn-primary">Edit</a> 
                                <a href="{{ url("$role/delete/folder/$folder->id") }}" class="btn btn-danger">Hapus</a></td>  
                            @endif
                        @endif
                    </tr>
            @endforeach
            @foreach ($files as $file)
                <tr>
                    <td><a href="{{ Storage::disk('local')->url($file->folder->parent_path . '/' .
                                $file->folder->name . '/' .
                                $file->uuid) }}" target="_blank" >{{$file->filename}}</a></td>
                    <td>
                        @if ($sessions['role'] == 'super_admin')
                            <a href="{{ url("$role/destroy/file/$file->uuid") }}" class="btn btn-danger">Hapus</a> 
                            <a href="{{ url("$role/download/file/$file->uuid") }}" class="btn btn-success">Download</a></td>
                        @else
                            @if ($sessions['role'] == $role)
                                <a href="{{ url("$role/destroy/file/$file->uuid") }}" class="btn btn-danger">Hapus</a> 
                            @endif
                            <a href="{{ url("$role/download/file/$file->uuid") }}" class="btn btn-success">Download</a></td>
                        @endif
                    </tr>
            @endforeach
        </tbody>
    </table>
@endsection