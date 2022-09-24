<x-admin.layout>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                @if (\Session::has('status'))
                    <div class="alert alert-success">
                        {{ \Session::get('status') }}
                    </div>
                @endif
                <form id="purchase_order_form" method="POST"
                    action="{{ route('purchase_orders.edit', ['id' => $order->id]) }}">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}

                    <div class="row justify-content-end mb-3">
                        <input type="button" class="btn btn-sm btn-success mx-2 col-3" name="order_status"
                            value="APPLY TO INVETORY" {{ $order->status == 'APPLIED' ? 'disabled' : '' }}>
                        <input type="button" name="status" value="Cancel"
                            data-action="{{ $route_model->get_route('cancel_purchase_order', $route_options['options']) }}"
                            class="btn btn-danger js-btn-cancel-purchase-order col-3 btn-sm mx-2" />
                    </div>
                    <div class="card bg-light mb-3">
                        <div class="card-header">
                            <strong>{{ __('PO Number: ') }} #{{ $order->po_number }}</strong>
                            <br>
                            @php
                                switch ($order->status) {
                                    case 'PENDING':
                                        $color_status = 'secondary';
                                        break;
                                    case 'CANCELLED':
                                        $color_status = 'danger';
                                        break;
                                    case 'TO BE RECEIVED':
                                        $color_status = 'primary';
                                        break;
                                    default:
                                        $color_status = 'success';
                                        break;
                                }
                            @endphp
                            <div class="badge badge-{{ $color_status }}">{{ $order->status }}</div>
                        </div>
                        <div class="card-body cart-items">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Supply Name</th>
                                        <th>SKU</th>
                                        <th>Inventory Qty</th>
                                        <th>Ordered Qty</th>
                                        <th>
                                            Received Qty
                                            @if ($order->status !== 'APPLIED')
                                                <div class="d-inline-block w-50 text-right"><input type="checkbox"
                                                        class="js-received-all text-right"> <small>(recieved
                                                        all)</small></div>
                                            @endif
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($supplies_list as $sl)
                                        <tr>
                                            <td>
                                                {{ $sl['name'] }}
                                            </td>
                                            <td>
                                                {{ $sl['sku'] }}
                                            </td>
                                            <td>
                                                <span class="js-current-qty"
                                                    data-name="current-qty-{{ $sl['id'] }}"
                                                    data-current-qty="{{ $sl['current_qty'] }}">{{ $sl['current_qty'] }}</span>
                                            </td>
                                            <td>
                                                <span class="js-ordered-qty"
                                                    data-name="current-qty-{{ $sl['id'] }}"
                                                    data-ordered-qty="{{ $sl['ordered_qty'] }}">{{ $sl['ordered_qty'] }}</span>
                                            </td>
                                            <td>
                                                <input type="number" data-name="current-qty-{{ $sl['id'] }}"
                                                    name="received_qty[{{ $sl['id'] }}]"
                                                    class="form-control js-qty" value="{{ $sl['received_qty'] }}"
                                                    data-id="{{ $sl['id'] }}"
                                                    {{ $order->status == 'APPLIED' ? 'disabled' : '' }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('admin.purchase_orders.purchase_order_cancel_modal')
</x-admin.layout>
