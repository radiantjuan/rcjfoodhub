/**
 * Promo Codes JS
 * 
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 * 
 * @copyright RCJuan Food Hub 2021
 */

import $ from 'jquery';
class PromoCodes {
    /**
     * initilized events and window on load
     * 
     * @returns {void}
     */
    init() {
        $(window).on('load', () => {
            if ($('select[data-js-event="on-coverage-change"] option:selected').val() == 'Individual Discount') {
                $('select[data-js-event="on-coverage-change-item-list"]').prop('disabled', false);
                $('select[data-js-event="items_exception"]').select2('val', 'testing');
                $('select[data-js-event="items_exception"]').prop('disabled', true);
            } else {
                $('select[data-js-event="on-coverage-change-item-list"]').prop('disabled', true);
                $('select[data-js-event="on-coverage-change-item-list"]').prop('disabled', true);
                $('select[data-js-event="items_exception"]').prop('disabled', false);
            }

            if ($('input[data-js-event="on-is_limited-check"]').prop('checked')) {
                $('input[data-js-event="number_of_use"]').prop('disabled', false);
            } else {
                $('input[data-js-event="number_of_use"]').prop('disabled', true);
            }

            if ($('input[name="use_end_date"]').prop('checked')) {
                $('input[name="end_date"]').prop('disabled', false);
            } else {
                $('input[name="end_date"]').prop('disabled', true);
            }
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
                    null,
                    null,
                    null
                ],
                columnDefs: {
                    type: 'date',
                    targets: [6]
                },
            });
        });

        //////EVENTS
        $(document).on('change', 'select[data-js-event="on-coverage-change"]', (event) => this.onCoverageChange(event));
        $(document).on('click', 'input[data-js-event="on-is_limited-check"]', (event) => this.onIsLimitedChange(event));
        $(document).on('click', 'input[name="use_end_date"]', (event) => this.onUseEndDateChange(event));
    }

    /**
     * On coverage change disable or enable the item list select form
     * 
     * @param {EventTarget} event
     * 
     * @return {void}
     */
    onCoverageChange(event) {
        if ($(event.currentTarget).val() == 'Individual Discount') {
            $('select[data-js-event="on-coverage-change-item-list"]').prop('disabled', false);
            $('select[data-js-event="items_exception"]').select2('val', 'testing');
            $('select[data-js-event="items_exception"]').prop('disabled', true);
        } else {
            $('select[data-js-event="on-coverage-change-item-list"]').select2('val', 'testing');
            $('select[data-js-event="on-coverage-change-item-list"]').prop('disabled', true);
            $('select[data-js-event="items_exception"]').prop('disabled', false);
        }
    }

    /**
     * On islimited change tick box will disable or enable the number of use text box
     * 
     * @param {EventTarget} event 
     * 
     * @return {void}
     */
    onIsLimitedChange(event) {
        if ($(event.currentTarget).prop('checked')) {
            $('input[data-js-event="number_of_use"]').prop('disabled', false);
        } else {
            $('input[data-js-event="number_of_use"]').prop('disabled', true);
        }
    }

    /**
     * on use end date checkbox checked, will enable and disable enddate date box
     * 
     * @param {EventTarget} event 
     * 
     * @return {void}
     */
    onUseEndDateChange(event) {
        if ($(event.currentTarget).prop('checked')) {
            $('input[name="end_date"]').prop('disabled', false);
        } else {
            $('input[name="end_date"]').prop('disabled', true);
        }
    }
}

export default PromoCodes;