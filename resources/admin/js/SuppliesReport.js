/**
 * Supplies Report JS
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */
import $ from 'jquery';
import {AxiosInstance} from "./AxiosInstance";


const SuppliesReport = () => {
    let dtable;
    /**
     * Initilized events and behaviors
     *
     * @returns {void}
     */
    const suppliesReportInit = () => {
        $(window).on('load', () => {
            _populateDataTable();
            _fetch_top_ten();
        });

        $(document).on('change', '#showAllSupplies', (event) => {
            $('.js-supplies-table').DataTable().clear();
            $('.js-supplies-table').DataTable().destroy();
            if (!event.target.checked) {
                _populateDataTable();
                $('.js-overall-supplies-dates-container').removeClass('d-none');
            } else {
                _populateDataTable();
                $('.js-overall-supplies-dates-container').addClass('d-none');
            }
        });
        $(document).on('click', '.js-submit-date-overall-supplies', () => {
            $('.js-supplies-table').DataTable().clear();
            $('.js-supplies-table').DataTable().destroy();
            _populateDataTable();
        });
        $(document).on('click', '.js-submit-date-top-ten', _fetch_top_ten);
    }

    const _populateDataTable = () => {
        const auth_token = document.getElementById('auth_token');
        const start_date = $('#startDateOverallSupplies').val();
        const end_date = $('#endDateOverallSupplies').val();
        const b_show_all = $('#showAllSupplies').prop('checked');
        let url = `/api/reports/supplies_report/get_overall_supplies_ordered?start_date=${start_date}&end_date=${end_date}`;
        if (b_show_all) {
            url =    '/api/reports/supplies_report/get_overall_supplies_ordered?show_all=1';
        }

        $('.js-supplies-table').DataTable({
            processing: true,
            ajax: {
                url: url,
                headers: {
                    Authorization: 'Bearer ' + auth_token.value
                }
            },
            columns: [
                {
                    data: 'name'
                },
                {
                    data: 'total_items_sold',
                    render(data, type) {
                        if (type === 'display') {
                            return new Intl.NumberFormat().format(data);

                        }
                        return data;
                    }
                },
                {
                    data: 'total_revenue',
                    render(data, type) {
                        if (type === 'display') {
                            return new Intl.NumberFormat('en', {style: 'currency', currency: 'PHP'}).format(data)
                        }
                        return data;
                    }
                }
            ],
            pagination: true
        });
    }

    /**
     * Fetch top
     */
    const _fetch_top_ten = async () => {
        const js_top_ten_supplies_container = $('.js-top-ten-supplies');
        try {
            js_top_ten_supplies_container.html('Loading...');
            $('.is-invalid').removeClass('is-invalid');
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();
            const top_ten_report = (await AxiosInstance().get(`/api/reports/supplies_report/get_top_ten_supplies?start_date=${startDate}&end_date=${endDate}`)).data;
            const top_ten_report_html = top_ten_report.map(function (val, key) {
                const supplies_container_copy = $('.js-top-supplies-container-copy').clone();

                supplies_container_copy.find('.js-top-supplies-img').attr('src', val.img_url);
                supplies_container_copy.find('.js-top-supplies-number').text(key + 1);
                supplies_container_copy.find('.js-top-supplies-title').text(val.title);
                supplies_container_copy.find('.js-top-supplies-overall-purchased').text(val.ordered_quantity);
                supplies_container_copy.removeClass('d-none');
                supplies_container_copy.removeClass('js-top-supplies-container-copy');
                return supplies_container_copy;
            });
            js_top_ten_supplies_container.html(top_ten_report_html);
            // console.log();
        } catch (error) {
            if (error.response.status === 422) {
                if (typeof error.response.data.errors !== 'undefined') {
                    for (let field_name in error.response.data.errors) {
                        $(`input[name="${field_name}"]`).addClass('is-invalid');
                        $(`.${field_name}_feedback`).text(error.response.data.errors[field_name][0]);
                    }
                }
            } else if (error.response.status === 404) {
                js_top_ten_supplies_container.html('No record found');
            }
        }
    }

    return {
        suppliesReportInit: suppliesReportInit
    };
}

export default SuppliesReport;
