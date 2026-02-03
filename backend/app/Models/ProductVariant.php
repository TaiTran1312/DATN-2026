<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $primaryKey = 'variant_id';

    protected $fillable = [
        'product_id', 'color', 'storage_size', 'name',
        'price_extra', 'sku', 'stock', 'image_urls',
    ];

    protected $casts = [
        'price_extra' => 'decimal:2',
        'image_urls'  => 'array', // Nếu lưu JSON
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }
}
