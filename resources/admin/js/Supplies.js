/**
 * Supplies JS
 * 
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 * 
 * @copyright RCJuan Food Hub 2021
 */
import $ from 'jquery';

class Supplies {
    /**
     * Initilized events and behaviors
     * 
     * @returns {void}
     */
    init() {
        $(window).on('load', () => {
            $('.js-datatable').dataTable({
                columns: [
                    {
                        data: 'id',
                        visible: false
                    },
                    {
                        data: 'Image',
                        sortable: false
                    },
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    {
                        data: 'color',
                        visible: false
                    }
                ],
                order: [[2,'asc']]
            });
        });
    }
}

export default Supplies;