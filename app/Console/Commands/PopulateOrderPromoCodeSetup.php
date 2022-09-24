<?php

namespace App\Console\Commands;

use App\Models\OrderPromoCodeSetup;
use App\Models\Orders;
use App\Models\OrderTotals;
use Illuminate\Console\Command;

class PopulateOrderPromoCodeSetup extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:PopulateOrderPromoCodeSetup';

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
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $latest_stored_order_id = OrderPromoCodeSetup::select('order_id')->orderBy('order_id', 'DESC')->first();

        if (!empty($latest_stored_order_id)) {
            $orders = Orders::select('id', 'promo_code_setup')->where('id', '>', $latest_stored_order_id->order_id)->get();
        } else {
            $orders = Orders::select('id', 'promo_code_setup')->get();
        }

        foreach ($orders as $order) {
            $order_total_json = json_decode($order->promo_code_setup, true);
            if ($order_total_json) {
                OrderPromoCodeSetup::create([
                    'promo_code' => $order_total_json['promo_code'],
                    'promo_code_id' => $order_total_json['promo_code_id'],
                    'order_id' => $order->id,
                    'promo_code_coverage' => json_encode($order_total_json['promo_code_coverage']),
                    'promo_code_exceptions' => json_encode($order_total_json['promo_code_exceptions']),
                    'new_amount_to_be_paid' => $order_total_json['new_amount_to_be_paid'],
                    'promo_code_total_discount' => $order_total_json['promo_code_total_discount'],
                    'previous_amount_to_be_paid' => $order_total_json['previous_amount_to_be_paid'],
                ]);
                dump($order->id, 'success');
            }

        }

        return 0;

    }
}
