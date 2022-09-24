@component('mail::message')
    <h1>New Order Has Been Placed!</h1>
    @component('mail::button', ['url' => route('orders.edit',['id' => $order_id])])
        Check the order here!
    @endcomponent
    Thanks,<br>
    Admin
@endcomponent
