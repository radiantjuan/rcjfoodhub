/**
 * Franchisee Orders JS
 * 
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 * 
 * @copyright RCJuan Food Hub 2021
 */
 import $ from 'jquery';

 class FranchiseeOrders {
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
                    targets: [5]
                },
             });
         });
     }
 }
 
 export default FranchiseeOrders;