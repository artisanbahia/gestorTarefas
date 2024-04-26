<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Condicional para usar o Datatable apenas onde for preciso -->
    @if(!empty($datatables))
        <link rel="stylesheet" href="{{ asset('assets/datatables/datatables.min.css') }}">
        <script src="{{ asset('assets/datatables/jquery/jquery.min.js') }}"></script>
    @endif

</head>
<body>

    @include('nav')

    @yield('main') <!-- Para carregamento do conteúdo das views dentro do contexto deste layout -->
    @yield('content') <!-- Para carregamento do conteúdo das views dentro do contexto deste layout -->

        <!--

            ATENÇÃO:

            É possível usar apenas o yield content, basta que também em main o section tenha o nome content. Neste caso, estou mantendo os dois, mas oportunamente posso ajustar

        -->

    @include('footer')

    <script src="{{ asset('assets/bootstrap/bootstrap.bundle.min.js') }}"></script>


    <!-- Condicional para usar o Datatable apenas onde for preciso -->
    @if(!empty($datatables))
        <script src="{{ asset('assets/datatables/datatables.min.js') }}"></script>
    @endif


</body>
</html>
