<?php

namespace App\Imports;

use App\Models\Supplier;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SuppliersImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Supplier::updateOrCreate(
                ['supplier_name' => $row['supplier_name']],
                [
                    'email' => $row['email'],
                    'phone_number' => $row['phone_number'],
                    'address' => $row['address'],                   
                ]
            );
        }
    }


}
