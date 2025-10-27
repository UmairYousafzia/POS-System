<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stockOnHand(Request $request)
    {
        $warehouses = Warehouse::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        $q = InventoryMovement::query()
            ->selectRaw('product_id, warehouse_id, SUM(CASE WHEN type = "in" THEN quantity WHEN type = "out" THEN -quantity ELSE quantity END) as stock')
            ->groupBy('product_id','warehouse_id');

        if ($request->filled('warehouse_id')) {
            $q->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('product_id')) {
            $q->where('product_id', $request->product_id);
        }

        $rows = $q->get()->map(function ($row) {
            return [
                'product' => Product::find($row->product_id),
                'warehouse' => Warehouse::find($row->warehouse_id),
                'stock' => (float)$row->stock,
            ];
        })->sortBy([['product.name', 'asc'], ['warehouse.name', 'asc']]);

        return view('pos.stock', [
            'rows' => $rows,
            'warehouses' => $warehouses,
            'products' => $products,
            'filters' => $request->only('warehouse_id','product_id'),
        ]);
    }
}
