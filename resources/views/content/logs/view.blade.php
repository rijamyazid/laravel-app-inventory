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
            <div class="col-md-6">
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
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered table-sm">
                <thead>
                    
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
                        
                        
                        <td style="width=20%">{{ $log->log_type }}</td>
                        <td style="width=30%">{!! nl2br(e($log->keterangan)) !!}</td>
                        @if (!is_null($log->user_id))
                            <td style="width=20%">{{ $log->user->user_name }}</td>
                        @else
                            <td style="width=20%"></td>
                        @endif
                        <td style="width=20%">{{ Helper::convertTime($log->created_at) }}</td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table> 
            {!! $logs->links() !!}
        </div>
    </div>
</div>  
@endsection
