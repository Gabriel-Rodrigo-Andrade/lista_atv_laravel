<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Escola')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        h1 { font-size: 1.8rem; margin: 24px 0; } p { margin: 12px 0; } input, select { border: 1px solid #94a3b8; } button { background: #155e75; color: white; border-radius: 4px; }
        body { font-family: sans-serif; max-width: 960px; margin: 32px auto; padding: 0 20px; color: #172c3d; }
        nav { display: flex; gap: 18px; flex-wrap: wrap; padding: 18px 0; border-bottom: 1px solid #ccc; }
        a { color: #155e75; } table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
        label { display: block; margin-top: 16px; } input, select, button { padding: 9px; font: inherit; }
        button { cursor: pointer; } .erro { color: #b91c1c; } .sucesso { color: #166534; }
    </style>
</head>
<body>
    @include('partials.menu')
    <main>
        @if (session('sucesso'))<p class="sucesso">{{ session('sucesso') }}</p>@endif
        @isset($header)<header>{{ $header }}</header>@endisset
        @yield('content')
        {{ $slot ?? '' }}
    </main>
</body>
</html>
