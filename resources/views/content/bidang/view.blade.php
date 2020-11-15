@extends('dashboard.dashboard')

@section('sub-content')
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <h2 >Kelola Bidang</h2>
        </div>
    </div>
    <div class="row mb-3" >
        <div class="col">
            <a href="javascript:void(0)" class="btn btn-success" id="btn-tambah-bidang">Tambah Bidang Baru</a>
        </div>
    </div>
    {{-- MENAMPILKAN NOTIFIKASi AKSI PADA BIDANG --}}
    <div class="row mb-1">
        <div class="col">
            @foreach (['danger', 'warning', 'success', 'info'] as $jenis)
            @if(Session::has('alert-' . $jenis))
                <p class="alert alert-{{ $jenis }}">{{ Session::get('alert-' . $jenis) }} <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
            @endforeach
        </div>
    </div>
    {{-- MENAMPILKAN NOTIFIKASi AKSI PADA BIDANG --}}
    <div class="row mb-3" id="form-tambah-bidang" style="display: none">
        <div class="col-6">
            <form class="border p-3" action="/super_admin/create/bidang/" method="POST">
                <h4>Masukan Nama Bidang</h4>
                @csrf
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control" name="bidang_name" placeholder="Nama Bidang" maxlength="30">
                    </div>
                    <div class="col-4">
                        <input type="submit" class="btn btn-success" value="Tambah Bidang">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered table-sm">
                <thead>
                    <th style="width=10%">No</th>
                    <th style="width=30%">Nama Bidang</th>
                    <th style="width=30%">Bidang Prefix</th>
                    <th style="width=30%">Opsi</th>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @foreach ($bidangData as $bidang)
                    <tr>
                        @if ($bidang->bidang_prefix != 'super_admin')
                            <td>{{ $count }}</td>
                            <td>{{ $bidang->bidang_name }}</td>
                            <td>{{ $bidang->bidang_prefix }}</td>
                            <td>
                                <a href="{{ url("/super_admin/edit/bidang/".$bidang->id) }}" class="btn btn-primary btn-sm">Ubah Bidang</a>
                                <a href="{{ url("/super_admin/delete/bidang/".$bidang->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Menghapus bidang akan menghilangkan semua file dan folder didalamnya secara PERMANEN. Anda yakin ingin menghapus bidang ini?')">Hapus Bidang</a>
                            </td>
                            @php
                                $count++;
                            @endphp
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>  
        </div>
    </div>
</div>  
@endsection
