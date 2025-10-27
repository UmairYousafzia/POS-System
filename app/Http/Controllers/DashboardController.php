<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Party;
use App\Models\Expense;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        $todaySales = Sale::whereDate('date', $today)->sum('total');
        $weekSales = Sale::where('date', '>=', $startOfWeek)->sum('total');
        $monthSales = Sale::where('date', '>=', $startOfMonth)->sum('total');
        $customersCount = Party::where('type','customer')->count();

        $todayPurchases = Purchase::whereDate('date', $today)->sum('total');
        $receivables = Sale::sum('due');
        $payables = Purchase::sum('due');
        $monthExpenses = Expense::where('date', '>=', $startOfMonth)->sum('amount');

        $recentSales = Sale::with('party')->orderByDesc('date')->orderByDesc('id')->limit(10)->get();
        $recentPurchases = Purchase::with('party')->orderByDesc('date')->orderByDesc('id')->limit(10)->get();

        return view('default', compact(
            'todaySales','weekSales','monthSales','customersCount','todayPurchases',
            'receivables','payables','monthExpenses','recentSales','recentPurchases'
        ));
    }
    public function edit()
    {
         return view('profile');
    }

   public function update(Request $request)
{
    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'string',
            'lowercase',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore(auth()->id())
        ],
        'image' => ['nullable', 'image', 'max:2048'],
    ]);

    try {
        DB::beginTransaction();

        // Remove image from $data so it’s not inserted into users table
        unset($data['image']);

        $user = auth()->user();
        $user->update($data);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if (!$file->isValid()) {
                Log::warning('Invalid image upload attempt for user ID: '.$user->id);
                DB::rollBack();
                return back()->with('error', 'Invalid image upload. Please try a different image.');
            }

            // Store new file first
            $newPath = $file->store('avatars', 'public');
            if (! $newPath) {
                Log::error('Failed to store uploaded image for user ID: '.$user->id);
                DB::rollBack();
                return back()->with('error', 'Failed to save image.');
            }

            // Delete old file if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Save new path
            $user->avatar = $newPath;
            $user->save();
        }

        DB::commit();

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Profile update failed: ' . $e->getMessage());
        return back()->with('error', 'Something went wrong while updating profile.');
    }

    return back()->with('success', 'Profile updated successfully!');
}

}
