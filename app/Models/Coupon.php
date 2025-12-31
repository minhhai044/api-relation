<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'coupon_code',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'coupon_limit',
        'coupon_used',
        'coupon_status',
        'coupon_description',
    ];
    public function products(){
        return $this->belongsToMany(Product::class,'product_coupon');
    }
}
