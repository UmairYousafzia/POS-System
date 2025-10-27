<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('name')->paginate(20);
        return view('pos.locations', compact('locations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
        ]);
        Location::create($data);
        return back()->with('success', 'Location created');
    }

    public function update(Request $request, Location $location)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
        ]);
        $location->update($data);
        return back()->with('success', 'Location updated');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return back()->with('success', 'Location deleted');
    }
}
