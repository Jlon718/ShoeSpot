<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderinfo extends Model
{
    use HasFactory;
    protected $table = 'orderinfos'; // Specify the table name if it's different from the model name
    protected $primaryKey = 'orderinfo_id'; // Specify the primary key if it's different from 'id'
    protected $fillable = [
        'customer_id',
        'date_place',
        'date_shipped',
        'shipping_fee',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'orderlines', 'orderinfo_id', 'product_id')->withPivot('quantity');
    }
}
