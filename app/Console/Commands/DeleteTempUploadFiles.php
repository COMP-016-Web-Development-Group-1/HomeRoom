<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Storage;

class DeleteTempUploadFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-temp-uploaded-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete temporary uploaded files older than 24 hours.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $disks = ['local', 'public'];

        foreach ($disks as $disk) {
            $allDirectories = Storage::disk($disk)->allDirectories();

            $this->info("Scanning all directories on '{$disk}' disk...");

            foreach ($allDirectories as $directory) {
                // Debug: Show the directory being checked
                $this->line("⏳ Checking directory: {$directory}");

                if (str_contains($directory, 'tmp')) {
                    try {
                        $lastModified = Carbon::createFromTimestamp(
                            Storage::disk($disk)->lastModified($directory)
                        );

                        $hoursOld = now()->diffInHours($lastModified);

                        // if ($hoursOld > 24) {
                        Storage::disk($disk)->deleteDirectory($directory);
                        $this->info("✅ Deleted [{$directory}] - Last modified {$hoursOld} hours ago.");
                        // } else {
                        //     $this->line("🟡 Skipped [{$directory}] - Only {$hoursOld} hours old.");
                        // }
                    } catch (\Exception $e) {
                        $this->error("❌ Failed to check/delete '{$directory}': " . $e->getMessage());
                    }
                } else {
                    $this->line("🔹 Ignored non-temp directory: {$directory}");
                }
            }

        }
    }
}
