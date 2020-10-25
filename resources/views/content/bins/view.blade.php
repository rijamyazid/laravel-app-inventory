@extends('dashboard.dashboard')

@section('sub-content')
<div class="container-fluid p-3">

    <div class="row mt-3">
        <div class="col">
            <h2>Sampah Sementara</h2>
        </div>
    </div>
    {{-- OPSI ATAS --}}
    <div class="row mb-3">
        @if ( Session::get('rolePrefix') == 'super_admin')
            <div class="col-md">
                <select class="form-control" name="bin_bidang" id="bin_bidang" onchange="binBidangChanges(this.value)">
                    @foreach ($bidangS as $bidang)
                        @if ($bidang->bidang_prefix != 'super_admin')
                            <option value="{{ $bidang->bidang_prefix }}" @if ($bidangPrefix == $bidang->bidang_prefix)
                                selected
                            @endif>{{ $bidang->bidang_name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        @endif
        {{-- FORM PENCARIAN --}}
        <div class="col-md">
            <form class="form-inline float-right" action="{{ url('/' . $bidangPrefix . '/bin/search') }}" method="GET">
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

    {{-- MENAMPILKAN NOTIFIKASi AKSI PADA BIN --}}
    <div class="row">
        <div class="col">
            @foreach (['danger', 'warning', 'success', 'info'] as $jenis)
            @if(Session::has('alert-' . $jenis))
                <p class="alert alert-{{ $jenis }}">{{ Session::get('alert-' . $jenis) }} <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
            @endforeach
        </div>
    </div>
    {{-- MENAMPILKAN NOTIFIKASi AKSI PADA BIN --}}

    {{-- LOKASI FOLDER --}}
    <div class="row mt-1 mb-1">
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
                <th style="width: 20.0%">Opsi</th>
                <th style="width: 30.0%">Lokasi Asal</th>
                <th style="width: 25.0%">Waktu</th>
            </tr>
        </thead>
        <tbody>
            {{-- TAMPILAN FOLDER --}}
            @foreach ($folders as $folder)
                <tr>
                    <td class="pl-3">
                        <a href="{{ url("$bidangPrefix/folder/$folder->url_path") }}">
                            <span class="mr-3" data-feather="folder"></span>
                            {{ Helper::removeFolderTrasedName($folder->folder_name) }}
                        </a>
                    </td>
                    <td>
                        @if (Session::get('rolePrefix') == 'super_admin' || Session::get('rolePrefix') == $bidangPrefix)
                            {{-- <a href="{{ url("$role/edit/folder/$folder->id") }}" class="btn btn-primary">Edit</a> --}}
                            <a href="{{ url("$bidangPrefix/restore/bin/folder/$folder->id") }}" class="btn btn-success btn-sm" >Pulihkan</a>  
                            <a href="{{ url("$bidangPrefix/delete/bin/folder/$folder->id") }}" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus folder ini secara PERMANEN ?')">Hapus Permanen</a>
                        @endif
                    </td>
                    <td>
                        {{ $folder->bidang->bidang_name.'/'.Helper::deleteUrlPathLast($folder->url_path) }}
                    </td>
                    <td>
                        {{ $folder->updated_at }}
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
                            <a href="{{ url("$bidangPrefix/restore/bin/file/$file->file_uuid") }}" class="btn btn-success btn-sm" >Pulihkan</a>  
                            <a href="{{ url("$bidangPrefix/delete/bin/file/$file->file_uuid") }}" class="btn btn-danger btn-sm delete-confirm" onclick="return confirm('Anda yakin ingin menghapus file ini secara PERMANEN ?')">Hapus Permanen</a>
                        @endif
                    </td>
                    <td>
                        {{ $file->folder->bidang->bidang_name.'/'.$file->folder->url_path }}
                    </td>
                    <td>
                        {{ $file->updated_at }}
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