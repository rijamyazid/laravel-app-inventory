@extends('layouts.main')

@section('content')
    <div class="card-view">
        <div class="card-body">
            <h2>Login</h2>
            <form action="/login" method="POST">
                @csrf
                <div class="form-group">
                    <input class="form-control" type="text" name="username" placeholder="Username">
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="Login">
                </div>
            </form>
        </div>
    </div>
@endsection