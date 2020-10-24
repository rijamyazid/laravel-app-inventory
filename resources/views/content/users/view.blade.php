@extends('dashboard.dashboard')

@section('sub-content')
<div class="container mt-4">
    <div class="row">
        <div class="col">
            <h3>Kelola User</h3>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <a href="{{ url('/'. Session::get('rolePrefix'). '/create/user') }}" class="btn btn-success">Tambah User</a>
        </div>
    </div>
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
    <div class="row">
        <div class="col">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th class="pl-3">No</th>
                        <th class="pl-3">Nama</th>
                        <th class="pl-3">Bagian</th>
                        <th class="pl-3">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @foreach ($users as $user)
                        <tr>
                            <td class="pl-3">
                                {{ $count }}
                            </td>
                            <td class="pl-3">
                                {{ $user->user_name }}
                            </td>
                            <td class="pl-3">
                                {{ $user->bidang->bidang_name }}
                            </td>
                            @if (!($user->user_name == 'SuperAdmin'))
                                <td>
                                    @if (Session::get('rolePrefix') == 'super_admin')
                                        <a href="{{ url('/'. Session::get('rolePrefix'). '/edit/user/'. $user->user_username) }}" class="btn btn-primary btn-sm" style="width: 30.0%">Edit</a> 
                                        <a href="{{ url('/'. Session::get('rolePrefix'). '/delete/user/'. $user->user_username) }}" class="btn btn-danger btn-sm" style="width: 30.0%" onclick="return confirm('Anda yakin ingin menghapus user?')">Hapus</a></td>  
                                    @endif
                                </td>
                            @endif
                        </tr>
                        @php
                            $count++;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection