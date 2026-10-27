<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Order $order): View
    {
        $payments = $order->payments()
            ->orderByDesc('paid_at')
            ->get();

        $amountPaid = (float) $payments->sum('amount');
        $remainingBalance = max(0, (float) $order->total_amount - $amountPaid);

        return view('payments.index', compact(
            'order',
            'payments',
            'amountPaid',
            'remainingBalance'
        ));
    }

    public function create(Order $order): View
    {
        $amountPaid = (float) $order->payments()->sum('amount');
        $remainingBalance = max(0, (float) $order->total_amount - $amountPaid);

        return view('payments.create', compact(
            'order',
            'amountPaid',
            'remainingBalance'
        ));
    }

    public function store(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'payment_reference' => [
                'required',
                'string',
                'max:100',
                'unique:payments,payment_reference',
            ],
            'payment_method' => [
                'required',
                'string',
                'in:cash,card,gcash,bank_transfer',
            ],
            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],
            'paid_at' => [
                'required',
                'date',
            ],
        ]);

        DB::transaction(function () use ($order, $data) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            $alreadyPaidCents = (int) round(
                (float) $lockedOrder->payments()->sum('amount') * 100
            );

            $newPaymentCents = (int) round(
                (float) $data['amount'] * 100
            );

            $orderTotalCents = (int) round(
                (float) $lockedOrder->total_amount * 100
            );

            if ($alreadyPaidCents + $newPaymentCents > $orderTotalCents) {
                $remainingCents = max(
                    0,
                    $orderTotalCents - $alreadyPaidCents
                );

                throw ValidationException::withMessages([
                    'amount' => 'Payment exceeds the remaining balance of '
                        . number_format($remainingCents / 100, 2)
                        . '.',
                ]);
            }

            Payment::create([
                'order_id' => $lockedOrder->id,
                'payment_reference' => $data['payment_reference'],
                'payment_method' => $data['payment_method'],
                'amount' => number_format(
                    $newPaymentCents / 100,
                    2,
                    '.',
                    ''
                ),
                'paid_at' => $data['paid_at'],
            ]);

            if ($alreadyPaidCents + $newPaymentCents >= $orderTotalCents) {
                $lockedOrder->update([
                    'status' => 'paid',
                ]);
            }
        });

        return redirect()
            ->route('payments.index', $order)
            ->with('success', 'Payment recorded successfully.');
    }
}