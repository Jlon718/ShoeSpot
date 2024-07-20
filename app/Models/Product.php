<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'product_name',
        'brand_id',
        'description',
        'sell_price',
        'cost_price'

    ];

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'product_id', 'product_id');
    }

    public function stock()
    {
        return $this->hasMany(Stock::class, 'product_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
