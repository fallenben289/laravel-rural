<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'shop_name', 'phone', 'description', 'image'];

    // Define the relationship with the Product model
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
