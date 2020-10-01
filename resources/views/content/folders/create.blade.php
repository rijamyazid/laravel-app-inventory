@extends('dashboard.dashboard')

@section('sub-content')
<div class="container-fluid">
    <div class="row">
        <div class="col" id="form-tambah-folder" style="display: none">
            <form class="border p-3" action="/{{$role}}/creating/folder/{{$url_path}}" method="POST">
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
                                @foreach ($roles as $bidang)
                                @if ( ($bidang->bidang_prefix != 'super_admin') && ($bidang->bidang_prefix != $role))
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
    </div>
</div>
@endsection