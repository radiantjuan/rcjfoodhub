<form method="POST" action="{{ route('orders.edit', ['id' => $order_id]) }}">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <div class="row justify-content-end">
        @if ($order_status !== 'COMPLETED')
        <div>
            <button type="submit" class="btn btn-sm btn-success js-btn-status" name="order_status" value="COMPLETE"
                data-toggle="tooltip" data-placement="top" title="Complete"><i class="fa fa-check"></i></button>
        </div>
        @endif
        <div>
            <button type="submit" class="btn btn-sm btn-primary js-btn-status" name="order_status" value="PRINT RECEIPT"
                data-toggle="tooltip" data-placement="top" title="Print Receipt"><i class="fa fa-print"></i></button>
        </div>
        @if ($order_status !== 'CANCELLED')
            <div>
                <button type="button" class="btn btn-sm btn-danger js-cancel-order js-btn-status" name="order_status"
                    value="CANCEL" data-id="{{ $order_id }}" data-toggle="tooltip" data-placement="top" title="Cancel"><i
                        class="fa fa-times"></i></button>
            </div>
        @endif
    </div>
</form>
