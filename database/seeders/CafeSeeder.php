<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CafeSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // One staff account. Use these credentials to log in locally.
            $staff = User::create([
                'name' => 'Cafe Staff',
                'email' => 'staff@example.com',
                'password' => Hash::make('password'),
            ]);

            // Three categories
            $coffee = Category::create([
                'name' => 'Coffee',
                'description' => 'Espresso and brewed coffee drinks.',
            ]);

            $tea = Category::create([
                'name' => 'Tea',
                'description' => 'Hot and iced tea drinks.',
            ]);

            $pastries = Category::create([
                'name' => 'Pastries',
                'description' => 'Freshly baked café treats.',
            ]);

            // Six products
            $latte = Product::create([
                'category_id' => $coffee->id,
                'name' => 'Cafe Latte',
                'description' => 'Espresso with steamed milk.',
                'price' => 4.50,
                'stock_quantity' => 30,
                'is_available' => true,
            ]);

            $americano = Product::create([
                'category_id' => $coffee->id,
                'name' => 'Americano',
                'description' => 'Espresso with hot water.',
                'price' => 3.25,
                'stock_quantity' => 30,
                'is_available' => true,
            ]);

            $cappuccino = Product::create([
                'category_id' => $coffee->id,
                'name' => 'Cappuccino',
                'description' => 'Espresso with steamed milk and foam.',
                'price' => 4.25,
                'stock_quantity' => 25,
                'is_available' => true,
            ]);

            $greenTea = Product::create([
                'category_id' => $tea->id,
                'name' => 'Green Tea',
                'description' => 'A cup of green tea.',
                'price' => 2.75,
                'stock_quantity' => 20,
                'is_available' => true,
            ]);

            $croissant = Product::create([
                'category_id' => $pastries->id,
                'name' => 'Butter Croissant',
                'description' => 'A flaky butter croissant.',
                'price' => 3.00,
                'stock_quantity' => 15,
                'is_available' => true,
            ]);

            $muffin = Product::create([
                'category_id' => $pastries->id,
                'name' => 'Blueberry Muffin',
                'description' => 'A muffin with blueberries.',
                'price' => 3.50,
                'stock_quantity' => 12,
                'is_available' => true,
            ]);

            // Three customers
            $customers = [
                Customer::create([
                    'name' => 'Alex Santos',
                    'email' => 'alex@example.com',
                    'phone' => '09171234567',
                    'address' => 'Manila',
                ]),
                Customer::create([
                    'name' => 'Jamie Cruz',
                    'email' => 'jamie@example.com',
                    'phone' => '09181234567',
                    'address' => 'Quezon City',
                ]),
                Customer::create([
                    'name' => 'Taylor Reyes',
                    'email' => 'taylor@example.com',
                    'phone' => '09191234567',
                    'address' => 'Makati City',
                ]),
            ];

            // Create each order and its items together.
            $order1 = $this->createOrder(
                customer: $customers[0],
                staff: $staff,
                orderNumber: 'CAFE-2026-0001',
                status: 'paid',
                products: [
                    [$latte, 2],
                    [$croissant, 1],
                ],
            );

            $order2 = $this->createOrder(
                customer: $customers[1],
                staff: $staff,
                orderNumber: 'CAFE-2026-0002',
                status: 'paid',
                products: [
                    [$americano, 1],
                    [$muffin, 2],
                ],
            );

            $this->createOrder(
                customer: $customers[2],
                staff: $staff,
                orderNumber: 'CAFE-2026-0003',
                status: 'pending',
                products: [
                    [$cappuccino, 1],
                    [$greenTea, 1],
                ],
            );

            // Payments for two orders.
            // Amounts are equal to each order total for this sample data.
            Payment::create([
                'order_id' => $order1->id,
                'payment_reference' => 'PAY-CAFE-0001',
                'payment_method' => 'cash',
                'amount' => $order1->total_amount,
                'paid_at' => now()->subDays(2),
            ]);

            Payment::create([
                'order_id' => $order2->id,
                'payment_reference' => 'PAY-CAFE-0002',
                'payment_method' => 'card',
                'amount' => $order2->total_amount,
                'paid_at' => now()->subDay(),
            ]);
        });
    }

    /**
     * Create an order and its item rows, calculating all prices from products.
     *
     * @param array<int, array{0: Product, 1: int}> $products
     */
    private function createOrder(
        Customer $customer,
        User $staff,
        string $orderNumber,
        string $status,
        array $products,
    ): Order {
        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_id' => $customer->id,
            'user_id' => $staff->id,
            'order_date' => now(),
            'status' => $status,
            'total_amount' => 0,
        ]);

        $total = 0;

        foreach ($products as [$product, $quantity]) {
            $unitPrice = (float) $product->price;
            $subtotal = $quantity * $unitPrice;

            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ]);

            $total += $subtotal;
        }

        $order->update([
            'total_amount' => $total,
        ]);

        return $order;
    }
}