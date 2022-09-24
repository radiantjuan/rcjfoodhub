<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTotals extends Model {
    use HasFactory;

    protected $fillable = [
        'total_costs',
        'total_items',
        'total_to_be_paid',
        'order_id',
    ];
}
