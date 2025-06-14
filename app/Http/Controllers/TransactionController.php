<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Bill;
use Illuminate\Http\Request;
use App\Enums\PaymentMethod;
use App\Enums\BillStatus;
use App\Enums\TransactionStatus;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = auth()->user()->role;

        return match ($role) {
            'landlord' => (function () {
                    $pendingTransactions = Transaction::with(['tenant.room.property', 'bill'])
                    ->where('status', TransactionStatus::PENDING->value)
                    ->orderByDesc('payment_date')
                    ->get();

                    $historyTransactions = Transaction::with(['tenant.room.property', 'bill'])
                    ->where('status', '!=', TransactionStatus::PENDING->value)
                    ->orderByDesc('payment_date')
                    ->get();

                    return view('landlord.transaction.index', compact('pendingTransactions', 'historyTransactions'));
                })(),

            'tenant' => (function () {
                    $user = auth()->user();
                    $tenantId = $user->tenant->id ?? null;

                    $data = $this->getTenantBillDetails($tenantId);

                    return view('tenant.transaction.index', $data);
                })(),

            default => abort(403),
        };
    }

    /**
     * Show the pay bills page with available payment methods and outstanding info.
     */
    public function showPayBills(Request $request)
    {
        $user = auth()->user();
        $tenantId = $user->tenant->id ?? null;
        $type = $request->query('type', 'monthly'); // default to monthly

        $data = $this->getTenantBillDetails($tenantId);

        // Choose which data to display based on $type
        if ($type === 'outstanding') {
            $displayAmount = $data['totalOutstandingBill'];
            $displayDueDate = $data['nextOutstandingDueDate'];
        } else {
            $displayAmount = $data['monthlyBill'] ? $data['monthlyBill']->amount_due : 0;
            $displayDueDate = $data['monthlyBill'] && $data['monthlyBill']->due_date
                ? $data['monthlyBill']->due_date
                : null;
        }

        $data['displayAmount'] = $displayAmount;
        $data['displayDueDate'] = $displayDueDate;
        $data['displayType'] = $type;

        // --- Get landlord's QR codes (GCash, Maya, etc.) ---
        $landlord = $user->tenant
            ? ($user->tenant->room->property->landlord ?? null)
            : null;

        $gcashQrUrl = $landlord && $landlord->gcash_qr
            ? asset('storage/' . ltrim($landlord->gcash_qr, '/'))
            : null;
        $data['gcashQrUrl'] = $gcashQrUrl;

        $mayaQrUrl = $landlord && $landlord->maya_qr
            ? asset('storage/' . ltrim($landlord->maya_qr, '/'))
            : null;
        $data['mayaQrUrl'] = $mayaQrUrl;

        return view('tenant.transaction.pay-bills', $data);
    }

    /**
     * Helper: Get tenant bill/payment/transaction details for both dashboard and pay-bills.
     */
    private function getTenantBillDetails($tenantId)
    {
        $historyTransactions = Transaction::with(['tenant.room.property', 'bill'])
            ->where('tenant_id', $tenantId)
            ->orderByDesc('payment_date')
            ->get();

        $monthlyBill = Bill::where('tenant_id', $tenantId)
            ->where('status', BillStatus::UNPAID->value)
            ->orderByDesc('due_date')
            ->first();

        $totalOutstandingBill = Bill::where('tenant_id', $tenantId)
            ->where('status', BillStatus::OVERDUE->value)
            ->where('amount_due', '>', 0)
            ->sum('amount_due');

        $nextOutstandingDueDate = Bill::where('tenant_id', $tenantId)
            ->where('status', BillStatus::OVERDUE->value)
            ->where('amount_due', '>', 0)
            ->orderBy('due_date', 'asc')
            ->value('due_date');

        $paymentMethods = implode(', ', array_map(
            fn($method) => ucfirst(str_replace('_', ' ', $method->name)),
            PaymentMethod::cases()
        ));

        return compact(
            'historyTransactions',
            'monthlyBill',
            'totalOutstandingBill',
            'nextOutstandingDueDate',
            'paymentMethods'
        );
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $tenantId = $user->tenant->id ?? null;
        $paymentMethod = $request->input('payment_method');

        $rules = [
            'amount_sent' => ['required', 'numeric', 'min:1'],
            'bill_id' => ['required', 'exists:bills,id'],
            'payment_method' => ['required', 'in:gcash,maya,cash'],
        ];

        if ($paymentMethod === 'gcash') {
            $rules['reference_number'] = ['required', 'string', 'max:64'];
            $rules['gcash_number'] = ['required', 'string', 'max:20'];
            $rules['payment_photo'] = ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'];
        } elseif ($paymentMethod === 'maya') {
            $rules['reference_number'] = ['required', 'string', 'max:64'];
            $rules['maya_number'] = ['required', 'string', 'max:20'];
            $rules['payment_photo'] = ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'];
        } elseif ($paymentMethod === 'cash') {
            $rules['payment_photo'] = ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'];
        }

        $validated = $request->validate($rules);

        // Handle file upload if present
        $photoPath = null;
        if ($request->hasFile('payment_photo')) {
            $photoPath = $request->file('payment_photo')->store('proof_photos', 'public');
        }

        // Prepare transaction data
        $data = [
            'tenant_id' => $tenantId,
            'bill_id' => $validated['bill_id'],
            'amount' => $validated['amount_sent'],
            'status' => TransactionStatus::PENDING->value,
            'payment_method' => $paymentMethod,
            'proof_photo' => $photoPath,
            'payment_date' => now(),
            'confirmed_at' => null,
        ];

        // Payment method-specific fields
        if ($paymentMethod === 'gcash') {
            $data['reference_number'] = $validated['reference_number'];
            $data['gcash_number'] = $validated['gcash_number'];
        } elseif ($paymentMethod === 'maya') {
            $data['reference_number'] = $validated['reference_number'];
            $data['maya_number'] = $validated['maya_number'];
        }
        // For cash, no extra fields

        // Create transaction
        $transaction = Transaction::create($data);

        // Update Bill status to paid if amount matches or exceeds amount_due
        $bill = Bill::find($validated['bill_id']);
        if ($bill) {
            if (floatval($validated['amount_sent']) >= floatval($bill->amount_due)) {
                $bill->status = BillStatus::PAID->value;
                $bill->save();
            }
            // otherwise, do not change the status (leave as overdue or unpaid)
        }

        // Redirect with toast.success message
        return redirect()->route('transaction.index')->with('toast.success', [
            'title' => 'Transaction Submitted',
            'content' => 'Your transaction has been submitted and is pending confirmation.',
        ]);
    }

    public function show(string $id)
    {
    }
    public function edit(string $id)
    {
    }
    public function update(Request $request, $id)
    {
        $transaction = Transaction::with('bill')->findOrFail($id);

        if ($request->input('action') === 'acknowledge') {
            if (auth()->user()->role !== 'landlord' || $transaction->status !== TransactionStatus::PENDING->value) {
                abort(403);
            }

            $transaction->status = TransactionStatus::COMPLETED->value;
            $transaction->confirmed_at = now();
            $transaction->save();

            // Optionally, update bill status if needed
            if ($transaction->bill && $transaction->bill->status !== BillStatus::PAID->value) {
                $transaction->bill->status = BillStatus::PAID->value;
                $transaction->bill->save();
            }

            return redirect()->route('transaction.index')->with('toast.success', [
                'title' => 'Transaction Confirmed',
                'content' => 'You have acknowledged and completed the transaction.',
            ]);
        }

        // Other update logic here (if any)
        abort(400, 'Invalid update action.');
    }
    public function destroy(string $id)
    {
    }
}
