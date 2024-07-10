<?php

namespace App\Imports;

use App\Models\Brand;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\ToModel;

class BrandsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Brand([
            //
        ]);
    }

    public function brandsImport(Request $request)
    {
        $request->validate([
            'item_upload' => [
                'required',
                'file'
            ],
        ]);

        try {
            Excel::import(new BrandsImport, $request->file('item_upload'));
            
            // Import successful, now redirect
            return redirect('http://127.0.0.1:8000/brands')->with('success', 'File imported successfully');
            
        } catch (\Exception $e) {
            // Handle import failure, if needed
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }

    }
}
