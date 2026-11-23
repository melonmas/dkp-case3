<div class="bg-zinc-900/60 border border-zinc-800 rounded-lg p-6 sm:p-7 shadow-sm">
    <div class="mb-5">
        <h1 class="text-lg font-semibold text-zinc-100 tracking-tight">Masuk ke Akun</h1>
        <p class="text-xs text-zinc-400 mt-1">Masukkan email dan password untuk mengakses task Anda</p>
    </div>

    <form wire:submit="login" class="space-y-4">
        <flux:input
            wire:model="email"
            label="Email"
            type="email"
            placeholder="nama@domain.com"
            required
            autocomplete="email"
        />

        <flux:input
            wire:model="password"
            label="Password"
            type="password"
            placeholder="••••••••"
            required
            autocomplete="current-password"
            viewable
        />

        <div class="pt-0.5">
            <flux:checkbox
                wire:model="remember"
                label="Ingat sesi saya"
            />
        </div>

        <flux:button type="submit" variant="primary" class="w-full">
            <span wire:loading.remove wire:target="login">Masuk</span>
            <span wire:loading wire:target="login" class="flex items-center gap-2">
                Memverifikasi...
            </span>
        </flux:button>
    </form>

    <div class="mt-6 pt-5 border-t border-zinc-800/80 text-center text-xs text-zinc-400">
        Belum memiliki akun?
        <a href="{{ route('register') }}" wire:navigate class="text-zinc-200 hover:text-white font-medium underline underline-offset-4 ml-1">
            Daftar baru
        </a>
    </div>
</div>
