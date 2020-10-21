@extends('dashboard.dashboard')

@section('sub-content')
<div class="container mt-4">
    <h3>Kelola User</h3>

    @if (Session::get('rolePrefix') == 'super_admin')
        <nav class="navbar navbar-expand-md navbar-light bg-light">
            <ul class="navbar nav nav-pills mr-auto">
                <li class="nav-item">
                    <a href="{{ url('/'. Session::get('rolePrefix'). '/create/user') }}" class="btn btn-success">Tambah User</a>
                </li>
            </ul>
        </nav>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Bagian</th>
                <th>Opsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>
                        <a class="nav-link" href="#">
                            <span class="mr-3" data-feather="user"></span>
                            {{ $user->user_name }}
                        </a>
                    </td>
                    <td>
                        <p class="nav-item">
                            {{ $user->bidang->bidang_name }}
                        </p>
                    </td>
                    @if (!($user->user_name == 'SuperAdmin'))
                        <td>
                            @if (Session::get('rolePrefix') == 'super_admin')
                                <a href="{{ url('/'. Session::get('rolePrefix'). '/edit/user/'. $user->user_username) }}" class="btn btn-primary">Edit</a> 
                                <a href="{{ url('/'. Session::get('rolePrefix'). '/delete/user/'. $user->user_username) }}" class="btn btn-danger">Hapus</a></td>  
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection