@extends('layouts.main')

@section('content')
<nav class="navbar navbar-expand-md navbar-light" style="background-color: #e3f2fd; z-index: 110;">
    <div class="mx-auto order-0">
        <a class="navbar-brand mx-auto" href="/{role_prefix}">Sistem Manajemen Arsip BKKBN Jawa Barat</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="btn btn-outline-danger" href="/logout">
                    <span data-feather="log-out"></span>
                    Logout
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar dual-collapse2 collapse" style="background-color: red;">
        <div class="sidebar-sticky pt-3" style="background-color: #e3f2fd;">
            <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="/{role_prefix}">
                <span data-feather="home"></span>
                Dashboard
                </a>
            </li>
            @foreach ($roles as $role)        
                @if ($sessions['role'] != 'super_admin')
                    @if ($role->role != 'Super Admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/'. $role->role_prefix) }}">
                            <span data-feather="folder"></span>
                                {{ $role->role }}
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/'. $role->role_prefix) }}">
                        <span data-feather="folder"></span>
                            {{ $role->role }}
                        </a>
                    </li>
                @endif
            @endforeach
            @if ($sessions['role'] == 'super_admin')
                <li class="nav-item" >
                    <a class="nav-link" href="#" id="btn-tambah-bidang">
                        <span data-feather="folder-plus"></span>
                        Tambah Bidang
                    </a>
                </li>
                <li class="nav-item justify-content-center px-3" id="form-tambah-bidang" style="display: none">
                    <form action="{{ url($role . '/create/bidang-baru') }}" method="GET">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Nama Bidang" name="foldername">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success w-100" value="Tambah">  
                        </div>
                    </form>
                </li>
            @endif
            <script>
                $("#btn-tambah-bidang").click(function(){
                    $("#form-tambah-bidang").slideToggle();
                });
            </script>
            <li class="nav-item">
                <a class="nav-link" href="#">
                <span data-feather="users"></span>
                    Kelola User
                </a>
            </li>
            </ul>
        </div>
        </nav>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
        @yield('sub-content')
    </main>
    </div>
</div>

@endsection