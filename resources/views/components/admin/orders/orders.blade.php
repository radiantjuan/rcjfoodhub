@if (\Session::has('status'))
    <div class="alert alert-success">

        {{ \Session::get('status') }}

    </div>
@endif
<table class="js-datatable table table-sm">
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $rows)
            <tr>
                @foreach ($rows as $column_name => $row)
                    @if ($column_name == 'Actions')
                        <td>
                            @foreach ($row as $actions)
                                {!!$actions!!}
                            @endforeach
                        </td>
                    @else
                        <td>{!! $row !!}</td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
<x-admin.bread.modal />