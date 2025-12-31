<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_variant_id',
        'cart_item_quantity'
    ];
    public function productVariant(){
        return $this->belongsTo(ProductVariant::class);
    }
}
