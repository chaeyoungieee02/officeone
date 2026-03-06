<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Admin: display all orders with DataTable.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Order::with(['user', 'product.photos'])->latest();

            return DataTables::of($query)
                ->addColumn('customer', function ($order) {
                    return e($order->user->name ?? 'Deleted User');
                })
                ->addColumn('product_name', function ($order) {
                    if ($order->product) {
                        return '<a href="' . route('products.show', $order->product) . '">' . e($order->product->name) . '</a>';
                    }
                    return '<span class="text-muted">Deleted</span>';
                })
                ->addColumn('photo', function ($order) {
                    if ($order->product && $order->product->photos->count() > 0) {
                        return '<img src="' . asset('storage/' . $order->product->photos->first()->photo_path) . '" width="40" height="40" class="rounded" style="object-fit:cover;">';
                    }
                    return '<i class="bi bi-box-seam text-muted"></i>';
                })
                ->addColumn('formatted_total', function ($order) {
                    return '₱' . number_format($order->total_price, 2);
                })
                ->addColumn('order_status', function ($order) {
                    return match($order->status) {
                        'completed' => '<span class="badge bg-success">Completed</span>',
                        'pending'   => '<span class="badge bg-warning text-dark">Pending</span>',
                        'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
                        default     => '<span class="badge bg-secondary">' . e($order->status) . '</span>',
                    };
                })
                ->addColumn('delivery', function ($order) {
                    return match($order->delivery_status) {
                        'delivered'  => '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Delivered</span>',
                        'shipped'    => '<span class="badge bg-info"><i class="bi bi-truck"></i> Shipped</span>',
                        'processing' => '<span class="badge bg-secondary"><i class="bi bi-clock"></i> Processing</span>',
                        'cancelled'  => '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Cancelled</span>',
                        'returned'   => '<span class="badge bg-warning text-dark"><i class="bi bi-arrow-return-left"></i> Returned</span>',
                        default      => '<span class="badge bg-secondary">' . e($order->delivery_status) . '</span>',
                    };
                })
                ->addColumn('date', function ($order) {
                    return $order->created_at->format('M d, Y');
                })
                ->addColumn('action', function ($order) {
                    $statuses = [
                        'processing' => 'Processing',
                        'shipped'    => 'Shipped',
                        'delivered'  => 'Delivered',
                        'cancelled'  => 'Cancelled',
                        'returned'   => 'Returned',
                    ];

                    $form = '<form action="' . route('admin.orders.updateDelivery', $order->id) . '" method="POST" class="d-flex align-items-center gap-1">
                        ' . csrf_field() . method_field('PATCH') . '
                        <select name="delivery_status" class="form-select form-select-sm" style="width:130px;" onchange="this.form.submit()">';

                    foreach ($statuses as $value => $label) {
                        $selected = $order->delivery_status === $value ? ' selected' : '';
                        $form .= '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
                    }

                    $form .= '</select></form>';

                    return $form;
                })
                ->rawColumns(['product_name', 'photo', 'order_status', 'delivery', 'action'])
                ->make(true);
        }

        // Order stats for the view
        $totalOrders = Order::count();
        $processingOrders = Order::where('delivery_status', 'processing')->count();
        $shippedOrders = Order::where('delivery_status', 'shipped')->count();
        $deliveredOrders = Order::where('delivery_status', 'delivered')->count();
        $cancelledOrders = Order::whereIn('delivery_status', ['cancelled', 'returned'])->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

        return view('orders.admin-index', compact(
            'totalOrders', 'processingOrders', 'shippedOrders',
            'deliveredOrders', 'cancelledOrders', 'totalRevenue'
        ));
    }

    /**
     * Admin: update delivery status of an order.
     */
    public function updateDelivery(Request $request, Order $order)
    {
        $validated = $request->validate([
            'delivery_status' => 'required|in:processing,shipped,delivered,cancelled,returned',
        ]);

        $newStatus = $validated['delivery_status'];

        // If cancelled or returned, also update the order status
        if (in_array($newStatus, ['cancelled', 'returned'])) {
            $order->update([
                'delivery_status' => $newStatus,
                'status'          => 'cancelled',
            ]);
        } else {
            $order->update([
                'delivery_status' => $newStatus,
                'status'          => 'completed',
            ]);
        }

        $statusLabel = ucfirst($newStatus);

        return back()->with('success', "Order #{$order->id} updated to {$statusLabel}.");
    }
}
