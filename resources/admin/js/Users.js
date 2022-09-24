/**
 * Users Admin JS
 * 
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 * 
 * @copyright RCJuan Food Hub 2021
 */
import $ from 'jquery';

class Users {
    /**
     * Initilized events and behaviors
     * 
     * @returns {void}
     */
    init() {
        $(window).on('load', () => {
            $('.js-datatable').dataTable({
                columns: [
                    null,
                    null,
                    null,
                    null,
                    {
                        data: 'Created Date',
                        render: (data, type, row) => {
                            if (type == 'sort') {
                                return parseInt(data);
                            }
                            const milliseconds = parseInt(data) * 1000
                            const dateObject = new Date(milliseconds)
                            const humanDateFormat = dateObject.toLocaleString() //2019-12-9 10:30:15
                            return humanDateFormat;
                        }
                    },
                    {
                        data: 'Updated Date',
                        render: (data, type, row) => {
                            if (type == 'sort') {
                                return parseInt(data);
                            }
                            const milliseconds = parseInt(data) * 1000
                            const dateObject = new Date(milliseconds)
                            const humanDateFormat = dateObject.toLocaleString() //2019-12-9 10:30:15
                            return humanDateFormat;
                        }
                    },
                    null
                ]
            });
        });
    }
}

export default Users;