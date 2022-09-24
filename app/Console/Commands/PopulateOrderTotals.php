<?php

namespace App\Console\Commands;

use App\Models\Orders;
use App\Models\OrderTotals;
use Illuminate\Console\Command;

class PopulateOrderTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:PopulateOrderTotals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $latest_stored_order_id = OrderTotals::select('order_id')->orderBy('order_id', 'DESC')->first();

        if (!empty($latest_stored_order_id)) {
            $orders = Orders::select('id', 'order_total')->where('id', '>', $latest_stored_order_id->order_id)->get();
        } else {
            $orders = Orders::select('id', 'order_total')->get();
        }

        foreach ($orders as $order) {
            $order_total_json = json_decode($order->order_total, true);
            OrderTotals::create([
                'total_costs' => $order_total_json['total_costs'],
                'total_items' => $order_total_json['total_items'],
                'total_to_be_paid' => $order_total_json['total_to_be_paid'],
                'order_id' => $order->id,
            ]);
            dump($order->id, 'success');
        }
        return 0;
    }
}
