<!DOCTYPE html>
<html lang="id" class="dark h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - Task Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @fluxAppearance
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen font-sans antialiased selection:bg-zinc-800 selection:text-zinc-100">

    <!-- Global Toast Container (Alpine.js) -->
    <div x-data="{
            toasts: [],
            add(msg, type = 'success') {
                const id = Date.now();
                this.toasts.push({ id, msg, type });
                setTimeout(() => this.remove(id), 3500);
            },
            remove(id) {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }
        }"
        x-init="
            @if (session()->has('toast'))
                add('{{ addslashes(session('toast')) }}');
            @endif
        "
        @toast.window="add($event.detail.message, $event.detail.type || 'success')"
        class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none"
    >
        <template x-for="t in toasts" :key="t.id">
            <div x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                 class="pointer-events-auto flex items-center justify-between px-3.5 py-2.5 rounded-lg bg-zinc-900 border border-zinc-800 shadow-xl text-xs"
            >
                <div class="flex items-center gap-2.5">
                    <span class="w-1.5 h-1.5 rounded-full"
                          :class="t.type === 'error' ? 'bg-rose-500' : 'bg-emerald-400'"></span>
                    <span x-text="t.msg" class="text-zinc-200 font-medium"></span>
                </div>
                <button @click="remove(t.id)" class="text-zinc-500 hover:text-zinc-300 ml-3">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <!-- Navigation Bar -->
    <header class="border-b border-zinc-800/80 bg-zinc-950/80 backdrop-blur sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                    <div class="w-7 h-7 rounded-md bg-zinc-900 border border-zinc-700/80 flex items-center justify-center text-zinc-100 group-hover:border-zinc-500 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm text-zinc-100 tracking-tight">Tasks</span>
                </a>
                <span class="text-zinc-700">/</span>
                <span class="text-xs text-zinc-400 font-medium">Dashboard</span>
            </div>

            <!-- User Menu -->
            <div class="flex items-center gap-3">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            @click.outside="open = false"
                            class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg hover:bg-zinc-900 border border-transparent hover:border-zinc-800 transition cursor-pointer">
                        <div class="w-6 h-6 rounded-md bg-zinc-800 border border-zinc-700/80 text-zinc-200 font-medium text-[11px] flex items-center justify-center font-mono">
                            {{ Auth::user()->initials() ?? 'U' }}
                        </div>
                        <span class="text-xs font-medium text-zinc-300 hidden sm:inline">{{ Auth::user()->name }}</span>
                        <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-1.5 w-52 rounded-lg bg-zinc-900 border border-zinc-800 shadow-xl py-1 z-40 text-xs"
                         style="display: none;"
                    >
                        <div class="px-3 py-2 border-b border-zinc-800/80">
                            <p class="text-[11px] text-zinc-500">Masuk sebagai</p>
                            <p class="font-medium text-zinc-200 truncate mt-0.5">{{ Auth::user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-rose-400 hover:bg-zinc-800/80 flex items-center gap-2 transition cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        {{ $slot }}
    </main>

    @livewireScripts
    @fluxScripts
</body>
</html>
