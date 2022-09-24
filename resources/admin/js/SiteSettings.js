/**
 * Franchisee Admin JS
 * 
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 * 
 * @copyright RCJuan Food Hub 2021
 */

import $ from 'jquery';

class SiteSettings {
    /**
     * Initilized events and behaviors
     * 
     * @returns {void}
     */
    init() {
        $(window).on('load', () => {
            $('.js-datatable').dataTable({
                columnDefs: {
                    type: 'date',
                    targets: [4,5]
                },
            });
        });
    }
}

export default SiteSettings;