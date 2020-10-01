@extends('dashboard.dashboard')

@section('sub-content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <form class="border p-3" action="/{{$bidangPrefix}}/update/folder/{{$folder->id}}" method="POST">
                <h4>Ubah Data Folder</h4>
                @csrf
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control" name="folder_name" placeholder="Nama Folder" value="{{$folder->folder_name}}">
                    </div>
                </div>
                <hr class="mb-2">
                <div class="row">
                    <div class="col"><label> <strong>Hak Akses</strong> </label></div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="folder_flag" value="public" id="folder_public" @if (count($flags) == 1)
                                checked
                            @endif >
                            <label class="form-check-label" for="folder_public">Public</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="folder_flag" value="private" id="folder_private" @if (count($flags) == 2)
                            checked
                        @endif>
                            <label class="form-check-label" for="folder_private">Private</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="folder_flag" value="pilih" id="folder_pilih" @if (count($flags) > 2)
                            checked
                        @endif>
                            <label class="form-check-label" for="folder_pilih">Pilih</label>
                        </div>
                    </div>
                </div>
                <div class="row" id="folder_akses_pilih" @if (count($flags) < 2)
                    style="display: none"
                @endif >
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
                                        <input class="form-check-input" type="checkbox" name="folder_flag_bidang[]" value="{{$bidang->bidang_prefix}}" id="folder_{{$bidang->bidang_prefix}}" @if (in_array($bidang->bidang_prefix, $flags))
                                            checked
                                        @endif>
                                        <label class="form-check-label" for="folder_{{$bidang->bidang_prefix}}">{{$bidang->bidang_name}}</label>
                                    </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    <div class="row">
                        <div class="col">
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-auto">
                            <input type="submit" class="btn btn-success" value="Simpan Perubahan">
                        </div>
                        <div class="col-auto">
                    
                            <a href="{{ url( "/$bidangPrefix/move/folder/$folder->id" ) }}" class="btn btn-primary">Pindahkan</a>
                        
                    </div>
                    </div>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection