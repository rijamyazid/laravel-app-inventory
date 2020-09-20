@extends('dashboard.dashboard')

@section('sub-content')
<div class="container-fluid p-3">

    {{-- OPSI ATAS --}}
    <div class="row mt-3">
    @if ($sessions['rolePrefix'] == 'super_admin' || $sessions['rolePrefix'] == $role)
        {{-- TOMBOL TAMBAH FOLDER --}}
        <div class="col-md-auto col-sm mx-auto mb-2">
            <a href="#" class="btn btn-success btn-block" id="btn-tambah-folder">
                <span data-feather="folder-plus"></span>
                Tambah Folder
            </a>
        </div>
        {{-- TOMBOL TAMBAH FOLDER --}}
        {{-- TOMBOL TAMBAH FILE --}}
        <div class="col-md-auto col-sm mx-auto mb-2">
            <a href="#" class="btn btn-success btn-block" id="btn-tambah-file">
                <span data-feather="file-plus"></span>
                Tambah File
            </a>
        </div>
        {{-- TOMBOL TAMBAH FILE --}}
    @endif
        {{-- FORM PENCARIAN --}}
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
        {{-- FORM PENCARIAN --}}
    </div>
    {{-- OPSI ATAS --}}

    {{-- MENAMPILKAN ERROR VALIDASI FORM TAMBAH FILE/FOLDER--}}
    <div class="row">
        <div class="col">
            @if ($errors->any())
            <ul class="list-group list-group-flush">
            @foreach ($errors->all() as $error)
                <li class="list-group-item list-group-item-danger">{{ $error }}</li>
            @endforeach
            </ul>
            @endif
        </div>
    </div>

    {{-- FORM TAMBAH FILE FOLDER (HIDEN) --}}
    {{-- FORM TAMBAH FOLDER --}}
    <div class="row my-1">
        <div class="col-6" id="form-tambah-folder" style="display: none">
            <form class="border p-3" action="/{{$role}}/creating/folder/{{$url_path}}" method="POST">
                <h4>Tambah Folder</h4>
                @csrf
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control" name="folder_name" placeholder="Nama Folder">
                    </div>
                    <div class="col-3">
                        <input type="submit" class="btn btn-success" value="Tambah">
                    </div>
                </div>
                <hr class="mb-2">
                <div class="row">
                    <div class="col"><label> <strong>Hak Akses</strong> </label></div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="folder_flag" value="public" id="public" checked>
                            <label class="form-check-label" for="public">Public</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="folder_flag" value="private" id="private">
                            <label class="form-check-label" for="private">Private</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="folder_flag" value="pilih" id="pilih">
                            <label class="form-check-label" for="pilih">Pilih</label>
                        </div>
                    </div>
                </div>
                <div class="row" id="akses-pilih" style="display: none">
                    <div class="col">
                        <hr class="mb-2">
                        <div class="row">
                            <div class="col"><label> <strong>Pilih satu atau lebih Bidang</strong> </label></div>
                        </div>
                        <div id="akses_pilih" class="row">
                            <div class="col">
                                @foreach ($roles as $bidang)
                                @if ( ($bidang->bidang_prefix != 'super_admin') && ($bidang->bidang_prefix != $role))
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="folder_flag_bidang[]" value="{{$bidang->bidang_prefix}}" id="{{$bidang->bidang_prefix}}">
                                        <label class="form-check-label" for="{{$bidang->bidang_prefix}}">{{$bidang->bidang_name}}</label>
                                    </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    {{-- FORM TAMBAH FOLDER --}}
    {{-- FORM TAMBAH FILE --}}
        <div class="col-6" id="form-tambah-file" style="display: none">
            <form action="{{ url($role . '/create/bidang-baru') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Nama Bidang" name="foldername">
                </div>
                <div class="form-group">    
                    <input type="submit" class="btn btn-success w-100" value="Tambah">  
                </div>
            </form>
        </div>
    </div>
    {{-- FORM TAMBAH FILE --}}
    {{-- FORM TAMBAH FILE FOLDER (HIDEN) --}}

    {{-- LOKASI FOLDER --}}
    <div class="row mt-1 mb-1">
        <div class="col">
            <a href="{{ url('/'. $role . '/folder') }}"> <strong>Home</strong></a>
            @foreach ($locations as $location)
                <a><strong> > </strong></a>
                <a href="{{ url('/'. $role . '/folder/' . $location['locLink']) }}"> <strong>{{$location['loc']}}</strong></a>
            @endforeach
        </div>
    </div>
    {{-- LOKASI FOLDER --}}

    {{-- ALERT UNTUK AKSI --}}
    @if ($message = Session::get('successFolder'))
      <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
          <strong>{{ $message }}</strong>
      </div>
    @endif
    {{-- ALERT UNTUK AKSI --}}

    {{-- TAMPILAN FILE FOLDER --}}
    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Opsi</th>
                <th>Dibuat Oleh</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            {{-- TAMPILAN FOLDER --}}
            @foreach ($folders as $folder)
                <tr>
                    <td>
                        <a class="nav-link" href="{{ url("$role/folder/$folder->url_path") }}">
                            <span class="mr-3" data-feather="folder"></span>
                            {{ $folder->folder_name }}
                        </a>
                    </td>
                    <td>
                        @if ($sessions['rolePrefix'] == 'super_admin')
                            <a href="{{ url("$role/edit/folder/$folder->id") }}" class="btn btn-primary">Edit</a> 
                            <a href="{{ url("$role/delete/folder/$folder->id") }}" class="btn btn-danger delete-confirm">Hapus</a>
                        @else
                            @if ($sessions['rolePrefix'] == $role)
                                <a href="{{ url("$role/edit/folder/$folder->id") }}" class="btn btn-primary">Edit</a> 
                                <a href="{{ url("$role/delete/folder/$folder->id") }}" class="btn btn-danger delete-confirm">Hapus</a> 
                            @endif
                        @endif
                    </td>
                    <td>
                        {{ $folder->admin->admin_name }}
                    </td>
                    <td>
                        {{ $folder->created_at }}
                    </td>
                </tr>
            @endforeach
            {{-- TAMPILAN FOLDER --}}
            {{-- TAMPILAN FILE --}}
            @foreach ($files as $file)
                <tr>
                    <td><a href="{{ Storage::disk('local')->url($file->folder->parent_path . '/' .
                                $file->folder->folder_name . '/' .
                                $file->file_uuid) }}" target="_blank" >
                                
                                <span class="mr-3" data-feather="file"></span>
                                {{$file->file_name}}
                        </a>
                    </td>
                    <td>
                        @if ($sessions['rolePrefix'] == 'super_admin')
                            <a href="{{ url("$role/destroy/file/$file->file_uuid") }}" class="btn btn-danger delete-confirm">Hapus</a> 
                            <a href="{{ url("$role/download/file/$file->file_uuid") }}" class="btn btn-success">Download</a>
                        @else
                            @if ($sessions['rolePrefix'] == $role)
                                <a href="{{ url("$role/destroy/file/$file->file_uuid") }}" class="btn btn-danger delete-confirm">Hapus</a> 
                            @endif
                            <a href="{{ url("$role/download/file/$file->file_uuid") }}" class="btn btn-success">Download</a>
                        @endif
                    </td>
                    <td>
                        {{ $file->admin->admin_name }}
                    </td>
                    <td>
                        {{ $file->created_at }}
                    </td>
                </tr>
            @endforeach
            {{-- TAMPILAN FILE --}}
        </tbody>
    </table>
    {{-- TAMPILAN FILE FOLDER --}}
</div>
</div>

<script>
    $("#btn-tambah-folder").click(function(){
        if(!($("#form-tambah-file").is(":hidden"))){
            $("#form-tambah-file").slideToggle(function(){
                $("#form-tambah-folder").slideToggle();
            });
        } else {
            $("#form-tambah-folder").slideToggle();
        }
    });

    $("#btn-tambah-file").click(function(){
        if(!($("#form-tambah-folder").is(":hidden"))){
            $("#form-tambah-folder").slideToggle(function(){
                $("#form-tambah-file").slideToggle();
            });
        } else {
            $("#form-tambah-file").slideToggle();
        }
    });

    $("#pilih").click(function(){
        $("#akses-pilih").slideDown();
    });
    $("#public").click(function(){
        $("#akses-pilih").slideUp();
    });
    $("#private").click(function(){
        $("#akses-pilih").slideUp();
    });
</script>


@endsection