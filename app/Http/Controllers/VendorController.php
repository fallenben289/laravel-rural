<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function showRegister()
    {
        return view('vendor.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'shop_name' => 'required|string|max:255|unique:vendors,shop_name',
            'phone' => 'required|string|max:15|unique:vendors,phone|regex:/^\+?[0-9]+$/',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        $vendor = Vendor::create([
            'name' => $validated['name'],
            'shop_name' => $validated['shop_name'],
            'phone' => $validated['phone'],
            'description' => $validated['description'],
            'image' => $request->file('image')?->store('vendor_images', 'public'),
        ]);

        Auth::guard('vendor')->login($vendor);

        return redirect()->route('vendor.home')
            ->with('success', 'Registration successful!');
    }

    public function showLogin()
    {
        return view('vendor.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => 'required|string',
        ]);

        $vendor = Vendor::where('phone', $credentials['phone'])->first();

        if ($vendor) {
            Auth::guard('vendor')->login($vendor);
            $request->session()->regenerate();
            return redirect()->intended(route('vendor.home'))
                ->with('success', 'Login successful!');
        }

        return back()
            ->withInput()
            ->withErrors(['phone' => 'Invalid phone number']);
    }

    public function home()
    {
        return view('vendor.home', [
            'vendor' => Auth::guard('vendor')->user(),
            'products' => Auth::guard('vendor')->user()->products ?? []
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('vendor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('vendor.login')
            ->with('success', 'Logged out successfully!');
    }
}