@extends('dashboard.dashboard')

@section('sub-content')
<div class="container-fluid p-3">

    {{-- OPSI ATAS --}}
    <div class="row mt-3">
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

    {{-- ALERT UNTUK AKSI --}}
    @if ($message = Session::get('successFolder'))
      <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
          <strong>{{ $message }}</strong>
      </div>
    @endif
    {{-- ALERT UNTUK AKSI --}}

    {{-- TAMPILAN FILE FOLDER --}}
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th style="width: 25.0%">Nama</th>
                <th style="width: 35.0%">Opsi</th>
                <th style="width: 20.0%">Dihapus Oleh</th>
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
                            <a href="{{ url("$bidangPrefix/restore/bin/folder/$folder->id") }}" class="btn btn-success btn-sm" style="width: 40.0%">Pulihkan</a>  
                            <a href="{{ url("$bidangPrefix/delete/bin/folder/$folder->id") }}" class="btn btn-danger btn-sm delete-confirm" style="width: 40.0%">Hapus Permanen</a>
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
                            <a href="{{ url("$bidangPrefix/restore/bin/file/$file->id") }}" class="btn btn-success btn-sm" style="width: 40.0%">Pulihkan</a>  
                            <a href="{{ url("$bidangPrefix/delete/bin/file/$file->id") }}" class="btn btn-danger btn-sm delete-confirm" style="width: 40.0%">Hapus Permanen</a>
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
@endsection