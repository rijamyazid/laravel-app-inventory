@extends('dashboard.dashboard')

@section('sub-content')
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <h3>Ubah Data File</h3>
            <form class="border p-3" action="{{ url("/$bidangPrefix/update/file/$file->file_uuid") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col">File saat ini : <a href="{{ Storage::disk('local')->url($file->folder->parent_path . '/' .
                        $file->folder->folder_name . '/' .
                        $file->file_uuid) }}" target="_blank" >{{$file->file_name}}</a></div>
                </div>
                <hr class="mb-2">
                <div class="row">
                    <div class="col"><label> <strong>Ganti File</strong> </label></div>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="file" class="form-control-file" name="file_name">
                    </div>
                </div>
                <hr class="mb-2">
                <div class="row">
                    <div class="col"><label> <strong>Hak Akses</strong> </label></div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="file_flag" value="public" id="file_public" @if ( count($flags) == 1 )
                                checked
                            @endif >
                            <label class="form-check-label" for="file_public">Public</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="file_flag" value="private" id="file_private" @if ( count($flags) == 2 )
                            checked
                        @endif>
                            <label class="form-check-label" for="file_private">Private</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="file_flag" value="pilih" id="file_pilih" @if ( count($flags) > 2 )
                            checked
                        @endif>
                            <label class="form-check-label" for="file_pilih">Pilih</label>
                        </div>
                    </div>
                </div>
                <div class="row" id="file_akses_pilih" @if (count($flags) < 2)
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
                                        <input class="form-check-input" type="checkbox" name="file_flag_bidang[]" value="{{$bidang->bidang_prefix}}" id="file_{{$bidang->bidang_prefix}}" @if (in_array($bidang->bidang_prefix, $flags))
                                            checked
                                        @endif>
                                        <label class="form-check-label" for="file_{{$bidang->bidang_prefix}}">{{$bidang->bidang_name}}</label>
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
                        <div class="col">
                            <input type="submit" class="btn btn-success" style="width: 15.0%" value="Simpan Perubahan">
                            <a href="{{ url( "$bidangPrefix/folder/". $file->folder->url_path ) }}" class="btn btn-danger" style="width: 15.0%">Batalkan</a>
                        </div>
                    </div>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection