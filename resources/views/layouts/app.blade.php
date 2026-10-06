<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portfolio')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen flex-col bg-zinc-50 font-sans text-zinc-900 antialiased">

    @include('partials.navbar')

    <main class="flex-1">
        <div class="mx-auto w-full max-w-3xl px-6 py-10">
            @include('partials.flash')
            @yield('content')
        </div>
    </main>

    @include('partials.footer')

</body>

</html>