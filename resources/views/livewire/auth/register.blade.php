<div class="bg-zinc-900/60 border border-zinc-800 rounded-lg p-6 sm:p-7 shadow-sm">
    <div class="mb-5">
        <h1 class="text-lg font-semibold text-zinc-100 tracking-tight">Buat Akun Baru</h1>
        <p class="text-xs text-zinc-400 mt-1">Daftar untuk mengelola dan memonitor task Anda</p>
    </div>

    <form wire:submit="register" class="space-y-4">
        <flux:input
            wire:model="name"
            label="Nama Lengkap"
            placeholder="John Doe"
            required
            autocomplete="name"
        />

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
            label="Password (Min. 8 karakter)"
            type="password"
            placeholder="••••••••"
            required
            autocomplete="new-password"
            viewable
        />

        <flux:input
            wire:model="password_confirmation"
            label="Konfirmasi Password"
            type="password"
            placeholder="••••••••"
            required
            autocomplete="new-password"
            viewable
        />

        <flux:button type="submit" variant="primary" class="w-full">
            <span wire:loading.remove wire:target="register">Daftar Akun</span>
            <span wire:loading wire:target="register" class="flex items-center gap-2">
                Mendaftarkan...
            </span>
        </flux:button>
    </form>

    <div class="mt-6 pt-5 border-t border-zinc-800/80 text-center text-xs text-zinc-400">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" wire:navigate class="text-zinc-200 hover:text-white font-medium underline underline-offset-4 ml-1">
            Masuk sekarang
        </a>
    </div>
</div>
