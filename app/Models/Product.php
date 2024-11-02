<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'pro_name',
        'pro_image',
        'pro_price',
        'pro_description',
        'pro_quantity',
        'pro_active',
        'category_id'
    ];
}
