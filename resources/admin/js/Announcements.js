/**
 * Categories JS
 * 
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 * 
 * @copyright RCJuan Food Hub 2021
 */
import $ from 'jquery';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

class Announcements {
    /**
     * Initilized events and behaviors
     * 
     * @returns {void}
     */
    init() {
        $(window).on('load',() => {
            if (document.querySelector('#content')) {
                ClassicEditor.create(document.querySelector('#content')).then(editor => {
                    console.log(editor);
                })
                .catch(error => {
                    console.error(error);
                });
            } else {
                let table = $('.js-datatable').DataTable({
                    columns: [
                        {
                            data: 'id',
                            visible: false
                        },
                        null,
                        null,
                        null,
                        null,
                        null
                    ],
                    columnDefs: {
                        type: 'date',
                        targets: [2,3]
                    },
                    order: [[5,'desc']]
                });
            }
        })
    }
}

export default Announcements;