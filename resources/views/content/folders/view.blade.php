@extends('dashboard.dashboard')

@section('sub-content')
<div class="container-fluid p-3">

    {{-- OPSI ATAS --}}
    <div class="row mt-3">
    @if (Session::get('rolePrefix') == 'super_admin' || Session::get('rolePrefix') == $bidangPrefix)
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
            <form class="form-inline float-right" action="{{ url('/' . $bidangPrefix . '/search') }}" method="GET">
                <div class="form-group mr-3">
                    <input class="form-control" type="text" placeholder="Cari File" name="bidang" hidden value="{{ $bidangPrefix }}">
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

    {{-- MENAMPILKAN NOTIFIKASi AKSI PADA FOLDER/FILE --}}
    <div class="row">
        <div class="col">
            @foreach (['danger', 'warning', 'success', 'info'] as $jenis)
            @if(Session::has('alert-' . $jenis))
                <p class="alert alert-{{ $jenis }}">{{ Session::get('alert-' . $jenis) }} <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
            @endforeach
        </div>
    </div>
    {{-- MENAMPILKAN NOTIFIKASi AKSI PADA FOLDER/FILE --}}

    {{-- MENAMPILKAN NOTIFIKASi PEMINDHAKAN PADA FOLDER/FILE --}}
    <div class="row">
        <div class="col">
            @foreach (['folder', 'file'] as $item)
            @if (Session::has('move_'.$item.'Id'))
                <p class="alert alert-info">Pindahkan {{ $item }} {{ Session::get('move_'.$item.'Name') }} ke folder {{ Session::get('move_folderNameGoal') }} ? <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
            @endforeach
        </div>
    </div>
    {{-- MENAMPILKAN NOTIFIKASi PEMINDAHAN PADA FOLDER/FILE --}}

    <div class="row">
        @if (!is_null(Session::get('move_folderId')))
            <div class="col-md-2 col-sm">
                <a href="{{ url("/$bidangPrefix/moving/folder/$urlPath") }}" class="btn btn-success btn-block" id="btn-tambah-file">
                    Pindahkan
                </a>
            </div>
            <div class="col-md-2 col-sm">
                <a href="{{ url("/$bidangPrefix/movingCancel/folder/$urlPath") }}" class="btn btn-danger btn-block" id="btn-tambah-file">
                    Batalkan
                </a>
            </div>
        @endif
        @if (!is_null(Session::get('move_fileId')))
            <div class="col-md-2 col-sm">
                <a href="{{ url("/$bidangPrefix/moving/file/$urlPath") }}" class="btn btn-success btn-block" id="btn-tambah-file">
                    Pindahkan
                </a>
            </div>
            <div class="col-md-2 col-sm">
                <a href="{{ url("/$bidangPrefix/movingCancel/file/$urlPath") }}" class="btn btn-danger btn-block" id="btn-tambah-file">
                    Batalkan
                </a>
            </div>
        @endif
    </div>

    {{-- FORM TAMBAH FILE FOLDER (HIDEN) --}}
    {{-- FORM TAMBAH FOLDER --}}
    <div class="row mb-1">
        <div class="col-6" id="form-tambah-folder" style="display: none">
            <form class="border p-3" action="/{{$bidangPrefix}}/creating/folder/{{$urlPath}}" method="POST">
                <h4>Tambah Folder</h4>
                @csrf
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control" name="folder_name" placeholder="Nama Folder">
                    </div>
                    <div class="col-4">
                        <input type="submit" class="btn btn-success" value="Tambah Folder">
                    </div>
                </div>
                <hr class="mb-2">
                <div class="row">
                    <div class="col"><label> <strong>Hak Akses</strong> </label></div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="folder_flag" value="public" id="folder_public" checked>
                            <label class="form-check-label" for="folder_public">Public</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="folder_flag" value="private" id="folder_private">
                            <label class="form-check-label" for="folder_private">Private</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="folder_flag" value="pilih" id="folder_pilih">
                            <label class="form-check-label" for="folder_pilih">Pilih</label>
                        </div>
                    </div>
                </div>
                <div class="row" id="folder_akses_pilih" style="display: none">
                    <div class="col">
                        <hr class="mb-2">
                        <div class="row">
                            <div class="col"><label> <strong>Pilih satu atau lebih Bidang</strong> </label></div>
                        </div>
                        <div class="row">
                            <div class="col">
                                @foreach ($bidangS as $bidang)
                                @if ( ($bidang->bidang_prefix != 'super_admin') && ($bidang->bidang_prefix != $bidangPrefix))
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="folder_flag_bidang[]" value="{{$bidang->bidang_prefix}}" id="folder_{{$bidang->bidang_prefix}}">
                                        <label class="form-check-label" for="folder_{{$bidang->bidang_prefix}}">{{$bidang->bidang_name}}</label>
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
            <form class="border p-3" action="/{{$bidangPrefix}}/store/files/{{$urlPath}}" method="POST" enctype="multipart/form-data">
                <h4>Tambah File</h4>
                @csrf
                <div class="row">
                    <div class="col">
                        <input type="file" class="form-control-file" name="file_name[]" multiple>
                    </div>
                    <div class="col-4">
                        <input type="submit" class="btn btn-success" value="Tambah File">
                    </div>
                </div>
                <hr class="mb-2">
                <div class="row">
                    <div class="col"><label> <strong>Hak Akses</strong> </label></div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="file_flag" value="public" id="file_public" checked>
                            <label class="form-check-label" for="file_public">Public</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="file_flag" value="private" id="file_private">
                            <label class="form-check-label" for="file_private">Private</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="file_flag" value="pilih" id="file_pilih">
                            <label class="form-check-label" for="file_pilih">Pilih</label>
                        </div>
                    </div>
                </div>
                <div class="row" id="file_akses_pilih" style="display: none">
                    <div class="col">
                        <hr class="mb-2">
                        <div class="row">
                            <div class="col"><label> <strong>Pilih satu atau lebih Bidang</strong> </label></div>
                        </div>
                        <div class="row">
                            <div class="col">
                                @foreach ($bidangS as $bidang)
                                @if ( ($bidang->bidang_prefix != 'super_admin') && ($bidang->bidang_prefix != $bidangPrefix))
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="file_flag_bidang[]" value="{{$bidang->bidang_prefix}}" id="file_{{$bidang->bidang_prefix}}">
                                        <label class="form-check-label" for="file_{{$bidang->bidang_prefix}}">{{$bidang->bidang_name}}</label>
                                    </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- FORM TAMBAH FILE --}}
    {{-- FORM TAMBAH FILE FOLDER (HIDEN) --}}

    {{-- LOKASI FOLDER --}}
    <div class="row mb-1">
        <div class="col">
            <a href="{{ url('/'. $bidangPrefix . '/folder') }}"> <strong>Home</strong></a>
            @foreach (Helper::folderLocation($urlPath) as $path)
                <a><strong> > </strong></a>
                <a href="{{ url('/'. $bidangPrefix . '/folder/' . $path['urlPath']) }}"> <strong>{{ $path['path'] }}</strong></a>
            @endforeach
        </div>
    </div>
    {{-- LOKASI FOLDER --}}

    {{-- TAMPILAN FILE FOLDER --}}
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th style="width: 25.0%">Nama</th>
                <th style="width: 35.0%">Opsi</th>
                <th style="width: 20.0%">Dibuat Oleh</th>
                <th style="width: 20.0%">Waktu</th>
            </tr>
        </thead>
        <tbody>
            {{-- TAMPILAN FOLDER --}}
            @foreach ($folders as $folder)
                <tr>
                    <td class="pl-3">
                        <a href="{{ url("$bidangPrefix/folder/$folder->url_path") }}">
                            <span class="mr-3" data-feather="folder"></span>
                            {{ $folder->folder_name }}
                        </a>
                    </td>
                    <td>
                        @if (Session::get('rolePrefix') == 'super_admin' || Session::get('rolePrefix') == $bidangPrefix)
                            {{-- <a href="{{ url("$role/edit/folder/$folder->id") }}" class="btn btn-primary">Edit</a> --}}
                            <a href="{{ url("$bidangPrefix/edit/folder/$folder->id") }}" class="btn btn-primary btn-sm" style="width: 20.0%">Edit</a>  
                            <a href="{{ url("$bidangPrefix/delete/folder/$folder->id") }}" class="btn btn-danger btn-sm" style="width: 20.0%" onclick="return confirm('Anda yakin ingin menghapus folder? (Folder akan dipindahkan ke sampah sementara)')">Hapus</a>
                            <a href="{{ url( "$bidangPrefix/move/folder/$folder->id" ) }}" class="btn btn-primary btn-sm" style="width: 30.0%">Pindahkan</a>
                        @endif
                    </td>
                    <td>
                        {{ $folder->user->user_name }}
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
                    <td class="pl-3"><a href="{{ Storage::disk('local')->url($file->folder->parent_path . '/' .
                                $file->folder->folder_name . '/' .
                                $file->file_uuid) }}" target="_blank" >
                                
                                <span class="mr-3" data-feather="file"></span>
                                {{$file->file_name}}
                        </a>
                    </td>
                    <td>
                        @if (Session::get('rolePrefix') == 'super_admin' || Session::get('rolePrefix') == $bidangPrefix)
                            <a href="{{ url("$bidangPrefix/edit/file/$file->file_uuid") }}" class="btn btn-primary btn-sm" style="width: 20.0%">Edit</a>  
                            <a href="{{ url("$bidangPrefix/destroy/file/$file->file_uuid") }}" class="btn btn-danger btn-sm" style="width: 20.0%" onclick="return confirm('Anda yakin ingin menghapus file? (file akan dipindahkan ke sampah sementara)')">Hapus</a> 
                            <a href="{{ url( "$bidangPrefix/move/file/$file->file_uuid" ) }}" class="btn btn-primary btn-sm" style="width: 30.0%">Pindahkan</a>
                            <a href="{{ url("$bidangPrefix/download/file/$file->file_uuid") }}" class="btn btn-success btn-sm" style="width: 20.0%">Unduh</a>
                        @else
                            <a href="{{ url("$bidangPrefix/download/file/$file->file_uuid") }}" class="btn btn-success">Download</a>
                        @endif
                    </td>
                    <td>
                        {{ $file->user->user_name }}
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
@endsection