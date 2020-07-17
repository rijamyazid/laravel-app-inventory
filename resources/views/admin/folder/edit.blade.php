@extends('layouts.main')

@section('content')
    <form action="/{{ $role }}/update/folder/{{ $folder->id }}" method="POST">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control" name="foldername" placeholder="Nama Folder" 
                value="{{ $folder->name }}">
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-success" value="Buat Folder">
        </div>
    </form>
@endsection