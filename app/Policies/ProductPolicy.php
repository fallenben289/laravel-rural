<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false; // You can change this to `true` if you want to allow viewing all products.
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): bool
    {
        return false; // Change this logic if you want to allow users to view a specific product.
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false; // You can modify this to `true` if users can create products.
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Vendor $vendor, Product $product): bool
    {
        // Only allow the vendor to update the product if they are the owner of the product
        return $vendor->id === $product->vendor_id;
    }
    

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Vendor $vendor, Product $product): bool
    {
        // Your authorization logic here
        return $vendor->id === $product->vendor_id; // Example condition
    }


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Product $product): bool
    {
        return false; // Modify this as needed.
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return false; // Modify this as needed.
    }
}
