<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usagelog;

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
            return;
        }

        $handle = fopen($path, 'r');
        $headers = fgetcsv($handle);
        $headers = array_map(function ($header) {
            return strtolower(trim(str_replace(' ', '_', $header)));
        }, $headers);
        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($headers, $row);
            Usagelog::create([
                'workspace_id' => $data['workspace_id'],
                'apitoken_id' => $data['apitoken_id'],
                'service' => $data['service'],
                'duration' => $data['duration'],
                'cost_per_second' => $data['cost_per_second'],
            ]);
            $count++;
        }
        fclose($handle);
        $this->info("Complete to add $count row usage logs to database!");
    }
}
