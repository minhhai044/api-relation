<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_variant_id',
        'order_id',
        'order_item_quantity',
        'product_name',
        'product_sku',
        'product_img_thumbnail',
        'pro_price_regular',
        'pro_price_sale',
        'variant_capacity_name',
        'variant_color_name',
    ];
}
