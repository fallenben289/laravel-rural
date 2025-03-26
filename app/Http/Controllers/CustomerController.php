<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function show($id)
    {
        $vendor = Vendor::findOrFail($id);  // Find the vendor by ID
        $products = Product::where('vendor_id', $id)->get();  // Fetch products for the vendor

        return view('customer', compact('vendor', 'products'));  // Pass vendor and products to the view
    }
}

