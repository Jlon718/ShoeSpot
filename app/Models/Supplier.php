<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'suppliers'; // Specify the table name if it's different from the model name
    protected $primaryKey = 'supplier_id'; // Specify the primary key if it's different from 'id'
    protected $fillable = [
        'supplier_name',
        'email',
        'phone_number',
        'address',
    ];

    public function stocks()
    {
        return $this->belongsToMany(Stock::class, 'stocklines', 'supplier_id', 'stock_id');
    }
}
