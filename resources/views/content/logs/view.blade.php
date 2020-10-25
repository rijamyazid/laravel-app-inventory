@extends('dashboard.dashboard')

@section('sub-content')
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <h2>Riwayat Aksi</h2>
        </div>
    </div>
    <div class="row mb-3">
        @if ( Session::get('rolePrefix') == 'super_admin')
            <div class="col-md">
                <select class="form-control" name="bin_bidang" id="bin_bidang" onchange="logsBidangChanges(this.value)">
                    @foreach ($bidangS as $bidang)
                        @if ($bidang->bidang_prefix != 'super_admin')
                            <option value="{{ $bidang->bidang_prefix }}" @if ($bidangPrefix == $bidang->bidang_prefix)
                                selected
                            @endif>{{ $bidang->bidang_name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        @endif
        {{-- FORM PENCARIAN --}}
        <div class="col-md">
            <form class="form-inline float-right" action="{{ url('/' . $bidangPrefix . '/logs/search') }}" method="GET">
                <div class="form-group mr-3">
                    <input class="form-control" type="text" placeholder="Cari File" name="bidang" hidden value="{{ $bidangPrefix }}">
                </div>
                <div class="form-group mr-3">
                    <input class="form-control" type="text" placeholder="Cari logs" name="q">
                </div>
                <div class="form-group">
                    {{-- <input class="btn btn-success" type="submit" value="Cari"> --}}
                    <button class="btn btn-success" type="submit" value="Cari">
                        <span data-feather="search"></span>
                    </button>
                </div>
            </form>
        </div>
        {{-- FORM PENCARIAN --}}
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered table-sm">
                <thead>
                    <th style="width=10%">No</th>
                    <th style="width=20%">Jenis Aksi</th>
                    <th style="width=30%">Keterangan</th>
                    <th style="width=20%">User</th>
                    <th style="width=20%">Waktu</th>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @foreach ($logs as $log)
                    <tr>
                        <td style="width=10%" >{{ $count }}</td>
                        <td style="width=20%">{{ $log->log_type }}</td>
                        <td style="width=30%">{!! nl2br(e($log->keterangan)) !!}</td>
                        <td style="width=20%">{{ $log->user->user_name }}</td>
                        <td style="width=20%">{{ $log->created_at }}</td>
                        @php
                            $count++;
                        @endphp
                    </tr>
                    @endforeach
                </tbody>
            </table>  
        </div>
    </div>
</div>  
@endsection
