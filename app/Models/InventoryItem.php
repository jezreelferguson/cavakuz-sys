<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    protected $table = 'inventory_items';

    protected $fillable = [
        'name',
        'category',
        'unit_price',
        'stock_quantity',
        'description',
    ];

    protected $casts = [
        'unit_price' => 'float',
        'stock_quantity' => 'integer',
    ];

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
