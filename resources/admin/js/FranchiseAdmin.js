/**
 * Franchisee Admin JS
 * 
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 * 
 * @copyright RCJuan Food Hub 2021
 */

import $ from 'jquery';

class FranchiseAdmin {
    /**
     * Initilized events and behaviors
     *
     * @returns {void}
     */
    init() {
        $(window).on('load', () => this.onLoad())
    }

    /**
     * On Load will load datatable
     * @returns {void} 
     */
    onLoad() {
        $('.js-datatable').dataTable({
            columnDefs: {
                type: 'date',
                targets: [4,5]
            },
        });
    }
}

export default FranchiseAdmin