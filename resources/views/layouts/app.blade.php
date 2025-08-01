<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>App</title>
    @include('layouts.cdn')
</head>

<body class="h-screen overflow-hidden">
    {{-- navbar  --}}
    @include('layouts.navbar')
    <div class="flex flex-col h-screen overflow-hidden md:flex-row">
        {{-- sidebar --}}
        @include('layouts.sidebar')

        @yield('content')

    </div>
    @yield('script')
</body>


</html>
