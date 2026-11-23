<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default seeder has no data - use migrations manually or create seeds
        $this->command->info('Database is ready. No seeders configured.');
    }
}