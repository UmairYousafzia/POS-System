<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Location;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PosSeeder extends Seeder
{
    public function run(): void
    {
        BusinessSetting::query()->updateOrCreate([], [
            'business_name' => 'Hassan International Chemicals & Fertilizer',
            'tagline' => 'Wholesale - Lahore Akbari Mandi',
            'address' => 'Akbari Mandi',
            'city' => 'Lahore',
            'country' => 'Pakistan',
            'phone' => '+92-300-0000000',
            'email' => 'info@hicf.pk',
            'currency' => 'PKR',
        ]);

        $location = Location::firstOrCreate([
            'name' => 'Head Office',
        ], [
            'address' => 'Akbari Mandi, Lahore',
            'city' => 'Lahore',
        ]);

        $warehouse = Warehouse::firstOrCreate([
            'location_id' => $location->id,
            'name' => 'Main Warehouse',
        ], [
            'code' => 'MAIN'
        ]);

        Account::firstOrCreate(['type' => 'cash', 'name' => 'Cash in Hand'], ['opening_balance' => 0]);
        Account::firstOrCreate(['type' => 'bank', 'name' => 'Bank Account'], ['opening_balance' => 0]);
        Account::firstOrCreate(['type' => 'mobile_wallet', 'name' => 'JazzCash'], ['opening_balance' => 0]);

        $kg = Unit::firstOrCreate(['name' => 'Kilogram'], ['short_name' => 'KG']);
        $ltr = Unit::firstOrCreate(['name' => 'Liter'], ['short_name' => 'L']);
        $bag = Unit::firstOrCreate(['name' => 'Bag'], ['short_name' => 'Bag']);

        $chemCat = Category::firstOrCreate(['name' => 'Chemicals']);
        $fertCat = Category::firstOrCreate(['name' => 'Fertilizers']);

        Product::firstOrCreate([
            'sku' => 'CHEM-UREA',
        ], [
            'category_id' => $fertCat->id,
            'unit_id' => $bag->id,
            'name' => 'Urea 50kg',
            'barcode' => Str::random(12),
            'cost_price' => 5000,
            'sale_price' => 5500,
        ]);

        Product::firstOrCreate([
            'sku' => 'CHEM-DAP',
        ], [
            'category_id' => $fertCat->id,
            'unit_id' => $bag->id,
            'name' => 'DAP 50kg',
            'barcode' => Str::random(12),
            'cost_price' => 12000,
            'sale_price' => 12800,
        ]);

        Product::firstOrCreate([
            'sku' => 'CHEM-POTASH',
        ], [
            'category_id' => $chemCat->id,
            'unit_id' => $kg->id,
            'name' => 'Potash',
            'barcode' => Str::random(12),
            'cost_price' => 200,
            'sale_price' => 230,
        ]);
    }
}
