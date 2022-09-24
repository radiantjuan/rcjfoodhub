<x-admin.layout>
    <div class="col-12 col-lg-6">
        <h1>{{ __($page_title) }}</h1>
        <div class="pt-4">
            <div class="card w-100">
                <div class="card-header">
                    {{ __($page_sub_title) }}
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (\Session::has('status'))
                        <div class="alert alert-success">
                            {{ \Session::get('status') }}
                        </div>
                    @endif
                    @if (!empty($purchase_orders_status))
                        <div>
                            Status: {!! $purchase_orders_status !!} </br>
                            @if (!empty($purchase_orders_reason_for_cancelling))
                                Reason: {{ $purchase_orders_reason_for_cancelling }}
                            @endif
                        </div>
                    @endif
                    <form id="purchaseOrderForm" method="POST"
                        action="{{ $route_model->get_route($route_options['name'], $route_options['options']) }}"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @if ($route_options['name'] == 'edit')
                            {{ method_field('PUT') }}
                        @endif
                        
                        @foreach ($fields as $field)
                            {{ $field }}
                        @endforeach

                        @if ((!empty($po_status) && $po_status !== 'CANCELLED') || (empty($po_status) && $route_options['name'] == 'add'))
                            <input type="button" name="status" value="Save" class="btn btn-success btn-save" {{$route_options['name'] == 'add' && !app('request')->input('stock_warning_purchase') ? 'disabled' : ''}}>
                            @if ($route_options['name'] == 'edit')
                                <input type="submit" name="status" value="Receive" class="btn btn-primary btn-recieve">
                                <input type="button" name="status" value="Cancel"
                                    data-action="{{ $route_model->get_route('cancel_purchase_order', $route_options['options']) }}"
                                    class="btn btn-danger js-btn-cancel-purchase-order">
                            @endif
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    @include('admin.purchase_orders.purchase_order_cancel_modal')
</x-admin.layout>
