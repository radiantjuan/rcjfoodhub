<x-admin.layout>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                @if (\Session::has('status'))
                    <div class="alert alert-success">
                        {{ \Session::get('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if ($user_role == 'admin')
                    <form method="POST" action="{{ route('orders.edit', ['id' => $order->id]) }}">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="row justify-content-end">
                            <div class="col-2">
                                <input type="submit" class="btn btn-sm btn-success w-100 mb-3" name="order_status"
                                    value="COMPLETE">
                            </div>
                            <div class="col-2">
                                <input type="submit" class="btn btn-sm btn-primary w-100" name="order_status"
                                    value="PRINT RECEIPT">
                            </div>
                            <div class="col-2">
                                <input type="button" class="btn btn-sm btn-danger w-100 mb-3 js-cancel-order"
                                    name="order_status" value="CANCEL" data-id="{{ $order->id }}"
                                    {{ $order->order_status == 'CANCELLED' ? 'disabled' : '' }}>
                            </div>
                        </div>
                    </form>
                @endif
                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <strong>Order Number: #{{ $order->order_id }}</strong>
                        <br>
                        @php
                            switch ($order->order_status) {
                                case 'CANCELLED':
                                    $color_status = 'danger';
                                    $cancellation_reason = $order->order_cancellation->reason_for_cancellation;
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
                        <div class="badge badge-{{ $color_status }}">{{ $order->order_status }}</div>
                        @if (!empty($cancellation_reason))
                            <p>Reason for cancellation: <strong>{{ $cancellation_reason }}</strong></p>
                        @endif
                        <div class="shipping-info pt-3">
                            <table class="table table-sm table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            Branch:
                                        </td>
                                        <td>
                                            <strong>{{ !empty($order->franchisee->name) ? $order->franchisee->name : 'Branch Deleted' }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Payment:
                                        </td>
                                        <td>
                                            <strong>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</strong>
                                        </td>
                                    </tr>
                                    @if ($proof_of_payment)
                                        <tr>
                                            <td>
                                                Proof of Payment:
                                            </td>
                                            <td>
                                                <a href="{{ $proof_of_payment }}" target="_blank"><i
                                                        class="fa fa-external-link"></i> View Payment</a>
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($order->shipping_option !== 'pickup' && $order->shipping_option !== 'deliver_to_branch')
                                        <tr>
                                            <th colspan="2">Shipping Info:</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                Address:
                                            </td>
                                            <td>
                                                <strong>{{ $order->shipping_address_1 . ' ' . $order->shipping_address_2 }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                City:
                                            </td>
                                            <td>
                                                <strong>{{ $order->shipping_city }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Barangay:
                                            </td>
                                            <td>
                                                <strong>{{ $order->shipping_barangay }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Zip Code:
                                            </td>
                                            <td>
                                                <strong>{{ $order->shipping_zip_code }}</strong>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>
                                                Shipping Info:
                                            </td>
                                            <td>
                                                <strong>{{ str_replace('_', ' ', $order->shipping_option) }}</strong>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if (!empty($order->special_instructions))
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
                        @endif
                    </div>
                    <div class="card-body cart-items">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <th>Name</th>
                                <th>Qty</th>
                                <th>Discount</th>
                                <th>Subtotal</th>
                            </thead>
                            <tbody>
                                @foreach ($ordered_items as $ordered_item)
                                    <tr>
                                        <td>{{ $ordered_item['name'] }}</td>
                                        <td>{{ $ordered_item['qty'] }}</td>
                                        <td>₱{{ number_format($ordered_item['discount'], 2) }}</td>
                                        <td>₱{{ number_format($ordered_item['subtotal'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                        @if (!empty($promo_code))
                            <div class="promo-code-setup">
                                <div class="row">
                                    <div class="col"><b>Promo Code:</b><br></div>
                                    <div class="text-right col">{{ $promo_code['promo_code'] }}</div>
                                </div>
                                @empty(!$promo_code['promo_code_coverage'])
                                    @foreach (json_decode($promo_code['promo_code_coverage'], true) as $promo_code_coverage)
                                        <div class="row">
                                            <div class="col-7 col"><small>{{ $promo_code_coverage['title'] }}
                                                    (-₱{{ number_format($promo_code_coverage['total_discount'], 2) }})</small>
                                            </div>
                                            <div class="text-right col">
                                                <small><span>₱{{ number_format($promo_code_coverage['new_costs'], 2) }}</span></small>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="row">
                                        <div class="col"><strong>Total Discount</strong></div>
                                        <div class="text-right col">
                                            <span>-₱{{ number_format($promo_code['promo_code_total_discount'], 2) }}</span>
                                        </div>
                                    </div>
                                @endempty
                            </div>
                        @endif
                        <hr>
                        <div class="row">
                            <div class="col">
                                <div class="m-0 card-title h3">Total Amount: <span
                                        class="total-to-be-paid">₱{{ isset($promo_code['new_amount_to_be_paid']) ? number_format($promo_code['new_amount_to_be_paid'], 2) : number_format($order_total->total_to_be_paid, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (!$proof_of_payment)
                    <div class="card bg-light mb-3">
                        <div class="card-header">
                            Upload Proof of Payment
                        </div>
                        <div class="card-body">

                            <form method="POST" action="{{ route('orders.edit', ['id' => $order->id]) }}"
                                enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}

                                <div class="payment-upload">
                                    <div class="form-group">
                                        <label for="proof_of_payment">Upload Proof Of Payment *</label>
                                        <input type="file" name="proof_of_payment" id="">
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_transaction_number">Payment Transaction Number:</label>
                                        <input type="text" class="form-control" name="payment_transaction_number"
                                            placeholder="Enter Transaction"
                                            aria-describedby="shipping_zip_codeFeedback">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success btn-lg w-100">Submit</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('admin.orders.order-cancel-modal');
</x-admin.layout>
