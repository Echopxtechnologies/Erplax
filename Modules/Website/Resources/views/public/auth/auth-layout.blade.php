<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $settings->site_name ?? 'Store')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if($settings->getFaviconUrl())
    <link rel="icon" href="{{ $settings->getFaviconUrl() }}">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;background:#f8fafc;min-height:100vh}
    </style>
    @yield('styles')
</head>
<body>
    @yield('content')
</body>
</html>
