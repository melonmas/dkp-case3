<!DOCTYPE html>
<html lang="id" class="dark h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Task Management' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @fluxAppearance
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen font-sans antialiased selection:bg-zinc-800 selection:text-zinc-100 flex flex-col justify-between pt-12 sm:pt-16 pb-8 px-4 sm:px-6">

    <div class="w-full max-w-sm sm:max-w-md mx-auto">
        <!-- Logo & Brand Header -->
        <div class="flex flex-col items-center justify-center text-center mb-6">
            <div class="w-10 h-10 rounded-lg bg-zinc-900 border border-zinc-800 flex items-center justify-center text-zinc-100 shadow-sm mb-3">
                <svg class="w-5 h-5 text-zinc-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-base font-semibold tracking-tight text-zinc-100">Task Management</span>
                <span class="text-[11px] font-mono px-1.5 py-0.5 rounded border border-zinc-800 bg-zinc-900 text-zinc-400">DKP '26</span>
            </div>
        </div>

        {{ $slot }}
    </div>

    <!-- Footer note -->
    <footer class="w-full text-center text-xs text-zinc-600 mt-12">
        Praktikum Dasar Komputer dan Pemrograman &bull; Case 3
    </footer>

    @livewireScripts
    @fluxScripts
</body>
</html>
