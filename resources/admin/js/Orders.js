/**
 * Orders JS
 * 
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 * 
 * @copyright RCJuan Food Hub 2021
 */
import $ from 'jquery';
import 'datatables.net-buttons-bs4';
import 'jszip';
class Orders {
    /**
     * Initilized events and behaviors
     * 
     * @returns {void}
     */
    init() {
        $(window).on('load', () => {
            let table = $('.js-datatable').DataTable({
                columns: [
                    {
                        data: 'id',
                        visible: false
                    },
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null
                ],
                columnDefs: {
                    type: 'date',
                    targets: [5]
                },
                order: [[5,'desc']]
            });
        });

        // var isSafari = !!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/);
        // var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        
        // if (isSafari && iOS) {
        //     alert("You are using Safari on iOS!");
        // } else if(isSafari) {
        //     alert("You are using Safari.");
        // }
        
        $(document).on('click', '.js-cancel-order', (e) => {
            $('#cancelOrderModal').modal();
            let orderId = $(e.currentTarget).data('id');
            $('#cancelOrderModal form').attr('action','/admin/orders/cancel-order/'+orderId);
            $('#cancelOrderModal input[name="cancel_reason"]').val('');
        });
        
        $('[data-toggle="tooltip"]').tooltip('show');
    }
}

export default Orders;