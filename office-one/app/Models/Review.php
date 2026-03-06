<?php

namespace App\Models;

use App\Helpers\ProfanityFilter;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Automatically filter profanity when setting the comment.
     */
    public function setCommentAttribute($value)
    {
        $this->attributes['comment'] = ProfanityFilter::filter($value);
    }

    /**
     * Get the user who wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product being reviewed.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
