@extends('dashboard.dashboard')

@section('sub-content')
<div class="container mt-4">
    <h3>Edit User</h3>
    @if ($errors->any())
        <ul class="list-group list-group-flush">
            @foreach ($errors->all() as $error)
                <li class="list-group-item list-group-item-danger">{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <form action="{{ url('/'. Session::get('rolePrefix'). '/update/user/'. $user->user_username) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="usernameInput">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username" value="{{$user->user_username}}" disabled>
        </div>
        <div class="form-group">
            <label for="pwdInput">Password</label>
            <input type="text" class="form-control" name="password" placeholder="Password" value="{{$user->user_password}}" required>
        </div>
        <div class="form-group">
            <label for="nameInput">Nama Lengkap</label>
            <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" value="{{$user->user_name}}" required>
        </div>
        <div class="form-group">
            <label for="roleInput">Bagian</label>
            <select name="role">
                @foreach($bidangS as $bidang)
                @if($user->bidang_id == $bidang->id)
                    <option value="{{$bidang->id}}" selected>{{$bidang->bidang_name}}</option>
                @else
                    <option value="{{$bidang->id}}">{{$bidang->bidang_name}}</option>
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