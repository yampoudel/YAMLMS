<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://bunny.net">
    <link href="https://bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Application Compiled Assets Entrypoints -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Required Inertia head components tracking tag -->
    @inertiaHead
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <!-- Inertia mounts Vue layouts, sidebars, and user tables directly here -->
    @inertia
</body>
</html>
