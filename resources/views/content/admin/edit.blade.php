@extends('dashboard.dashboard')

@section('sub-content')
<div class="container mt-4">
    <h3>Edit User</h3>
    <form action="{{ url('/'. $sessions['rolePrefix']. '/update/admin/'. $admin->username) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="usernameInput">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username" value="{{$admin->username}}" disabled>
        </div>
        <div class="form-group">
            <label for="pwdInput">Password</label>
            <input type="text" class="form-control" name="password" placeholder="Password" value="{{$admin->password}}" required>
        </div>
        <div class="form-group">
            <label for="nameInput">Nama Lengkap</label>
            <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" value="{{$admin->name}}" required>
        </div>
        <div class="form-group">
            <label for="roleInput">Bagian</label>
            <select name="role">
                @foreach($roles as $role)
                @if($admin->role_id == $role->id)
                    <option value="{{$role->id}}" selected>{{$role->role}}</option>
                @else
                    <option value="{{$role->id}}">{{$role->role}}</option>
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