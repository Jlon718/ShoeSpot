<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderline extends Model
{
    use HasFactory;
    protected $table = 'orderlines'; // Specify the table name if it's different from the model name
    protected $fillable = [
        'orderinfo_id',
        'product_id',
        'quantity',
    ];
    
}
