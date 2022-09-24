@component('mail::message')
    <h1>Your order is now {{$order_status}}</h1>
    @component('mail::button', ['url' => route('orders.edit',['id' => $order_id])])
        Check the status here!
    @endcomponent
    Thanks,<br>
    Admin
@endcomponent
