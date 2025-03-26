<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vendor;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Create a new controller instance with authentication middleware.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth'); // Ensure only authenticated users can access
    }

    /**
     * Show the vendor's store and products.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user(); // Get the authenticated user
        $vendor = Vendor::with('products')->where('user_id', Auth::user()->id
        )->first();
        $products = $vendor?->products ?? [];
        

        return view('home', compact('vendor', 'products')); // Pass data to view
    }
}


//Auth::user()->id
//auth()->id()