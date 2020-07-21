@extends('dashboard.dashboard')

@section('sub-content')
<div class="container mt-4">
    <h3>Kelola User</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Bagian</th>
                <th>Opsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($admin as $user)
                <tr>
                    <td>
                        <a class="nav-link" href="#">
                            <span class="mr-3" data-feather="user"></span>
                            {{ $user->name }}
                        </a>
                    </td>
                    <td>
                        <p class="nav-item">
                            {{ $user->role->role }}
                        </p>
                    </td>
                    @if (!($user->role->role == 'Super Admin'))
                        <td>
                            @if ($sessions['rolePrefix'] == 'super_admin')
                                <a href="#" class="btn btn-primary">Edit</a> 
                                <a href="#" class="btn btn-danger">Hapus</a></td>  
                            @else
                                @if ($sessions['rolePrefix'] == $role)
                                    <a href="#" class="btn btn-primary">Edit</a> 
                                    <a href="#" class="btn btn-danger">Hapus</a></td>  
                                @endif
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection