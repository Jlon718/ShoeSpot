<?php

namespace App\Imports;

use App\Models\Image;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Stockline;
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
            // Import or update the product
            $product = Product::updateOrCreate(
                ['product_name' => $row['product_name']],
                [
                    'brand_id' => $row['brand_id'],
                    'description' => $row['description'],
                    'sell_price' => $row['sell_price'], 
                    'cost_price' => $row['cost_price'],
                ]
            );

            // Import or update the image
            if (isset($row['image_path'])) {
                Image::updateOrCreate(
                    ['product_id' => $product->product_id],
                    ['image_path' => $row['image_path']]
                );
            }

            // Import or update the stock
            if (isset($row['stock'])) {
                $stock = new Stock();
                $stock->product_id = $product->product_id;
                $stock->quantity = $row['stock'];
                $stock->save();
            }

            // Import or update the supplier
            if (isset($row['supplier_name'])) {
                $stockline = new Stockline();
                $stockline->stock_id = $stock->stock_id;
                $stockline->supplier_id = $row['supplier'];
                $stockline->save();    
            }
        }
    }
}