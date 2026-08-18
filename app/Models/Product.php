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

    public function flowerVariants()
    {
        return $this->belongsToMany(CustomizationOptionVariant::class, 'product_flower_variants', 'product_id', 'variant_id')
            ->withPivot('quantity');
    }

    public function getIsAvailableAttribute(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        return $this->flowerVariants->every(
            fn (CustomizationOptionVariant $variant) => $variant->is_active && $variant->option && $variant->option->isAvailable()
        );
    }
}
