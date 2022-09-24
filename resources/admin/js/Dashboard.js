/**
 * Dashboard JS
 * 
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 * 
 * @copyright RCJuan Food Hub 2021
 */
import $ from 'jquery';

class Dashboard {
    /**
     * Initilized events and behaviors
     * 
     * @returns {void}
     */
    init() {
        $(window).on('load', () => {
            const hash = window.location.hash;
            let activeId = false;
            if (hash) {
                // Fragment exists
                activeId = hash.substring(1);
            }

            if (activeId) {
                $('#announcementModal').modal();
                $.ajax({
                    url: '/admin/announcements/'+activeId,
                    type: 'GET',
                    success: (res) => {
                        $('#announcementModal .modal-title').text(res.title);
                        $('#announcementModal .sub-title').text('Date Published: ' + res.date_published);
                        $('#announcementModal .announcement-content').html(res.content);  
                    }
                })
            }
            
            $('.js-announcement-read-more').on('click',(e) => {
                let id = $(e.target).data('id');
                $('#announcementModal').modal();
                $.ajax({
                    url: '/admin/announcements/'+id,
                    type: 'GET',
                    success: (res) => {
                        $('#announcementModal .modal-title').text(res.title);
                        $('#announcementModal .sub-title').text('Date Published: ' + res.date_published);
                        $('#announcementModal .announcement-content').html(res.content);  
                    }
                })
            });

            $('.btn-filter').on('click', (e) => {
                $("#announcementFilter").modal();
            });

            $('#announcementModal').on('hide.bs.modal', function (e) {
                window.location.hash = '';
              })
        });
    }
}

export default Dashboard;