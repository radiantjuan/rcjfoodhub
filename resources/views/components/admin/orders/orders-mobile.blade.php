@if (\Session::has('status'))
    <div class="alert alert-success">

        {{ \Session::get('status') }}

    </div>
@endif
@foreach ($data as $rows)
    <div class="card mb-3">
        <div class="card-header">
            <strong>Order Number: {{$rows['Order#']}}</strong>
            <br>
            {!!$rows['Order Status']!!}
        </div>
        <div class="card-body">
            <ul style="list-style:none">
                @foreach ($rows as $field => $row)
                    @php
                        if($field == 'Order#' || $field == 'Order Status') {
                            continue;
                        }
                    @endphp
                     @if ($field == 'Actions')
                        <li>
                            {!! $field !!} : 
                            @foreach ($row as $actions)
                                {!!$actions!!}
                            @endforeach
                        </li>
                    @else
                        <li>{!! $field !!} : {!! $row !!}</li>
                    @endif
                   
                @endforeach
            </ul>
        </div>
        <div class="card-footer">
            <a href="{{ $route_model->get_route('edit', ['id' => $rows['id']]) }}"
                class="btn btn-sm btn-primary w-100">{{ __('Check Order Details') }}</a>
        </div>
    </div>
@endforeach
<x-admin.bread.modal />
