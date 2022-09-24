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
            <tr class="{{!empty($rows['color']) ? $rows['color'] : ''}}">
                @foreach ($rows as $row)
                    <td>{!! $row !!}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
<x-admin.bread.modal />
