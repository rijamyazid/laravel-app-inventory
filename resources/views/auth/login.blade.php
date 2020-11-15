@extends('layouts/main')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-center" 
  style="min-height: 100vh; background-image: linear-gradient(to bottom right, #48b1bf, #ffffff);">
  <div class="row">
    <div class="col-sm col-md col-lg col-xl text-center"></div>
    <div class="col-sm-8 col-md-6 col-lg-5 col-xl-4 text-center">
      <div class="card shadow p-3 mb-5 bg-white rounded mt-4 text-center">
        <img src="{{ asset('logo.png') }}" class="img-fluid w-50 mx-auto">
        <div class="card-body">
          <h4 class="card-title">Sistem Manajemen Arsip BKKBN Jawa Barat</h4>
          <div class="card-text">
            {{-- Menampilkan error validasi --}}
              @if ($errors->any())
                      <ul class="list-group list-group-flush">
                          @foreach ($errors->all() as $error)
                              <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                          @endforeach
                      </ul>
              @endif
          </div>
        </div>
        <div class="card-body">
          <form action="/login" method="POST">
            @csrf
            <div class="form-group">
                <input class="form-control" type="text" name="username" placeholder="Username" maxlength="25">
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Password" maxlength="25">
            </div>
            <div class="form-group">
                <input class="btn btn-primary btn-block" type="submit" value="Login">
            </div>
          </form>
          <div class="row">
              <div class="col-md"><hr></div>
              <div class="col-md-3">Atau</div>
              <div class="col-md"><hr></div>
          </div>
          <div class="form-group">
            <a href="/guest_login" class="btn btn-success btn-block">Login Sebagai Guest</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm col-md col-lg col-xl text-center"></div>
  </div>
</div>
@endsection