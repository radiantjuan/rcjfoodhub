/**
 * Franchisee shipping JS
 * 
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 * 
 * @copyright RCJuan Food Hub 2021
 */
import $ from 'jquery';

class FranchiseShipping {

    /**
     * Initilized events and behaviors
     * 
     * @returns {void}
     */
    init() {
        $(window).on('load', () => this.onLoad())

        $(document).on('change', '#shippingOption', (event) => {
            if ($(event.currentTarget).val() == 'pickup' || $(event.currentTarget).val() == 'deliver_to_branch') {
                $('.delivery-container').addClass('d-none');
            } else {
                $('.delivery-container').removeClass('d-none');
            }
        });

        $(document).on('change', '#paymentOption', (event) => {
            if ($(event.currentTarget).val() == 'cash') {
                $('.payment-upload').addClass('d-none');
                $('.alert-pay-later').addClass('d-none');
            } else if ($(event.currentTarget).val() == 'pay_later') {
                $('.payment-upload').addClass('d-none');
                $('.alert-pay-later').removeClass('d-none');

            } else {
                $('.payment-upload').removeClass('d-none');
                $('.alert-pay-later').addClass('d-none');
            }
        });
    }
    /**
     * On Load will get all the ordered contents in session storage
     * 
     * @returns {void}
     */
    onLoad() {
        const currencyFormat = {
            format: (value) => {
                return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(value)
            }
        }
        const cart_items = (sessionStorage.getItem('cart_items') != '') ? (sessionStorage.getItem('cart_items')) : '';
        const cart_total = (sessionStorage.getItem('cart_total') != '') ? (sessionStorage.getItem('cart_total')) : '';
        const promo_code_setup = (sessionStorage.getItem('promo_code_setup') != '') ? (sessionStorage.getItem('promo_code_setup')) : '';

        if(!cart_items) {
            window.location.href = '/franchisee/orders';
        }

        $('[name="cart_items"]').val(cart_items)
        $('[name="cart_total"]').val(cart_total)
        $('[name="promo_code_setup"]').val(promo_code_setup)
        const json_cart_items = JSON.parse(cart_items);
        const mapped_cart_items = json_cart_items.map((item) => {
            return '\
                <div class="mb-2 media"><img width="64" height="64" class="mr-3"\
                    src="'+ ((item.img_url !== null) ? item.img_url : 'https://via.placeholder.com/150') + '" alt="Generic placeholder">\
                    <div class="media-body">\
                        <h5>'+ item.quantity + 'x ' + item.title + '</h5>\
                        <div class="row">\
                            <div class="col">'+ item.grams + ' gram(s)</div>\
                            <div class="text-right col"><span>'+ currencyFormat.format(item.price) + '</span></div>\
                        </div>\
                        <div class="row">\
                            <div class="col">Cost: </div>\
                            <div class="text-right col"><span>'+ currencyFormat.format(item.total_cost) + '</span></div>\
                        </div>\
                    </div>\
                </div>\
            '
        });
        $('.cart-items').html(mapped_cart_items.join(' '));

        const json_cart_total = JSON.parse(cart_total);
        $('.cart-total').html('\
            <div class="row">\
                <div class="col"><b>Total Item(s):</b></div>\
                <div class="text-right col">'+ json_cart_total.total_items + '</div>\
            </div>\
            <div class="row">\
                <div class="col"><b>Total Cost(s):</b></div>\
                <div class="text-right col"><span>'+ currencyFormat.format(json_cart_total.total_costs) + '</span></div>\
            </div>\
        ');

        const json_promo_code_setup = JSON.parse(promo_code_setup);
        if (json_promo_code_setup) {
            const mapped_coverage = json_promo_code_setup.promo_code_coverage.map((val) => {
                return '\
                    <div class="row"> \
                        <div class="col-7 col"><small>'+ val.title + ' (-' + currencyFormat.format(val.total_discount) + ')</small></div> \
                        <div class="text-right col"><small><span>'+ currencyFormat.format(val.new_costs) + '</span></small></div> \
                    </div> \
                ';
            });
            $('.promo-code-setup').html('\
                <div class="row"> \
                    <div class="col"><b>Promo Code:</b><br></div> \
                    <div class="text-right col">'+ json_promo_code_setup.promo_code + '</div> \
                </div>  \
                '+ mapped_coverage.join('') + ' \
                <div class="row"> \
                    <div class="col"><strong>Total Discount</strong></div> \
                    <div class="text-right col"><span>-'+ currencyFormat.format(json_promo_code_setup.promo_code_total_discount) + '</span></div> \
                </div> \
            ');
            $('.total-to-be-paid').html(currencyFormat.format(json_promo_code_setup.new_amount_to_be_paid));
        } else {

            $('.total-to-be-paid').html(currencyFormat.format(json_cart_total.total_to_be_paid));
        }
    }
}

export default FranchiseShipping