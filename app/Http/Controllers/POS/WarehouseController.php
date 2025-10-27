<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::with('location')->orderBy('name')->paginate(20);
        $locations = Location::orderBy('name')->get();
        return view('pos.warehouses', compact('warehouses','locations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string',
            'code' => 'nullable|string',
        ]);
        Warehouse::create($data);
        return back()->with('success','Warehouse created');
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $data = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string',
            'code' => 'nullable|string',
        ]);
        $warehouse->update($data);
        return back()->with('success','Warehouse updated');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return back()->with('success','Warehouse deleted');
    }
}
