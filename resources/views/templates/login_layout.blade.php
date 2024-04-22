<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">


</head>
<body>

<div>
    @yield('content') <!-- Mesmo havendo um yield aqui que não estará presente na view filha, não haverá erro. Simplesmnente, no carregamento, este yield será ignorado -->
</div>

<script src="{{ asset('assets/bootstrap/bootstrap.bundle.min.js') }}"></script>

</body>
</html>