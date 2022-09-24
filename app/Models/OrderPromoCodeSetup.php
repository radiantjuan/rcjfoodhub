<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPromoCodeSetup extends Model {
    use HasFactory;
    protected $table = 'order_promo_code_setup';
    protected $fillable = [
        'promo_code',
        'promo_code_id',
        'order_id',
        'promo_code_coverage',
        'promo_code_exceptions',
        'new_amount_to_be_paid',
        'promo_code_total_discount',
        'previous_amount_to_be_paid',
    ];


}
