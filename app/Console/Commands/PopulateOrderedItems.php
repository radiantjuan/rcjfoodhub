<?php

namespace App\Console\Commands;

use App\Models\OrderedItems;
use App\Models\Orders;
use Illuminate\Console\Command;

class PopulateOrderedItems extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:PopulateOrderedItems';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates the ordered_items table from orders table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $latest_stored_order_id = OrderedItems::select('order_id')->orderBy('order_id', 'DESC')->first();

        if (!empty($latest_stored_order_id)) {
            $orders = Orders::select('id', 'ordered_items')->where('id', '>', $latest_stored_order_id->order_id)->get();
        } else {
            $orders = Orders::select('id', 'ordered_items')->get();
        }

        foreach ($orders as $order) {
            $ordered_items_json = json_decode($order->ordered_items, true);
            foreach ($ordered_items_json as $ordered_item_json) {
                OrderedItems::create([
                    'grams' => $ordered_item_json['grams'],
                    'price' => $ordered_item_json['price'],
                    'name' => $ordered_item_json['title'],
                    'quantity' => $ordered_item_json['quantity'],
                    'total_cost' => $ordered_item_json['total_cost'],
                    'supply_id' => $ordered_item_json['id'],
                    'order_id' => $order->id
                ]);
            }
            dump($order->id, 'success');
        }
        return 0;
    }
}
