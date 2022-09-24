<li class="nav-item">
    <a class="nav-link {{(in_array(\Request::route()->getName(),['admin.dashboard'])) ? 'active' : ''}}" href="{{route('admin.dashboard')}}" v-pre>
        Dashboard
    </a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link {{(in_array(\Request::route()->getName(),['orders.index','orders.add','orders.edit'])) ? 'active' : ''}}" href="{{route('orders.index')}}" v-pre>
        Orders
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{(in_array(\Request::route()->getName(),['purchase_orders.index','purchase_orders.add','purchase_orders.edit'])) ? 'active' : ''}}" href="{{route('purchase_orders.index')}}" v-pre>
        Purchase Order
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{(in_array(\Request::route()->getName(),['supplies.index','supplies.add','supplies.edit'])) ? 'active' : ''}}" href="{{route('supplies.index')}}" v-pre>
        Supplies
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{(in_array(\Request::route()->getName(),['categories.index','categories.add','categories.edit'])) ? 'active' : ''}}" href="{{route('categories.index')}}" v-pre>
        Categories
    </a>
</li>
