@extends('dashboard.dashboard')

@section('sub-content')
<div class="container-fluid p-3">
    <div class="row mt-3">
    @if ($sessions['rolePrefix'] == 'super_admin' || $sessions['rolePrefix'] == $role)
        <div class="col-md-auto col-sm mx-auto mb-2">
            <a href="{{ url("$role/create/folder/$url_path") }}" class="btn btn-success btn-block">
                <span data-feather="folder-plus"></span>
                Tambah Folder
            </a>
        </div>
        <div class="col-md-auto col-sm mx-auto mb-2">
            <a href="{{ url("$role/create/files/$url_path") }}" class="btn btn-success btn-block">
                <span data-feather="file-plus"></span>
                Tambah File
            </a>
        </div>
    @endif
        <div class="col-md">
            <form class="form-inline float-right" action="{{ url('/' . $role . '/search') }}" method="GET">
                <div class="form-group mr-3">
                    <input class="form-control" type="text" placeholder="Cari File" name="bidang" hidden value="{{ $role }}">
                </div>
                <div class="form-group mr-3">
                    <input class="form-control" type="text" placeholder="Cari File" name="q">
                </div>
                <div class="form-group">
                    {{-- <input class="btn btn-success" type="submit" value="Cari"> --}}
                    <button class="btn btn-success" type="submit" value="Cari">
                        <span data-feather="search"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="row mt-1 mb-1">
        <div class="col">
            <a href="{{ url('/'. $role . '/folder') }}"> <strong>Home</strong></a>
            @foreach ($locations as $location)
                <a><strong> > </strong></a>
                <a href="{{ url('/'. $role . '/folder/' . $location['locLink']) }}"> <strong>{{$location['loc']}}</strong></a>
            @endforeach
        </div>
    </div>

    {{-- ALERT UNTUK AKSI --}}
    @if ($message = Session::get('successFolder'))
      <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
          <strong>{{ $message }}</strong>
      </div>
    @endif
    {{-- ALERT UNTUK AKSI --}}

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
                        @if ($sessions['rolePrefix'] == 'super_admin')
                            <a href="{{ url("$role/edit/folder/$folder->id") }}" class="btn btn-primary">Edit</a> 
                            <a href="{{ url("$role/delete/folder/$folder->id") }}" class="btn btn-danger delete-confirm">Hapus</a></td>  
                        @else
                            @if ($sessions['rolePrefix'] == $role)
                                <a href="{{ url("$role/edit/folder/$folder->id") }}" class="btn btn-primary">Edit</a> 
                                <a href="{{ url("$role/delete/folder/$folder->id") }}" class="btn btn-danger delete-confirm">Hapus</a></td>  
                            @endif
                        @endif
                    </tr>
            @endforeach
            @foreach ($files as $file)
                <tr>
                    <td><a href="{{ Storage::disk('local')->url($file->folder->parent_path . '/' .
                                $file->folder->name . '/' .
                                $file->uuid) }}" target="_blank" >
                                
                                <span class="mr-3" data-feather="file"></span>
                                {{$file->filename}}
                        </a>
                    </td>
                    <td>
                        @if ($sessions['rolePrefix'] == 'super_admin')
                            <a href="{{ url("$role/destroy/file/$file->uuid") }}" class="btn btn-danger delete-confirm">Hapus</a> 
                            <a href="{{ url("$role/download/file/$file->uuid") }}" class="btn btn-success">Download</a></td>
                        @else
                            @if ($sessions['rolePrefix'] == $role)
                                <a href="{{ url("$role/destroy/file/$file->uuid") }}" class="btn btn-danger delete-confirm">Hapus</a> 
                            @endif
                            <a href="{{ url("$role/download/file/$file->uuid") }}" class="btn btn-success">Download</a></td>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
</div>
</div>
@endsection