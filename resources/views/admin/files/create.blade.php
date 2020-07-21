@extends('dashboard.dashboard')

@section('sub-content')
<div class="container mt-4">
    <h3>Tambah File</h3>
    <form action="/{{$role}}/store/files/{{$url_path}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <input type="file" class="form-control" name="filenames[]" multiple>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-success" value="Tambahkan File">
        </div>
    </form>
</div>
@endsection