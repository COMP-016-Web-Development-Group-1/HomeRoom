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
        $today = now()->startOfDay();

        Tenant::all()->each(function (Tenant $tenant) use ($today) {
            $billingDay = Carbon::parse($tenant->move_in_date)->day;
            $daysInMonth = $today->daysInMonth;
            $expectedBillingDay = min($billingDay, $daysInMonth);

            if ($today->day !== $expectedBillingDay) {
                return;
            }

            // Check if bill already exists for this user/month
            $exists = Bill::where('tenant_id', $tenant->id)
                ->whereMonth('created_at', $today->month)
                ->whereYear('created_at', $today->year)
                ->exists();

            if (!$exists) {
                $dueDate = $today->copy()->addMonth();

                // Ensure due date uses the same billing day logic
                $dueDateBillingDay = min($billingDay, $dueDate->daysInMonth);
                $dueDate->day = $dueDateBillingDay;
                $dueDate = $dueDate->endOfDay();

                $bill = Bill::create([
                    'tenant_id' => $tenant->id,
                    'amount_due' => $tenant->room->rent_amount,
                    'due_date' => $dueDate,
                    'status' => 'unpaid',
                ]);

                $tenant->user->notify(new BillCreated($bill));
            }
        });
    }
}
