/**
 * Admin JS Compilation
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

import $ from 'jquery';
import dt from 'datatables.net-bs4';
import bootstrap from 'bootstrap';
import PromoCodes from './PromoCodes';
import FranchiseShipping from './FranchiseShipping';
import FranchiseAdmin from './FranchiseAdmin';
import FranchiseeOrders from './FranchiseeOrders';
import PurchaseOrder from './PurchaseOrders';
import Orders from './Orders';
import Supplies from './Supplies';
import Categories from './Categories';
import Users from './Users';
import SiteSettings from './SiteSettings';
import SuppliesReport from './SuppliesReport';
import OverAllSalesReport from "./Reports";
import AuditTrail from './AuditTrail';
import Announcements from './Announcements';
import Dashboard from './Dashboard';
import 'select2/dist/js/select2.full';
(($, bt, dt) => {
    'Use strict'

    $(document).on('click', '.btn-delete', (event) => {
        let action = $(event.currentTarget).data('action');
        $('#deleteModal').modal();
        $('#deleteModal form').attr('action', action);
    });

    $(document).on('click', '.btn-change-logs', (event) => {
        let url = $(event.currentTarget).data('url');
        $('#changeLogsModal').modal();
        let auditTrail = new AuditTrail();
        auditTrail.init(url);
    });

    $(document).on('click', '.btn-logout', (e) => {
        e.preventDefault();
        document.getElementById('logout-form').submit();
        sessionStorage.removeItem('cart_items')
        sessionStorage.removeItem('cart_total')
        sessionStorage.removeItem('promo_code_setup')
    });

    $(window).on('load', () => {
        $('select[data-selection-js="true"]').select2({
            placeholder: "Select Here"
        });
    });

    ///////Dashboard////////
    if (location.href.search('admin/dashboard') >= 0) {
        const dashboard = new Dashboard();
        dashboard.init();
    }

    ///////promo codes////////
    if (location.href.search('admin/promo_codes') >= 0) {
        const promo_codes = new PromoCodes();
        promo_codes.init();
    }

    /////////////franchisee//////////////
    if (location.href.search('franchisee/shipping') >= 0) {
        const franchise_shipping = new FranchiseShipping();
        franchise_shipping.init();
    }

    if (location.href.search('franchisee/thank-you') >= 0) {
        sessionStorage.removeItem('cart_items')
        sessionStorage.removeItem('cart_total')
        sessionStorage.removeItem('promo_code_setup')
    }

    if (location.href.search('franchisee/orders') >= 0) {
        const fo = new FranchiseeOrders();
        fo.init();
    }

    if (location.href.search('admin/franchisees') >= 0) {
        const fa = new FranchiseAdmin();
        fa.init();

    }

    ///////////////////////purchase orders/////////////////////
    if (location.href.search('admin/purchase_orders') >= 0) {
        const po = new PurchaseOrder();
        po.init();
    }

    //////////////Orders////////////
    if (location.href.search('admin/orders') >= 0) {
        const or = new Orders();
        or.init();
    }

    //////////////Supplies////////////
    if (location.href.search('admin/supplies') >= 0) {
        const sup = new Supplies();
        sup.init();
    }

    //////////////Categories////////////
    if (location.href.search('admin/categories') >= 0) {
        const categories = new Categories();
        categories.init();
    }

    ////////////Users//////////////////
    if (location.href.search('admin/users') >= 0) {
        const us = new Users();
        us.init();
    }

    ////////////Site Settings//////////////////
    if (location.href.search('admin/site_settings') >= 0) {
        const ss  = new SiteSettings();
        ss.init();
    }

    ////////////Announcements//////////////////
    if (location.href.search('admin/announcements') >= 0) {
        const announcements  = new Announcements();
        announcements.init();
    }

    ////////////Reports//////////////////
    if (location.href.search('admin/overall-sales-report') >= 0) {
        OverAllSalesReport().OverAllSalesReportInit();
    }

    if (location.href.search('admin/supplies-report') >= 0) {
        SuppliesReport().suppliesReportInit();
    }

})($, bootstrap, dt);
