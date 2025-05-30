<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDatabaseConnection extends Command
{
    protected $signature = 'db:check';
    protected $description = 'Check database connection';

    public function handle()
    {
        try {
            DB::connection()->getPdo();
            $this->info('Database connection successful!');
            $this->info('Database name: ' . DB::connection()->getDatabaseName());
        } catch (\Exception $e) {
            $this->error('Database connection failed!');
            $this->error('Error: ' . $e->getMessage());
        }
    }
} 