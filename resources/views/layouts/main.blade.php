<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script type="text/javascript" src="{{ URL::asset('js/feather.js') }}"></script>
    
    {{-- Icons --}}
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('iconBkkbn.ico') }}" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .side-link {
            color: #FFFFFF;
        }

        .side-link-active {
            color: #FFFFFF;
        }

        .side-link:hover {
            color: #BEBEBE;
        }
    </style>

    <title>Managemen Arsip BKKBN</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</head>
<body>
    @include('sweetalert::alert')
    @yield('content')

    <script>
        feather.replace()
    </script>
    <script>
        $('.delete-confirm').on('click', function (event) {
            event.preventDefault();
            const url = $(this).attr('href');
            swal({
                title: 'Yakin Hapus?',
                text: 'Item ini akan dihapus secara permanen!',
                icon: 'warning',
                buttons: ["Batal", "Ya!"],
            }).then(function(value) {
                if (value) {
                    window.location.href = url;
                }
            });
        });
    </script>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
</body>
</html>