<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderAndReviewSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'user')->first();
        $admin = User::where('role', 'admin')->first();
        $products = Product::take(5)->get();

        if (!$user || $products->isEmpty()) {
            return;
        }

        // Create completed + delivered orders for the user on first 5 products
        foreach ($products as $product) {
            Order::create([
                'user_id'         => $user->id,
                'product_id'      => $product->id,
                'quantity'        => rand(1, 5),
                'total_price'     => $product->unit_price * rand(1, 5),
                'status'          => 'completed',
                'delivery_status' => 'delivered',
            ]);
        }

        // Create some reviews from the user
        $reviewData = [
            ['rating' => 5, 'comment' => 'Excellent product! Very high quality and arrived quickly. Will definitely purchase again.'],
            ['rating' => 4, 'comment' => 'Good value for money. The product works exactly as described. Highly recommended for the office.'],
            ['rating' => 3, 'comment' => 'Decent product overall. It does the job but I expected better packaging for this price range.'],
            ['rating' => 5, 'comment' => 'Outstanding! This is the best office supply I have ever purchased. Five stars well deserved!'],
            ['rating' => 4, 'comment' => 'Solid product with fast delivery. Minor issues with labeling but overall very satisfied.'],
        ];

        foreach ($products as $index => $product) {
            if (isset($reviewData[$index])) {
                Review::create([
                    'user_id'    => $user->id,
                    'product_id' => $product->id,
                    'rating'     => $reviewData[$index]['rating'],
                    'comment'    => $reviewData[$index]['comment'],
                ]);
            }
        }

        // Give admin a completed order and review on the first product too
        if ($admin && $products->first()) {
            Order::create([
                'user_id'         => $admin->id,
                'product_id'      => $products->first()->id,
                'quantity'        => 2,
                'total_price'     => $products->first()->unit_price * 2,
                'status'          => 'completed',
                'delivery_status' => 'delivered',
            ]);

            Review::create([
                'user_id'    => $admin->id,
                'product_id' => $products->first()->id,
                'rating'     => 5,
                'comment'    => 'Top-notch quality! As an administrator, I can confirm this product meets all our standards.',
            ]);
        }
    }
}
