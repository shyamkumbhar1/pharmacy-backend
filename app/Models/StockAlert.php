<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'user_id',
        'threshold_percentage',
        'current_stock_percentage',
        'alert_sent_at',
        'is_resolved',
    ];

    protected $casts = [
        'threshold_percentage' => 'decimal:2',
        'current_stock_percentage' => 'decimal:2',
        'alert_sent_at' => 'datetime',
        'is_resolved' => 'boolean',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resolve(): void
    {
        $this->update(['is_resolved' => true]);
    }
}

