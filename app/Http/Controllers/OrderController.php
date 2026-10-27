<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['customer', 'user'])
            ->withSum('payments', 'amount')
            ->latest('order_date')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();

        $products = Product::where('is_available', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();

        return view('orders.create', compact('customers', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['nullable', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $selectedItems = collect($data['items'])
            ->filter(fn ($item) => ! empty($item['product_id']));

        if ($selectedItems->isEmpty()) {
            throw ValidationException::withMessages([
                'items' => 'Select at least one product.',
            ]);
        }

        foreach ($selectedItems as $item) {
            if (empty($item['quantity'])) {
                throw ValidationException::withMessages([
                    'items' => 'Enter a quantity of at least 1 for each selected product.',
                ]);
            }
        }

        $order = DB::transaction(function () use ($data, $selectedItems) {
            $order = Order::create([
                'order_number' => 'TEMP-' . Str::uuid(),
                'customer_id' => $data['customer_id'],
                'user_id' => auth()->id(),
                'order_date' => now(),
                'status' => 'pending',
                'total_amount' => 0,
            ]);

            $totalCents = 0;

            foreach ($selectedItems as $item) {
                $product = Product::whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $product->is_available || $product->stock_quantity < 1) {
                    throw ValidationException::withMessages([
                        'items' => "{$product->name} is not currently available.",
                    ]);
                }

                $quantity = (int) $item['quantity'];

                if ($quantity > $product->stock_quantity) {
                    throw ValidationException::withMessages([
                        'items' => "There is not enough stock for {$product->name}.",
                    ]);
                }

                $unitPriceCents = (int) round((float) $product->price * 100);
                $subtotalCents = $quantity * $unitPriceCents;
                $totalCents += $subtotalCents;

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => number_format($unitPriceCents / 100, 2, '.', ''),
                    'subtotal' => number_format($subtotalCents / 100, 2, '.', ''),
                ]);
            }

            $order->update([
                'order_number' => 'CAFE-'
                    . now()->format('Y')
                    . '-'
                    . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT),
                'total_amount' => number_format($totalCents / 100, 2, '.', ''),
            ]);

            return $order;
        });

        return redirect()
            ->route('orders.index')
            ->with('success', "Order {$order->order_number} was created.");
    }
}