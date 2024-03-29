@extends('dashboard.dashboard')

@section('sub-content')
<div class="container mt-4">
    <h3>Edit User</h3>
    <form action="{{ url('/'. Session::get('rolePrefix'). '/update/user/'. $user->user_username) }}" method="POST">
        @csrf
        {{-- MENAMPILKAN NOTIFIKASi AKSI PADA FOLDER/FILE --}}
        <div class="row">
            <div class="col">
                @foreach (['danger', 'warning', 'success', 'info'] as $jenis)
                @if(Session::has('alert-' . $jenis))
                    <p class="alert alert-{{ $jenis }}">{{ Session::get('alert-' . $jenis) }} <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                @endif
                @endforeach
            </div>
        </div>
        {{-- MENAMPILKAN NOTIFIKASi AKSI PADA FOLDER/FILE --}}
        <div class="form-group">
            <label for="usernameInput">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username" value="{{$user->user_username}}" disabled>
        </div>
        <div class="form-group">
            <label for="pwdInput">Password</label>
            <input type="text" class="form-control" name="password" placeholder="Password" value="{{$user->user_password}}" maxlength="25" minlength="8" required>
        </div>
        <div class="form-group">
            <label for="nameInput">Nama Lengkap</label>
            <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" value="{{$user->user_name}}" maxlength="25" required>
        </div>
        <div class="form-group">
            <label for="roleInput">Bagian</label>
            <select name="bidang">
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
            <input type="submit" class="btn btn-success" style="width: 15.0%" value="Update User">
            <a href="{{ url( "$bidangPrefix/view/user") }}" class="btn btn-danger" style="width: 15.0%">Batalkan</a>
        </div>
    </form>
</div>
@endsection