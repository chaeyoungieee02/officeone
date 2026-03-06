<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Admin dashboard.
     */
    public function adminDashboard()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $inactiveProducts = Product::where('is_active', false)->count();
        $trashedProducts = Product::onlyTrashed()->count();
        $totalUsers = User::where('role', 'user')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $recentProducts = Product::with('photos')->latest()->take(5)->get();
        $productCategories = Product::selectRaw('category, count(*) as count')->groupBy('category')->get();
        $serviceCount = Product::where('category', 'Service')->count();
        $productCount = Product::where('category', 'Product')->count();
        $totalReviews = Review::count();

        // Order & revenue stats
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        $pendingDeliveries = Order::where('delivery_status', 'processing')
            ->orWhere('delivery_status', 'shipped')
            ->count();
        $deliveredOrders = Order::where('delivery_status', 'delivered')->count();

        // Recent orders
        $recentOrders = Order::with(['user', 'product'])->latest()->take(5)->get();

        // Monthly revenue (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyRevenue[] = [
                'month' => $date->format('M'),
                'revenue' => Order::where('status', 'completed')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('total_price'),
            ];
        }

        return view('dashboards.admin', compact(
            'totalProducts', 'activeProducts', 'inactiveProducts', 'trashedProducts',
            'totalUsers', 'totalAdmins', 'recentProducts', 'productCategories',
            'serviceCount', 'productCount', 'totalReviews',
            'totalOrders', 'totalRevenue', 'pendingDeliveries', 'deliveredOrders',
            'recentOrders', 'monthlyRevenue'
        ));
    }

    /**
     * User dashboard.
     */
    public function userDashboard()
    {
        $featuredProducts = Product::with('photos')
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $totalProducts = Product::where('is_active', true)->count();
        $categories = Product::where('is_active', true)
            ->selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->get();

        return view('dashboards.user', compact('featuredProducts', 'totalProducts', 'categories'));
    }
}
