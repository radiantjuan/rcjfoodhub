/**
 * Categories JS
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */
import $ from 'jquery';
// import Chart from 'chart.js/auto';

const OverAllSalesReport = () => {
    /**
     * Initilized events and behaviors
     *
     * @returns {void}
     */
    const OverAllSalesReportInit = () => {
        $(document).ready(() => {
            const auth_token = document.getElementById('auth_token');
            let url = '/api/reports/get_overall_sales_report';
            $('.js-overallsales-table').DataTable({
                processing: true,
                ajax: {
                    url: url,
                    headers: {
                        Authorization: 'Bearer ' + auth_token.value
                    }
                },
                columns: [
                    {data: 'year'},
                    {
                        data: 'jan',
                        render(data, type) {
                            return _currencyFormat(data);
                        }
                    },
                    {
                        data: 'feb',
                        render(data, type) {
                            return _currencyFormat(data);
                        }
                    },
                    {
                        data: 'mar',
                        render(data, type) {
                            return _currencyFormat(data);
                        }
                    },
                    {
                        data: 'apr',
                        render(data, type) {
                            return _currencyFormat(data);
                        }
                    },
                    {
                        data: 'may',
                        render(data, type) {
                            return _currencyFormat(data);
                        }
                    },
                    {
                        data: 'jun',
                        render(data, type) {
                            return _currencyFormat(data);
                        }
                    },
                    {
                        data: 'jul',
                        render(data, type) {
                            return _currencyFormat(data);
                        }
                    },
                    {
                        data: 'aug',
                        render(data, type) {
                            return _currencyFormat(data);
                        }
                    },
                    {
                        data: 'sept',
                        render(data, type) {
                            return _currencyFormat(data);
                        }
                    },
                    {
                        data: 'oct',
                        render(data, type) {
                            return _currencyFormat(data);
                        }
                    },
                    {
                        data: 'nov',
                        render(data, type) {
                            return _currencyFormat(data);
                        }
                    },
                    {
                        data: 'dec',
                        render(data, type) {
                            return _currencyFormat(data);
                        }
                    },
                    {
                        data: 'total',
                        render(data, type) {
                            return _currencyFormat(data);
                        }
                    },

                ]
            });
        });
    }

    /**
     * RGBA randomizer
     *
     * @returns {string}
     */
    const _random_rgba = () => {
        var o = Math.round, r = Math.random, s = 255;
        return 'rgba(' + o(r() * s) + ',' + o(r() * s) + ',' + o(r() * s) + ',' + r().toFixed(1) + ')';
    }

    /**
     * Format to currency
     * @param {float} value
     * @returns {Intl}
     */
    const _currencyFormat = (value) => {
        return new Intl.NumberFormat('en-PH', {style: 'currency', currency: 'PHP'}).format(value)
    }

    return {
        OverAllSalesReportInit: OverAllSalesReportInit
    }
}

export default OverAllSalesReport;
