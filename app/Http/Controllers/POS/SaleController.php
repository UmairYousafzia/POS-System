<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\InventoryMovement;
use App\Models\Party;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $customers = Party::where('type', 'customer')->orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();
        $accounts = Account::orderBy('name')->get();
        return view('pos.sell', compact('products','customers','warehouses','accounts'));
    }

    public function productLookup(Request $request)
    {
        $q = $request->get('q');
        $product = Product::where('barcode', $q)->orWhere('sku', $q)->first();
        if (!$product) return response()->json(['message' => 'Not found'], 404);
        return response()->json($product);
    }

    public function show(Sale $sale)
    {
        $sale->load('items.product','party','warehouse','payments');
        return view('pos.sale_show', compact('sale'));
    }

    public function history(Request $request)
    {
        $q = Sale::with(['party','warehouse'])->orderByDesc('date')->orderByDesc('id');
        if ($request->filled('invoice_no')) {
            $q->where('invoice_no', 'like', '%' . $request->invoice_no . '%');
        }
        if ($request->filled('customer')) {
            $q->whereHas('party', function($qq) use ($request){
                $qq->where('name','like','%'.$request->customer.'%');
            });
        }
        if ($request->filled('from')) {
            $q->where('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->where('date', '<=', $request->to);
        }
        $sales = $q->paginate(20)->withQueryString();
        return view('pos.sales_index', compact('sales'));
    }

    public function store(Request $request)
    {
        // Filter out empty/incomplete rows before validation
        $filteredItems = collect($request->input('items', []))
            ->filter(function ($row) {
                $pid = $row['product_id'] ?? null;
                $qty = isset($row['quantity']) ? (float)$row['quantity'] : 0;
                $price = isset($row['price']) ? (float)$row['price'] : 0;
                return $pid && $qty > 0 && $price >= 0;
            })
            ->values()
            ->all();
        $request->merge(['items' => $filteredItems]);
        if (count($filteredItems) === 0) {
            return back()->withErrors(['items' => 'Please add at least one valid item (product, quantity, price).'])->withInput();
        }

        $data = $request->validate([
            'party_id' => 'nullable|exists:parties,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'shipping' => 'nullable|numeric|min:0',
            'payment.amount' => 'nullable|numeric|min:0',
            'payment.account_id' => 'nullable|exists:accounts,id',
            'payment.method' => 'nullable|in:cash,bank_transfer,cheque,card,mobile_wallet',
        ]);

        return DB::transaction(function () use ($data) {
            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $subtotal += ($item['price'] * $item['quantity']);
            }
            $discount = $data['discount'] ?? 0;
            $tax = $data['tax'] ?? 0;
            $shipping = $data['shipping'] ?? 0;
            $total = max(0, $subtotal - $discount + $tax + $shipping);

            $sale = Sale::create([
                'invoice_no' => 'S-' . Str::upper(Str::random(8)),
                'party_id' => $data['party_id'] ?? null,
                'warehouse_id' => $data['warehouse_id'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'paid' => 0,
                'due' => $total,
                'date' => $data['date'],
                'status' => 'completed',
            ]);

            foreach ($data['items'] as $item) {
                $lineTotal = ($item['price'] * $item['quantity']);
                $saleItem = SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discount' => 0,
                    'tax' => 0,
                    'total' => $lineTotal,
                ]);

                InventoryMovement::create([
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $data['warehouse_id'],
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'reference_type' => 'sale',
                    'reference_id' => $sale->id,
                    'date' => $data['date'],
                    'note' => 'Sale ' . $sale->invoice_no,
                ]);
            }

            if (!empty($data['payment']['amount']) && $data['payment']['amount'] > 0) {
                $sale->payments()->create([
                    'account_id' => $data['payment']['account_id'],
                    'method' => $data['payment']['method'] ?? 'cash',
                    'amount' => min($data['payment']['amount'], $total),
                    'paid_at' => $data['date'],
                    'reference' => $sale->invoice_no,
                ]);
                $sale->paid = $sale->payments()->sum('amount');
                $sale->due = max(0, $sale->total - $sale->paid);
                $sale->save();
            }

            return redirect()->route('pos.sales.show', $sale)->with('success', 'Sale created');
        });
    }
}
