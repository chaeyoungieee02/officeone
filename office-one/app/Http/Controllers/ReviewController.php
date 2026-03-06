<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReviewController extends Controller
{
    /**
     * Store a new review for a product.
     */
    public function store(Request $request, Product $product)
    {
        $user = Auth::user();

        // Check if user has a delivered order for this product
        if (!$user->hasDeliveredOrderFor($product->id)) {
            return back()->with('error', 'You can only review products that have been delivered to you.');
        }

        // Check if user already reviewed this product
        if ($user->hasReviewedProduct($product->id)) {
            return back()->with('error', 'You have already reviewed this product. You can update your existing review.');
        }

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        Review::create([
            'user_id'    => $user->id,
            'product_id' => $product->id,
            'rating'     => $validated['rating'],
            'comment'    => $validated['comment'], // ProfanityFilter applied via model mutator
        ]);

        return back()->with('success', 'Your review has been posted successfully!');
    }

    /**
     * Update an existing review.
     */
    public function update(Request $request, Review $review)
    {
        // Only the review author can update
        if (Auth::id() !== $review->user_id) {
            return back()->with('error', 'You can only edit your own reviews.');
        }

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $review->update([
            'rating'  => $validated['rating'],
            'comment' => $validated['comment'], // ProfanityFilter applied via model mutator
        ]);

        return back()->with('success', 'Your review has been updated successfully!');
    }

    /**
     * Admin: List all reviews in DataTable.
     */
    public function adminIndex(Request $request)
    {
        if ($request->ajax()) {
            $query = Review::with(['user', 'product']);

            return DataTables::of($query)
                ->addColumn('user_name', function ($review) {
                    return e($review->user->name);
                })
                ->addColumn('product_name', function ($review) {
                    return '<a href="' . route('products.show', $review->product_id) . '">' . e($review->product->name) . '</a>';
                })
                ->addColumn('stars', function ($review) {
                    $stars = '';
                    for ($i = 1; $i <= 5; $i++) {
                        $stars .= $i <= $review->rating
                            ? '<i class="bi bi-star-fill text-warning"></i>'
                            : '<i class="bi bi-star text-muted"></i>';
                    }
                    return $stars;
                })
                ->addColumn('short_comment', function ($review) {
                    return e(\Illuminate\Support\Str::limit($review->comment, 80));
                })
                ->addColumn('action', function ($review) {
                    $viewBtn = '<a href="' . route('products.show', $review->product_id) . '#reviews" class="btn btn-sm btn-info me-1" title="View"><i class="bi bi-eye"></i></a>';
                    $deleteBtn = '
                        <form action="' . route('admin.reviews.destroy', $review->id) . '" method="POST" class="d-inline">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm(\'Are you sure you want to delete this review?\')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>';
                    return $viewBtn . $deleteBtn;
                })
                ->rawColumns(['product_name', 'stars', 'action'])
                ->make(true);
        }

        // Review stats
        $totalReviews = Review::count();
        $averageRating = round(Review::avg('rating'), 1) ?: 0;
        $fiveStarCount = Review::where('rating', 5)->count();
        $recentCount = Review::where('created_at', '>=', now()->subDays(30))->count();

        // Rating distribution
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDistribution[$i] = Review::where('rating', $i)->count();
        }

        return view('reviews.admin-index', compact(
            'totalReviews', 'averageRating', 'fiveStarCount',
            'recentCount', 'ratingDistribution'
        ));
    }

    /**
     * Admin: Delete a review.
     */
    public function adminDestroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}
