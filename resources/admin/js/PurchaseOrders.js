/**
 * Purchase Orders JS
 * 
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 * 
 * @copyright RCJuan Food Hub 2021
 */

import $ from 'jquery';
import { random } from 'lodash-es';
class PurchaseOrders {
    /**
    * Initialized events
    * 
    * @returns {void} 
    */
    init() {
        $(window).on('load', () => this.onLoad());
        $(document).on('click', '.js-btn-add-supply', (e) => this.addSupply(e));
        $(document).on('click', '.js-btn-delete-supply', (e) => this.deleteSupply(e));
        $(document).on('click', '.btn-save', (e) => this.savePO());
        $(document).on('keyup', '.js-qty', (e) => this.addQuantityOnKeyUp(e));
        $(document).on('click', '[name="order_status"]', (e) => this.changeOrderStatus());

        $(document).on('change', '.supply-list-container .js-supply-dd-class', (e) => {
            let value = $(e.currentTarget).find('option:selected').val();
            let arrayValues = [];
            let fieldId = $(e.currentTarget).data('field-id');
            let sku = $(e.currentTarget).find('option:selected').data('sku');
            let currentQuantity = $(e.currentTarget).find('option:selected').data('current-quantity');
            let stockWarning = $(e.currentTarget).find('option:selected').data('stock-warning');

            if ($('.supply-list-container .js-supply-dd-class').length > 0) {
                $('.supply-list-container .js-supply-dd-class option:selected').each((key, element) => {
                    arrayValues.push($(element).val());
                });
            }

            if (this.find_in_array(arrayValues, value).length > 1) {
                alert('item has been chosen already!');
                $(e.currentTarget).find('option:selected').prop('selected', false);
                $('.btn-save').prop('disabled', true);
                $('.js-span-current-quantity[data-field-id="' + fieldId + '"]').removeClass('text-danger');
                $('.js-span-current-quantity[data-field-id="' + fieldId + '"]').text(0);
            } else if (this.find_in_array(arrayValues, "0").length >= 1) {
                $('.btn-save').prop('disabled', true);
            } else {
                $('.btn-save').prop('disabled', false);
            }

            $('.js-span-sku[data-field-id="' + fieldId + '"]').text(sku);
            $('.js-span-current-quantity[data-field-id="' + fieldId + '"]').text(currentQuantity);

            if (currentQuantity < stockWarning) {
                $('.js-span-current-quantity[data-field-id="' + fieldId + '"]').addClass('text-danger');
            } else {
                $('.js-span-current-quantity[data-field-id="' + fieldId + '"]').removeClass('text-danger');
            }

            
        });

        $(document).on('click', '.js-btn-cancel-purchase-order', (e) => {
            let action = $(e.currentTarget).data('action');
            $('#cancelPurchaseOrderModal').modal();
            $('#cancelPurchaseOrderModal form').attr('action', action);
            let received_qty_value = [];
            $('.js-qty').each((key, val) => {
                received_qty_value.push({
                    id: $(val).data('id'),
                    value: $(val).val()
                });
            });

            $('#cancelPurchaseOrderModal form input[name="received_qty"]').val(JSON.stringify(received_qty_value));

        });

        $(document).on('click', '.js-received-all', (e) => {
            if ($(e.currentTarget).prop('checked')) {
                $('.js-ordered-qty').each((key, val) => {
                    let name = $(val).data('name');
                    let orderedQty = $(val).data('ordered-qty');
                    $('.js-qty[data-name="' + name + '"]').val(orderedQty);
                });
            } else {
                $('.js-ordered-qty').each((key, val) => {
                    let name = $(val).data('name');
                    $('.js-qty[data-name="' + name + '"]').val('');
                });
            }

            $('.js-qty').trigger('keyup');
        });
    }

    /**
    * initialize table to use datatable
    *
    * @return {void}
    */
    onLoad() {
        $('.js-datatable').dataTable({
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
            ],
            columnDefs: {
                type: 'date',
                targets: [4,5]
            },
            order: [[6, 'desc']]
        });

        if ($('.supply-list-container .js-supply-dd-class').length > 0) {
            $('.supply-list-container .js-supply-dd-class').each((key, element) => {
                let fieldId = $(element).data('field-id');
                let sku = $(element).find('option:selected').data('sku');
                let currentQuantity = $(element).find('option:selected').data('current-quantity');
                let stockWarning = $(element).find('option:selected').data('stock-warning');

                $('.js-span-sku[data-field-id="' + fieldId + '"]').text(sku);
                $('.js-span-current-quantity[data-field-id="' + fieldId + '"]').text(currentQuantity);

                if (currentQuantity < stockWarning) {
                    $('.js-span-current-quantity[data-field-id="' + fieldId + '"]').addClass('text-danger');
                } else {
                    $('.js-span-current-quantity[data-field-id="' + fieldId + '"]').removeClass('text-danger');
                }

            })
        }

        if ($('#status').val() == 'CANCELLED') {
            $('.js-btn-add-supply').addClass('d-none');
            $('.js-btn-delete-supply').addClass('d-none');

        }
    }

    /**
    * When add supply button in clicked
    * 
    * @param {EventTarget} e 
    * 
    * @returns {void}
    */
    addSupply(e) {
        var clone = $('.supply-list-container-copy').first().clone();
        let fieldId = this.makeid(5);
        clone.removeClass('d-none');
        clone.removeClass('supply-list-container-copy');
        clone.addClass('supply-list-container');
        clone.find('.js-btn-delete-supply').removeClass('d-none');
        clone.find('[name="supply_quantity[not_included]').attr('name', 'supply_quantity[]');
        clone.find('[name="supplies_list[not_included]').attr('data-field-id', fieldId);
        clone.find('.js-span-sku').attr('data-field-id', fieldId);
        clone.find('.js-span-current-quantity').attr('data-field-id', fieldId);
        clone.find('[name="supplies_list[not_included]').attr('name', 'supplies_list[]');
        clone.find('[name="supply_quantity[]"]').removeClass('is-invalid').val(0);
        clone.find('[name="supply_quantity[]"]').addClass('current-active-items').val(0);
        clone.find('[name="supplies_list[]"] option').prop('selected', false);

        $(e.currentTarget).before(clone);
        $('.btn-save').prop('disabled', true);
        $('.btn-recieve').prop('disabled', true);
    }

    /**
    * When delete button is clicked the supply will be deleted
    *
    * @param {EventTarget} e
    * 
    * @returns {void} 
    */
    deleteSupply(e) {
        $(e.target).parent().parent().remove();
        let arrayValues = [];
        if ($('.supply-list-container .js-supply-dd-class').length > 0) {
            $('.supply-list-container .js-supply-dd-class option:selected').each((key, element) => {
                arrayValues.push($(element).val());
            });
        }

        if (arrayValues.length <= 0) {
            $('.btn-save').prop('disabled', true);
            $('.btn-recieve').prop('disabled', true);
        } else if (this.find_in_array(arrayValues, "0").length >= 1) {
            $('.btn-save').prop('disabled', true);
            $('.btn-recieve').prop('disabled', true);
        } else {
            $('.btn-save').prop('disabled', false);
        }

        

    }

    /**
    * on form save
    * 
    * @param {EventTarget} e
    * 
    * @returns {void}
    */
    savePO() {
        //validate
        let is_valid = true;
        $('.current-active-items').each((key, val) => {
            if ($(val).val() == '') {
                is_valid = false;
                $(val).addClass('is-invalid');
                console.log(is_valid);
            }

        });

        if ($('#warehouse').val() == '') {
            is_valid = false;
            $('#warehouse').addClass('is-invalid');
        }

        if (is_valid) {
            $("#purchaseOrderForm").trigger('submit');
        }
    }

    /**
    * on quantity change it will manipulate the value and text of current QTY
    * 
    * @param {EventTarget} e
    * 
    * @returns {void}
    */
    addQuantityOnKeyUp(e) {
        const currentQty = parseInt($('span.js-current-qty[data-name="' + $(e.target).data('name') + '"]').data('current-qty'));
        const receivedQty = parseInt($(e.target).val());
        const totalQty = currentQty + receivedQty;

        if ($(e.target).val() !== '') {
            $('span.js-current-qty[data-name="' + $(e.target).data('name') + '"]').attr('data-current-qty', totalQty);
            $('span.js-current-qty[data-name="' + $(e.target).data('name') + '"]').text(totalQty);
        } else {
            $('span.js-current-qty[data-name="' + $(e.target).data('name') + '"]').attr('data-current-qty', currentQty);
            $('span.js-current-qty[data-name="' + $(e.target).data('name') + '"]').text(currentQty);
        }
    }

    /**
    * on order status change it will validate the quantity before submit
    * 
    * @returns {void}
    */
    changeOrderStatus() {
        let is_valid = true;
        $('.js-qty').each((key, val) => {
            $(val).removeClass('is-invalid');
            if ($(val).val() == '') {
                is_valid = false;
                $(val).addClass('is-invalid');
            }
        });

        if (is_valid) {
            $('#purchase_order_form').trigger('submit');
        }
    }

    /**
    * Random generation of characters for ID
    * @param {int} length 
    * @returns {String}
    */
    makeid(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() *
                charactersLength));
        }
        return result;
    }

    /**
    * Find in Array
    * @param {Array} arrayValues 
    * @returns {Array}
    */
    find_in_array(arrayValues, value) {
        let indexes = [];
        let i = -1;
        while ((i = arrayValues.indexOf(value, i + 1)) != -1) {
            indexes.push(i);
        }
        return indexes
    }
}

export default PurchaseOrders