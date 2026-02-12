<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'variant_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'color',
        'storage_size',
        'name',           // tên variant (ví dụ: "Đen 256GB", "Xanh 512GB")
        'price_extra',    // giá cộng thêm so với giá gốc của sản phẩm
        'sku',
        'stock',
        'image_urls',     // mảng URL ảnh variant (nếu khác ảnh chính)
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price_extra' => 'decimal:2',
        'image_urls'  => 'array',           // lưu dưới dạng JSON trong DB
        'stock'       => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /**
     * Get the product that owns the variant.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the order items associated with the variant.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'variant_id', 'variant_id');
    }

    /**
     * Get the cart items associated with the variant (nếu bạn có bảng cart_items).
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'variant_id', 'variant_id');
    }

    /**
     * Accessor: Giá cuối cùng của variant (giá gốc sản phẩm + price_extra)
     */
    public function getFinalPriceAttribute(): float
    {
        return ($this->product->price ?? 0) + ($this->price_extra ?? 0);
    }

    /**
     * Scope: Variants còn hàng (stock > 0)
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope: Variants theo màu
     */
    public function scopeByColor($query, string $color)
    {
        return $query->where('color', $color);
    }
}
