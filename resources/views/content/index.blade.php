@extends('dashboard.dashboard')

@section('sub-content')
    <h1 class="mt-3">Anda login sebagai @if ($roleS == 'Super Admin')
            Admin
        @else
            {{ $roleS }}
        @endif</h1>
@endsection