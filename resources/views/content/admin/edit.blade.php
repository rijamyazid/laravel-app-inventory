@extends('dashboard.dashboard')

@section('sub-content')
<div class="container mt-4">
    <h3>Edit User</h3>
    <form action="{{ url('/'. $sessions['rolePrefix']. '/update/admin/'. $admin->admin_username) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="usernameInput">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username" value="{{$admin->admin_username}}" disabled>
        </div>
        <div class="form-group">
            <label for="pwdInput">Password</label>
            <input type="text" class="form-control" name="password" placeholder="Password" value="{{$admin->admin_password}}" required>
        </div>
        <div class="form-group">
            <label for="nameInput">Nama Lengkap</label>
            <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" value="{{$admin->admin_name}}" required>
        </div>
        <div class="form-group">
            <label for="roleInput">Bagian</label>
            <select name="role">
                @foreach($roles as $role)
                @if($admin->bidang_id == $role->id)
                    <option value="{{$role->id}}" selected>{{$role->bidang_name}}</option>
                @else
                    <option value="{{$role->id}}">{{$role->bidang_name}}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-success" value="Update User">
        </div>
    </form>
</div>
@endsection