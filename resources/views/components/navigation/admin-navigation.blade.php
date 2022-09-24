<li class="nav-item">
    <a class="nav-link {{(in_array(\Request::route()->getName(),['admin.dashboard'])) ? 'active' : ''}}" href="{{route('admin.dashboard')}}" v-pre>
        Dashboard
    </a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        Orders Management
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <a class="nav-link dropdown-item {{(in_array(\Request::route()->getName(),['orders.index','orders.add','orders.edit'])) ? 'active' : ''}}" href="{{route('orders.index')}}" v-pre>
            Orders
        </a>
        <a class="nav-link dropdown-item {{(in_array(\Request::route()->getName(),['promo_codes.index','promo_codes.add','promo_codes.edit'])) ? 'active' : ''}}" href="{{route('promo_codes.index')}}" v-pre>
            Promo Codes
        </a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        Supplies Management
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <a class="nav-link dropdown-item {{(in_array(\Request::route()->getName(),['supplies.index','supplies.add','supplies.edit'])) ? 'active' : ''}}" href="{{route('supplies.index')}}" v-pre>
            Supplies
        </a>
        <a class="nav-link dropdown-item {{(in_array(\Request::route()->getName(),['purchase_orders.index','purchase_orders.add','purchase_orders.edit'])) ? 'active' : ''}}" href="{{route('purchase_orders.index')}}" v-pre>
            Purchase Order
        </a>
        <a class="nav-link dropdown-item {{(in_array(\Request::route()->getName(),['categories.index','categories.add','categories.edit'])) ? 'active' : ''}}" href="{{route('categories.index')}}" v-pre>
            Categories
        </a>
    </div>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        Administration Settings
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <a class="nav-link {{(in_array(\Request::route()->getName(),['announcements.index','announcements.add','announcements.edit'])) ? 'active' : ''}}" href="{{route('announcements.index')}}" v-pre>
            Announcements
        </a>
        <a class="nav-link {{(in_array(\Request::route()->getName(),['franchisees.index','franchisees.add','franchisees.edit'])) ? 'active' : ''}}" href="{{route('franchisees.index')}}" v-pre>
            Branches
        </a>
        <a class="nav-link {{(in_array(\Request::route()->getName(),['users.index','users.add','users.edit'])) ? 'active' : ''}}" href="{{route('users.index')}}" v-pre>
            Users
        </a>
        <a class="nav-link {{(in_array(\Request::route()->getName(),['site_settings.index','site_settings.add','site_settings.edit'])) ? 'active' : ''}}" href="{{route('site_settings.index')}}" v-pre>
            Site Settings
        </a>
    </div>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        Reports
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <a class="nav-link {{(in_array(\Request::route()->getName(),['reports.over_all_sales_report'])) ? 'active' : ''}}" href="{{route('reports.over_all_sales_report')}}" v-pre>
            Overall Sales Report
        </a>
        <a class="nav-link {{(in_array(\Request::route()->getName(),['reports.supplies_report'])) ? 'active' : ''}}" href="{{route('reports.supplies_report')}}" v-pre>
            Supplies Report
        </a>
    </div>
</li>
