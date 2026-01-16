<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'barcode',
        'price',
        'unit',
        'initial_stock',
        'current_stock',
        'expiry_date',
        'manufacturer',
        'user_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'expiry_date' => 'date',
        'initial_stock' => 'integer',
        'current_stock' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barcodeEntries()
    {
        return $this->hasMany(BarcodeEntry::class);
    }

    public function stockAlerts()
    {
        return $this->hasMany(StockAlert::class);
    }

    public function getStockPercentageAttribute(): float
    {
        if ($this->initial_stock == 0) {
            return 0;
        }
        return ($this->current_stock / $this->initial_stock) * 100;
    }

    public function isLowStock(float $threshold = 10.0): bool
    {
        return $this->stock_percentage <= $threshold;
    }
}

