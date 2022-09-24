<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <div class="modal-body">
                    {{ __('Are you sure you want to delete?') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="changeLogsModal" tabindex="-1" role="dialog" aria-labelledby="changeLogsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Logs</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body change-logs-content">
                <table class="table js-datatable-changelog">
                    <thead>
                        <th>Date of change</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Item Changed</th>
                        <th>Changes</th>
                    </thead>
                    <tbody class="change-logs-tbody">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('admin.purchase_orders.purchase_order_cancel_modal')