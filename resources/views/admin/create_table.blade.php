@extends('layouts.main')

@section('content')
    <form action="/{{$role}}/folder/{{$url_path}}/creating" method="POST">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control" name="folder_name" placeholder="Nama Folder">
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-success" value="Buat Folder">
        </div>
    </form>
@endsection