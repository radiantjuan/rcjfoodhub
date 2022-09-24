/**
 * Categories JS
 * 
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 * 
 * @copyright RCJuan Food Hub 2021
 */
import $ from 'jquery';

class Categories {
    /**
     * Initilized events and behaviors
     * 
     * @returns {void}
     */
    init() {
        $(window).on('load', () => {
            $('.js-datatable').dataTable(); 
        });
    }
}

export default Categories;