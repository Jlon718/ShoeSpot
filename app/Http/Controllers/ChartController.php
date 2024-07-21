<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ChartController extends Controller
{
    public function titleChart()
    {
        $customer = DB::table('customers')->groupBy('addressline')->orderBy('total')->pluck(DB::raw('count(addressline) as total'), 'addressline')->all();
        // dd($customer);
        $labels = (array_keys($customer));
        $data = array_values($customer);
        // dd($customer, $data, $labels);
        return response()->json(array('data' => $data, 'labels' => $labels));
    }

    public function salesChart()
    {
        $sales = DB::table('products as i')
            ->join('orderlines as ol', 'i.product_id', '=', 'ol.product_id')
            ->join('orderinfos as oi', 'ol.orderinfo_id', '=', 'oi.orderinfo_id')
            ->select(DB::raw('monthname(oi.date_place) as month, sum(ol.quantity * i.sell_price) as total'))
            ->groupBy('oi.date_place')
            ->pluck('total', 'month')
            ->all();

        // dd($sales);
        $labels = (array_keys($sales));

        $data = array_values($sales);
        // dd($sales, $data, $labels);
        return response()->json(array('data' => $data, 'labels' => $labels));
    }

    public function itemsChart() {
        
        $items = DB::table('products as i')
                    ->join('orderlines as ol', 'i.product_id', '=', 'ol.product_id')
                    ->select(DB::raw('i.product_name as products, sum(ol.quantity) as total'))
                    ->groupBy('i.product_name')
                    ->pluck('total','products')
                    ->all();
                    
        // dd($items);
        $labels = (array_keys($items));
        $data= array_values($items);
        // dd($items, $data, $labels);
        return response()->json(array('data' => $data, 'labels' => $labels));
    }
}
