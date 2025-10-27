<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category','unit'])->orderBy('name')->paginate(20);
        $categories = Category::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        return view('pos.products', compact('products','categories','units'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'sku' => 'required|string|unique:products,sku',
            'barcode' => 'nullable|string|unique:products,barcode',
            'category_id' => 'nullable|exists:categories,id',
            'unit_id' => 'nullable|exists:units,id',
            'cost_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        $data['is_active'] = $data['is_active'] ?? true;
        Product::create($data);
        return back()->with('success','Product created');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'unit_id' => 'nullable|exists:units,id',
            'cost_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        $product->update($data);
        return back()->with('success','Product updated');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success','Product deleted');
    }
}
