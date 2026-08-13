<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public $timestamps = false;

    protected $fillable = ['name', 'description', 'price', 'image_url', 'is_active'];

    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'category_product', 'product_id', 'category_id');
    }

    public function flowers()
    {
        return $this->belongsToMany(CustomizationOption::class, 'flower_product', 'product_id', 'flower_id');
    }

    public function getIsAvailableAttribute(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        return $this->flowers->every(fn (CustomizationOption $flower) => (int) $flower->stock_quantity > 0);
    }
}
