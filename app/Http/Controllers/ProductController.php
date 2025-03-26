<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\log;
class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:vendor'); // Ensure only vendors can access
    }

    // Show all products of the authenticated vendor
   public function index() 
{
    // Get the vendor with associated products
    $vendor = Vendor::where('user_id', Auth::id())->with('products')->first();

    // If vendor exists, get their products, otherwise use an empty collection
    $products = $vendor ? $vendor->products : collect();
    
    // Pass the vendor and products data to the view
    return view('vendor.home', compact('products', 'vendor'));
}


    // Show product creation form
    public function create()
    {
        $vendor = Vendor::where('user_id', Auth::id())->firstOrFail();
        return view('products.create', ['product' => new Product(), 'vendor' => $vendor]);

    }

   // Store new product
public function store(Request $request)
{
    // Validation for the incoming request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'quantity' => 'required|integer',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Example image validation
    ]);

    // Create and store the product
    $product = new Product();
    $product->name = $validated['name'];
    $product->price = $validated['price'];
    $product->quantity = $validated['quantity'];

    // Add vendor_id directly to the product
    $product->vendor_id = Auth::user()->id; // Assuming the logged-in user is a vendor

    // If there's an image, handle its storage
    if ($request->hasFile('image')) {
        $product->image = $request->file('image')->store('products', 'public');
    }

    // Save the product
    $product->save();
    
    // Redirect to the home page with a success message
    return redirect()->route('vendor.home')->with('success', 'Product created successfully!');
}

    

    // Show edit form for a product
    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        $vendor = Vendor::where('user_id', Auth::id())->first();
        $products = $vendor ? $vendor->products : collect();

        return view('products.edit', compact('product', 'vendor'));


    }

    // Update product
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        if ($request->hasFile('image')) {
            $validated['image'] = $this->handleImageUpload($request, $product);
        }
    
        $product->update($validated);
    
        return redirect()->route('vendor.home')->with('success', 'Product created successfully!');

    }
    
    // Delete product
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    // Handle image upload
    private function handleImageUpload(Request $request, Product $product = null)
    {
        if ($product && $product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        return $request->file('image')->store('product_images', 'public');
    }
}
