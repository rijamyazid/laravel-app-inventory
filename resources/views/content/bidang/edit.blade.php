@extends('dashboard.dashboard')

@section('sub-content')
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col" id="form-ubah-bidang">
            <h3>Ubah Data Bidang</h3>
            <form class="border p-3" action="{{ url("/super_admin/update/bidang/$bidang->id") }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col">
                        {{-- MENAMPILKAN NOTIFIKASi AKSI MENAMBAHKAN BIDANG --}}
                        @foreach (['danger', 'warning', 'success', 'info'] as $jenis)
                        @if(Session::has('alert-' . $jenis))
                            <p class="alert alert-{{ $jenis }}">{{ Session::get('alert-' . $jenis) }} <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                        @endif
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control" name="bidang_name" placeholder="Nama Bidang" value="{{$bidang->bidang_name}}">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                        <input type="submit" class="btn btn-success" style="width: 15.0%" value="Simpan Perubahan">
                        <a href="{{ url( "/super_admin/view/bidang" ) }}" class="btn btn-danger" style="width: 15.0%">Batalkan</a>
                    </div>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>  
@endsection
