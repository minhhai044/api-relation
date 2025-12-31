<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'color_name',
        'slug',
        'is_active',
    ];
    public function capacities()
    {
        return $this->belongsToMany(Capacity::class);
    }
}
