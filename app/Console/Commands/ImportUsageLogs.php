<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportUsageLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:usage-logs {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import usage logs dari file CSV';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->argument('file');

        if (!file_exists($path)) {
            $this->error("File tidak ditemukan: {$path}");
        }

        $handle = fopen($path, 'r');
        $headers = fgetcsv($handle);
        $count = 0;
    }
}
