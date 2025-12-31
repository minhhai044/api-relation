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
        'pro_sku',
        'pro_description',
        'pro_img_thumbnail',
        'pro_price_regular',
        'pro_price_sale',
        'pro_featured',
        'pro_views',
        'category_id'
    ];
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
    public function tags(){
        return $this->belongsToMany(Tag::class,'product_tags');
    }
    public function product_variant(){
        return $this->hasMany(ProductVariant::class);
    }

}
