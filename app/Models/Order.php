<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    // Define custom table
    protected $table = 'lms_orders';

    // The attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'course_id',
        'stripe_payment_intent_id',
        'amount',
        'status',
    ];

    /**
     * Get the user that own the order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the course belongs to the order
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
