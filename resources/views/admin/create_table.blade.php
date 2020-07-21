@extends('dashboard.dashboard')

@section('sub-content')
<div class="container mt-4">
    <form action="/{{$role}}/creating/folder/{{$url_path}}" method="POST">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control" name="folder_name" placeholder="Nama Folder">
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-success" value="Buat Folder">
        </div>
    </form>
</div>
@endsection