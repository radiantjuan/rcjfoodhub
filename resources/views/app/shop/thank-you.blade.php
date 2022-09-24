<x-admin.layout>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card bg-light mb-3">
                    <div class="card-header">
                        Thank you for ordering! <br>
                        <strong>Order Number: #{{ $order->order_id }}</strong>
                        <br>
                        @php
                            switch ($order->order_status) {
                                case 'CANCELLED':
                                    $color_status = 'danger';
                                    break;
                                case 'COMPLETED':
                                    $color_status = 'success';
                                    break;
                                case 'UNDELIVERED-UNPAID':
                                    $color_status = 'warning';
                                    break;
                                default:
                                    $color_status = 'primary';
                                    break;
                            }
                        @endphp
                        <div class="badge badge-{{$color_status}}">{{ $order->order_status }}</div>
                        <div class="shipping-info pt-3">
                            @if ($order->shipping_option !== 'pickup' && $order->shipping_option !== 'deliver_to_branch')
                            Shipping Info:
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-4">
                                        <strong>Address:</strong>
                                    </div>
                                    <div class="col">
                                        {{ $order->shipping_address_1 . ' ' . $order->shipping_address_2 }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <strong>City:</strong>
                                    </div>
                                    <div class="col">
                                        {{ $order->shipping_city }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <strong>Barangay:</strong>
                                    </div>
                                    <div class="col">
                                        {{ $order->shipping_barangay }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <strong>Zip Code:</strong>
                                    </div>
                                    <div class="col">
                                        {{ $order->shipping_zip_code }}
                                    </div>
                                </div>
                            </div>
                            @else
                            Shipping Info:
                                <strong>{{str_replace('_', ' ', $order->shipping_option)}}</strong>
                            @endif
                        </div>
                        <div class="shipping-info pt-3">
                            Special Instruction:
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col">
                                        <strong>{{ $order->special_instructions }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body cart-items">
                        @foreach ($ordered_items as $ordered_item)
                            <div class="mb-2 media"><img width="64" height="64" class="mr-3"
                                    src="{{ $ordered_item->img_url ? $ordered_item->img_url : 'https://via.placeholder.com/150' }}"
                                    alt="Generic placeholder">
                                <div class="media-body">
                                    <h5>{{ $ordered_item->quantity }}x {{ $ordered_item->title }} </h5>
                                    <div class="row">
                                        <div class="col">{{ $ordered_item->grams }} gram(s)</div>
                                        <div class="text-right col">
                                            <span>₱{{ number_format($ordered_item->price, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">Cost: </div>
                                        <div class="text-right col">
                                            <span>₱{{ number_format($ordered_item->total_cost, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="card-footer">
                        <div class="cart-total">
                            <div class="row">
                                <div class="col"><b>Total Item(s):</b></div>
                                <div class="text-right col">{{ $order_total->total_items }}</div>
                            </div>
                            <div class="row">
                                <div class="col"><b>Total Cost(s):</b></div>
                                <div class="text-right col">
                                    <span>₱{{ number_format($order_total->total_costs, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        @if (isset($promo_code))
                            <div class="promo-code-setup">
                                <div class="row">
                                    <div class="col"><b>Promo Code:</b><br></div>
                                    <div class="text-right col">{{ $promo_code->promo_code }}</div>
                                </div>
                                @foreach ($promo_code->promo_code_coverage as $promo_code_coverage)
                                    <div class="row">
                                        <div class="col-7 col"><small>{{ $promo_code_coverage->title }}
                                                (-₱{{ number_format($promo_code_coverage->total_discount, 2) }})</small>
                                        </div>
                                        <div class="text-right col">
                                            <small><span>₱{{ number_format($promo_code_coverage->new_costs, 2) }}</span></small>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="row">
                                    <div class="col"><strong>Total Discount</strong></div>
                                    <div class="text-right col">
                                        <span>-₱{{ number_format($promo_code->promo_code_total_discount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <hr>
                        <div class="row">
                            <div class="col">
                                <div class="m-0 card-title h3">Total Amount: <span
                                        class="total-to-be-paid">₱{{ isset($promo_code->new_amount_to_be_paid) ? number_format($promo_code->new_amount_to_be_paid, 2) : number_format($order_total->total_to_be_paid, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
