<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPhoto;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the products (with DataTable).
     */
    public function index(Request $request)
    {
        // Regular users get a storefront card-grid view
        if (!auth()->user()->isAdmin() && !$request->ajax()) {
            $products = Product::where('is_active', true)
                ->with(['photos', 'reviews'])
                ->orderBy('name')
                ->get();
            return view('products.index-user', compact('products'));
        }

        if ($request->ajax()) {
            $showTrashed = $request->get('show_trashed', false);

            $query = $showTrashed
                ? Product::onlyTrashed()->with('photos')
                : Product::with('photos');

            return DataTables::of($query)
                ->addColumn('photo', function ($product) {
                    $photo = $product->photos->first();
                    if ($photo) {
                        return '<img src="' . asset('storage/' . $photo->photo_path) . '" alt="' . e($product->name) . '" width="50" height="50" class="rounded" style="object-fit:cover;">';
                    }
                    return '<span class="text-muted"><i class="bi bi-image" style="font-size:2rem;"></i></span>';
                })
                ->addColumn('status', function ($product) {
                    return $product->is_active
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-secondary">Inactive</span>';
                })
                ->addColumn('formatted_price', function ($product) {
                    return '₱' . number_format($product->unit_price, 2);
                })
                ->addColumn('action', function ($product) {
                    if ($product->trashed()) {
                        return '
                            <form action="' . route('products.restore', $product->id) . '" method="POST" class="d-inline">
                                ' . csrf_field() . method_field('PATCH') . '
                                <button type="submit" class="btn btn-sm btn-success" title="Restore">
                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                </button>
                            </form>';
                    }

                    $viewBtn = '<a href="' . route('products.show', $product->id) . '" class="btn btn-sm btn-info me-1" title="View"><i class="bi bi-eye"></i></a>';

                    $editBtn = '<a href="' . route('products.edit', $product->id) . '" class="btn btn-sm btn-warning me-1" title="Edit"><i class="bi bi-pencil"></i></a>';
                    $deleteBtn = '
                        <form action="' . route('products.destroy', $product->id) . '" method="POST" class="d-inline delete-form">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm(\'Are you sure you want to delete this product?\')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>';
                    return $viewBtn . $editBtn . $deleteBtn;
                })
                ->rawColumns(['photo', 'status', 'action'])
                ->make(true);
        }

        return view('products.index');
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_code'   => 'required|string|max:50|unique:products,item_code',
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:Product,Service',
            'unit'        => 'nullable|string|max:50',
            'unit_price'  => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'brand'       => 'nullable|string|max:100',
            'type'        => 'nullable|string|max:100',
            'is_active'   => 'sometimes|boolean',
            'photos'      => 'nullable|array',
            'photos.*'    => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $product = Product::create($validated);

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('products', 'public');
                $product->photos()->create(['photo_path' => $path]);
            }
        }

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load('photos', 'reviews.user');

        // Admin sees the detailed management view (image 2 style)
        if (auth()->user()->isAdmin()) {
            return view('products.show', compact('product'));
        }

        // Regular user sees the e-commerce / storefront view
        return view('products.show-user', compact('product'));
    }

    /**
     * Show the form for editing the product.
     */
    public function edit(Product $product)
    {
        $product->load('photos');
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'item_code'   => ['required', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:Product,Service',
            'unit'        => 'nullable|string|max:50',
            'unit_price'  => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'brand'       => 'nullable|string|max:100',
            'type'        => 'nullable|string|max:100',
            'is_active'   => 'sometimes|boolean',
            'photos'      => 'nullable|array',
            'photos.*'    => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $product->update($validated);

        // Handle new photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('products', 'public');
                $product->photos()->create(['photo_path' => $path]);
            }
        }

        // Handle photo deletions
        if ($request->filled('delete_photos')) {
            $photoIds = $request->input('delete_photos');
            $photos = ProductPhoto::whereIn('id', $photoIds)
                ->where('product_id', $product->id)
                ->get();

            foreach ($photos as $photo) {
                Storage::disk('public')->delete($photo->photo_path);
                $photo->delete();
            }
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Soft-delete the specified product.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Restore a soft-deleted product.
     */
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('products.index')
            ->with('success', 'Product restored successfully.');
    }

    /**
     * Import products from an Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect()->route('products.index')
                ->with('success', 'Products imported successfully.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return redirect()->route('products.index')
                ->with('import_errors', $failures)
                ->with('error', 'Some rows failed validation during import.');
        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Download import template.
     */
    public function downloadTemplate()
    {
        $headers = ['Item Code', 'Name', 'Category (Product/Service)', 'Unit', 'Unit Price', 'Description', 'Brand', 'Type', 'Active (1/0)'];

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            // Sample row
            fputcsv($file, ['ITM-001', 'Sample Product', 'Product', 'pcs', '100.00', 'Sample description', 'Sample Brand', 'Office Supplies', '1']);
            fclose($file);
        };

        return response()->streamDownload($callback, 'products_import_template.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
