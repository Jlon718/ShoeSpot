<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ProductsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Product::updateOrCreate(
                ['product_name' => $row['product_name']],
                [
                    'brand_id' => $row['brand_id'],
                    'description' => $row['description'],
                    'sell_price' => $row['sell_price'], 
                    'cost_price' => $row['cost_price'],                     
                ]
            );
        }
    }
}
