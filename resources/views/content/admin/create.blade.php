@extends('dashboard.dashboard')

@section('sub-content')
<div class="container mt-4">
    <h3>Tambah User</h3>
    <form action="{{ url('/'. $sessions['rolePrefix']. '/store/admin') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="usernameInput">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username">
        </div>
        <div class="form-group">
            <label for="pwdInput">Password</label>
            <input type="text" class="form-control" name="password" placeholder="Password">
        </div>
        <div class="form-group">
            <label for="nameInput">Nama Lengkap</label>
            <input type="text" class="form-control" name="name" placeholder="Nama Lengkap">
        </div>
        <div class="form-group">
            <label for="roleInput">Bagian</label>
            <select name="role">
                @foreach($roles as $role)
                    <option value="{{$role->id}}">{{$role->bidang_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-success" value="Tambah User">
        </div>
    </form>
</div>
@endsection