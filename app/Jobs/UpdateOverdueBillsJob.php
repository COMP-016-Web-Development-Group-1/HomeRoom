<?php

namespace App\Jobs;

use App\Models\Bill;
use App\Notifications\BillOverdue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateOverdueBillsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $todayStr = now()->toString();

        Bill::where('status', 'unpaid')
            ->where('due_date', '<', $todayStr)
            ->get()
            ->each(function (Bill $bill) {
                $bill->update(['status' => 'overdue']);

                if ($bill->tenant && $bill->tenant->user) {
                    $bill->tenant->user->notify(new BillOverdue($bill));
                }
            });

    }
}
