<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OrderedItems extends Model {
    use HasFactory;

    protected $fillable = [
        'grams',
        'price',
        'name',
        'quantity',
        'total_cost',
        'supply_id',
        'order_id',
    ];

    public function order() {
        return $this->hasOne(Orders::class, 'id', 'order_id');
    }

    /**
     * Get top 10 supplies ordered
     *
     * @param array $dates
     *
     * @return array
     */
    public static function get_top_10_ordered_items($dates) {
        $ordered_items = self::selectRaw('
            SUM(quantity) as ordered_quantity,
            supplies.name as title,
            supplies.img_url')
            ->join('orders', 'ordered_items.order_id', '=', 'orders.id')
            ->join('supplies', 'ordered_items.supply_id', '=', 'supplies.id')
            ->where('orders.updated_at', '>=', $dates['start_date'])
            ->where('orders.updated_at', '<=', $dates['end_date'])
            ->where('orders.order_status', 'COMPLETED')
            ->groupBy('supply_id')
            ->orderBy('ordered_quantity', 'desc')
            ->limit(10)->get()->map(function ($val) {
                if (empty($val->img_url)) {
                    $val->img_url = 'https://via.placeholder.com/150';
                } else {
                    $val->img_url = Storage::url($val->img_url);
                }
                return $val;
            });
        return $ordered_items;
    }

    /**
     * Fetch overall supplies ordered
     *
     * @param array $requested_data data request
     * @return array
     */
    public static function get_overall_supplies_ordered($requested_data) {
        $ordered_items_query = self::selectRaw('SUM(quantity) as total_items_sold, SUM(total_cost) as total_revenue, supplies.name')
            ->join('orders', 'ordered_items.order_id', '=', 'orders.id')
            ->join('supplies', 'ordered_items.supply_id', '=', 'supplies.id')
            ->where('orders.order_status', 'COMPLETED')
            ->groupBy('supply_id');
        if (!empty($requested_data['start_date']) && !empty($requested_data['end_date'])) {
            $ordered_items_query->where('orders.updated_at', '>=', $requested_data['start_date'])
                ->where('orders.updated_at', '<=', $requested_data['end_date']);
        }
        return $ordered_items_query->get();
    }
}
