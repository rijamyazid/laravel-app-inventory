@extends('dashboard.dashboard')

@section('sub-content')
<div class="container mt-4">
    <h3>Kelola User</h3>

    @if ($sessions['rolePrefix'] == 'super_admin')
        <nav class="navbar navbar-expand-md navbar-light bg-light">
            <ul class="navbar nav nav-pills mr-auto">
                <li class="nav-item">
                    <a href="{{ url('/'. $sessions['rolePrefix']. '/create/admin') }}" class="btn btn-success">Tambah User</a>
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
            @foreach ($admin as $admin)
                <tr>
                    <td>
                        <a class="nav-link" href="#">
                            <span class="mr-3" data-feather="user"></span>
                            {{ $admin->name }}
                        </a>
                    </td>
                    <td>
                        <p class="nav-item">
                            {{ $admin->role->role }}
                        </p>
                    </td>
                    @if (!($admin->name == 'SuperAdmin'))
                        <td>
                            @if ($sessions['rolePrefix'] == 'super_admin')
                                <a href="{{ url('/'. $sessions['rolePrefix']. '/edit/admin/'. $admin->username) }}" class="btn btn-primary">Edit</a> 
                                <a href="{{ url('/'. $sessions['rolePrefix']. '/delete/admin/'. $admin->username) }}" class="btn btn-danger">Hapus</a></td>  
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection