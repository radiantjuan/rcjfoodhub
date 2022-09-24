/**
 * Supplies JS
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */
import {AxiosInstance} from './AxiosInstance';
import $ from 'jquery';

class AuditTrail {
    /**
     * Initilized events and behaviors
     *
     * @returns {void}
     */
    init(url) {
        AxiosInstance().get(url).then((res) => {
            if (res.data) {
                $('.change-logs-tbody').html('');
                let changeLogs = res.data.map((value) => {
                    let html = '<tr>';
                    html += '<td>' + value.date_of_change + '</td>';
                    html += '<td>' + value.user + '</td>';
                    html += '<td>' + value.message + '</td>';
                    html += '<td>' + value.item_changed + '</td>';

                    let changeHtml = '';
                    for (let changesIndex in value.changes) {
                        changeHtml += changesIndex + ': <span class="badge badge-danger">' + value.changes[changesIndex].previous + '</span> -> <span class="badge badge-success">' + value.changes[changesIndex].new_value + '</span> <br/>';
                    }

                    html += '<td>' + changeHtml + '</td>';
                    html += '</tr>';
                    return html;
                });
                $('.change-logs-tbody').html(changeLogs.join(''));
                $('.js-datatable-changelog').DataTable().destroy();
                $('.js-datatable-changelog').DataTable({
                    order: [[0, 'desc']],
                    columnDefs: {
                        type: 'date',
                        targets: [0]
                    },
                });
            }
        });
    }
}

export default AuditTrail;
