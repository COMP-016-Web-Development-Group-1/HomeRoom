<?php

namespace App\Jobs;

use App\Models\Bill;
use App\Models\Tenant;
use App\Notifications\BillCreated;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateBillsJob implements ShouldQueue
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
        $today = now();

        Tenant::all()->each(function (Tenant $tenant) use ($today) {
            $billingDay = Carbon::parse($tenant->move_in_date)->day;
            $daysInMonth = $today->daysInMonth;
            $expectedBillingDay = min($billingDay, $daysInMonth);

            if ($today->day !== $expectedBillingDay) {
                return;
            }

            // Check if bill already exists for this user/month
            $exists = Bill::where('tenant_id', $tenant->id)
                ->whereMonth('due_date', $today->month)
                ->whereYear('due_date', $today->year)
                ->exists();

            if (! $exists) {
                $bill = Bill::create([
                    'tenant_id' => $tenant->id,
                    'amount_due' => $tenant->room->rent_amount, // or dynamic
                    'due_date' => $today->copy()->addMonth(), // due 1 month (if that day does not exist, it will fall back to the last valid day of the month)
                    'status' => 'unpaid',
                ]);

                $tenant->user->notify(new BillCreated($bill));
            }
        });
    }
}
