<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    @if(app()->environment('local'))
        @routes
    @endif
    @vite('resources/app/main.ts')
    @inertiaHead

    <script>
        try {
            if (localStorage.getItem('sidebar-expanded') === 'true') {
                document.querySelector('body').classList.add('sidebar-expanded');
            } else {
                document.querySelector('body').classList.remove('sidebar-expanded');
            }
        } catch (e) {
        }
    </script>
</head>
<body class="font-inter antialiased bg-slate-100 text-slate-600">
@inertia
</body>
</html>
