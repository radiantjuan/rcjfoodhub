<div class="modal fade" id="cancelPurchaseOrderModal" tabindex="-1" role="dialog"
    aria-labelledby="cancelPurchaseOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="modal-body">
                    <label for="cancel_reason">{{ __('Reason for cancelling') }}</label>
                    <input type="text" class="form-control" name="cancel_reason" placeholder="(e.g.) Duplicate Order"
                        required>
                    <input type="hidden" name="order_status" value="CANCEL">
                    <input type="hidden" name="received_qty" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Cancel Purchase Order') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
