@extends('layouts.main')

@section('content')
<nav class="navbar navbar-expand-md navbar-light sticky-top" style="background-color: #436EB3; z-index: 110;">
    <div class="mx-auto order-0">
        <a class="navbar-brand mx-auto text-white" href="/{role_prefix}">Sistem Manajemen Arsip BKKBN Jawa Barat</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="btn btn-danger" href="/logout">
                    <span data-feather="log-out"></span>
                    Logout
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <div class="row" style="min-height: 100vh">
        <div class="col-md-2" style="background-color: #436EB3;">
            <ul class="nav flex-column">
                <li class="nav-item">
                    @if (Session::get('side_loc') == 'dashboard')
                        <a class="nav-link side-link-active" style="background: #39C172" href="{{ url('/'.Session::get('rolePrefix').'/dashboard') }}">
                    @else
                        <a class="nav-link side-link" href="{{url('/'. Session::get('rolePrefix')) . '/dashboard'}}">
                    @endif
                        <span class="mr-2" data-feather="home"></span>
                            Dashboard
                    </a>
                </li>
                @foreach ($bidangS as $bidang)        
                    @if ($bidang->bidang_name != 'Super Admin')
                        <li class="nav-item">
                        @if (Session::get('side_loc')  == $bidang->bidang_prefix)
                            <a class="nav-link side-link-active" style="background: #39C172" href="{{ url('/'. $bidang->bidang_prefix . '/folder/') }}">
                        @else
                            <a class="nav-link side-link" href="{{ url('/'. $bidang->bidang_prefix . '/folder/') }}">
                        @endif
                            <span class="mr-2" data-feather="folder"></span>
                                {{ $bidang->bidang_name }}
                            </a>
                        </li>
                    @endif
                @endforeach
                @if (Session::get('rolePrefix') != 'guest')
                    <li class="nav-item">
                        @if (Session::get('side_loc')  == 'kelola_sampah_sementara')
                            <a class="nav-link side-link-active" style="background: #39C172" href="{{ url('/'. Session::get('rolePrefix') . '/bin/') }}">
                        @else
                            <a class="nav-link side-link" href="{{ url('/'. Session::get('rolePrefix') . '/bin/') }}">
                        @endif
                                <div class="row">
                                    <div class="col-2"><span class="mr-2" data-feather="trash-2"></span></div>
                                    <div class="col ml-1">Kelola Sampah Sementara</div>
                                </div>
                            </a>
                    </li>
                @endif
                @if (Session::get('rolePrefix') == 'super_admin')
                    <li class="nav-item" >
                        @if (Session::get('side_loc') != 'kelola_bidang')
                        <a class="nav-link side-link" href="{{ url("/super_admin/view/bidang") }}">
                        @else
                        <a class="nav-link side-link-active" style="background: #39C172" href="{{ url("/super_admin/view/bidang") }}">
                        @endif
                            <span class="mr-2" data-feather="folder-plus"></span>  
                                Kelola Bidang
                        </a>
                    </li>
                    <li class="nav-item">
                    @if (Session::get('side_loc') == 'kelola_user')
                        <a class="nav-link side-link-active" style="background: #39C172" href="{{ url('/'. Session::get('rolePrefix'). '/view/user') }}">
                    @else
                        <a class="nav-link side-link" href="{{ url('/'. Session::get('rolePrefix') . '/view/user') }}">
                    @endif
                            <span class="mr-2" data-feather="users"></span>
                            Kelola User
                        </a>
                    </li>
                @endif
                </ul>
        </div>
        <div class="col-md">
            @yield('sub-content')
        </div>
    </div>
</div>

@endsection