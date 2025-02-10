<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeedBack extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'review_star',
        'message',
        'status',
        'user_information_data'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
