<li class="nav-item">
    <a class="nav-link {{(in_array(\Request::route()->getName(),['admin.dashboard'])) ? 'active' : ''}}" href="{{route('admin.dashboard')}}" v-pre>
        Dashboard
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{route('shop')}}" v-pre>
        Shop Now!
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{(in_array(\Request::route()->getName(),['franchise.orders.index','franchise.orders.add','franchise.orders.edit'])) ? 'active' : ''}}" href="{{route('franchise.orders.index')}}" v-pre>
        Orders
    </a>
</li>