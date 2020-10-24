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

</head>
<body>
    @yield('content')

    <script>
        feather.replace()
    </script>

    <script>
        $("#btn-tambah-folder").click(function(){
            if(!($("#form-tambah-file").is(":hidden"))){
                $("#form-tambah-file").slideToggle(function(){
                    $("#form-tambah-folder").slideToggle();
                });
            } else {
                $("#form-tambah-folder").slideToggle();
            }
        });

        $("#btn-tambah-file").click(function(){
            if(!($("#form-tambah-folder").is(":hidden"))){
                $("#form-tambah-folder").slideToggle(function(){
                    $("#form-tambah-file").slideToggle();
                });
            } else {
                $("#form-tambah-file").slideToggle();
            }
        });

        $("#folder_pilih").click(function(){
            $("#folder_akses_pilih").slideDown();
        });
        $("#folder_public").click(function(){
            $("#folder_akses_pilih").slideUp();
        });
        $("#folder_private").click(function(){
            $("#folder_akses_pilih").slideUp();
        });

        $("#file_pilih").click(function(){
            $("#file_akses_pilih").slideDown();
        });
        $("#file_public").click(function(){
            $("#file_akses_pilih").slideUp();
        });
        $("#file_private").click(function(){
            $("#file_akses_pilih").slideUp();
        });
        
        $("#btn-tambah-bidang").click(function(){
            $("#form-tambah-bidang").slideToggle();
        });

        function binBidangChanges(val){
            window.location.replace("/" + val + "/bin");
        }
    </script>
</body>
</html>