<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed the products table with sample data.
     */
    public function run(): void
    {
        // Featured products matching the screenshot
        $featured = [
            [
                'item_code'   => 'ITM-001',
                'name'        => 'Printer Ink',
                'category'    => 'Product',
                'unit'        => 'bottle',
                'unit_price'  => 350.00,
                'description' => 'Printer Ink – High-quality UV ink for inkjet printers. Compatible with most major brands.',
                'brand'       => 'INKPIU',
                'type'        => 'Office Supplies',
                'is_active'   => true,
            ],
            [
                'item_code'   => 'ITM-002',
                'name'        => 'Ballpoint Pen Set',
                'category'    => 'Product',
                'unit'        => 'set',
                'unit_price'  => 299.00,
                'description' => 'Premium ballpoint pen set with 12 assorted colors. Quick dry ink, porous nibs, comfortable grip.',
                'brand'       => 'FOUR CANDIES',
                'type'        => 'Office Supplies',
                'is_active'   => true,
            ],
            [
                'item_code'   => 'ITM-003',
                'name'        => 'A4 Copy Paper Ream',
                'category'    => 'Product',
                'unit'        => 'ream',
                'unit_price'  => 245.00,
                'description' => 'High-quality A4 copy paper, 500 sheets per ream. Ultra white, suitable for copiers and printers.',
                'brand'       => 'Hard Copy',
                'type'        => 'Office Supplies',
                'is_active'   => true,
            ],
            [
                'item_code'   => 'ITM-004',
                'name'        => 'Desk Organizer',
                'category'    => 'Product',
                'unit'        => 'pcs',
                'unit_price'  => 599.00,
                'description' => 'Multi-compartment desk organizer for office supplies. Bamboo wood construction with multiple slots.',
                'brand'       => 'WoodCraft',
                'type'        => 'Office Furniture',
                'is_active'   => true,
            ],
        ];

        foreach ($featured as $item) {
            Product::create($item);
        }

        // Additional sample products
        $additional = [
            [
                'item_code'   => 'ITM-005',
                'name'        => 'Whiteboard Marker Set',
                'category'    => 'Product',
                'unit'        => 'set',
                'unit_price'  => 189.00,
                'description' => 'Set of 8 whiteboard markers in assorted colors. Non-toxic, easy to erase.',
                'brand'       => 'Pilot',
                'type'        => 'Office Supplies',
                'is_active'   => true,
            ],
            [
                'item_code'   => 'ITM-006',
                'name'        => 'Stapler Heavy Duty',
                'category'    => 'Product',
                'unit'        => 'pcs',
                'unit_price'  => 450.00,
                'description' => 'Heavy-duty stapler capable of stapling up to 100 sheets at a time.',
                'brand'       => 'Kang',
                'type'        => 'Office Supplies',
                'is_active'   => true,
            ],
            [
                'item_code'   => 'ITM-007',
                'name'        => 'Filing Cabinet',
                'category'    => 'Product',
                'unit'        => 'pcs',
                'unit_price'  => 4500.00,
                'description' => '3-drawer vertical filing cabinet. Steel construction with lock and key.',
                'brand'       => 'Surelock',
                'type'        => 'Office Furniture',
                'is_active'   => true,
            ],
            [
                'item_code'   => 'ITM-008',
                'name'        => 'Printer Maintenance Service',
                'category'    => 'Service',
                'unit'        => 'unit',
                'unit_price'  => 800.00,
                'description' => 'Professional printer cleaning and maintenance service. Includes head cleaning and alignment.',
                'brand'       => null,
                'type'        => 'Printing',
                'is_active'   => true,
            ],
            [
                'item_code'   => 'ITM-009',
                'name'        => 'Office Chair Ergonomic',
                'category'    => 'Product',
                'unit'        => 'pcs',
                'unit_price'  => 6800.00,
                'description' => 'Ergonomic office chair with lumbar support, adjustable height, and armrests.',
                'brand'       => 'ErgoSit',
                'type'        => 'Office Furniture',
                'is_active'   => true,
            ],
            [
                'item_code'   => 'ITM-010',
                'name'        => 'IT Support Service',
                'category'    => 'Service',
                'unit'        => 'unit',
                'unit_price'  => 1500.00,
                'description' => 'On-site IT support service for computer troubleshooting and network setup.',
                'brand'       => null,
                'type'        => 'Electronics',
                'is_active'   => false,
            ],
        ];

        foreach ($additional as $item) {
            Product::create($item);
        }
    }
}
