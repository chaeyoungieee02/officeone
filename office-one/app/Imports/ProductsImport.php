<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    /**
     * Map each row to a Product model.
     */
    public function model(array $row)
    {
        return new Product([
            'item_code'   => $row['item_code'],
            'name'        => $row['name'],
            'category'    => $row['category_productservice'] ?? $row['category'] ?? 'Product',
            'unit'        => $row['unit'] ?? null,
            'unit_price'  => $row['unit_price'] ?? 0,
            'description' => $row['description'] ?? null,
            'brand'       => $row['brand'] ?? null,
            'type'        => $row['type'] ?? null,
            'is_active'   => isset($row['active_10']) ? (bool) $row['active_10'] : (isset($row['active']) ? (bool) $row['active'] : true),
        ]);
    }

    /**
     * Validation rules for each row.
     */
    public function rules(): array
    {
        return [
            'item_code' => 'required|string|max:50|unique:products,item_code',
            'name'      => 'required|string|max:255',
            '*.category_productservice' => 'nullable|in:Product,Service',
            '*.category' => 'nullable|in:Product,Service',
            'unit_price' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function customValidationMessages(): array
    {
        return [
            'item_code.unique' => 'The item code ":input" already exists.',
            'item_code.required' => 'Item code is required.',
            'name.required' => 'Product name is required.',
        ];
    }
}
