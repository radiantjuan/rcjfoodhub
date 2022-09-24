<x-admin.layout>
    <div class="col-12 col-lg-8">
        <div class="row">
            <div class="col">
                <h1>{{ __($page_title) }}</h1>
            </div>
            @php
                $user = Auth::user();
            @endphp
            @if ($user->role_id == 1)
                @if (!empty($extractable))
                    <div class="col-2 text-right">
                        <form action="{{ route($route_model->get_prefix() . '.extract_record') }}" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary"><i class="fa fa-download"></i> CSV
                                Report</button>
                        </form>
                    </div>
                @endif
                @if (!empty($has_audit_trail))
                <div class="col-2 text-right">
                    <button type="submit" class="btn btn-primary btn-change-logs"
                        data-url="{{ route('change.logs', ['model' => $route_model->get_prefix()]) }}"><i
                            class="fa fa-eye"></i> Change Logs</button>
                </div>
                @endif
            @endif
        </div>
        <div class="row justify-content-center">
            <div class="col">
                <x-admin.orders.orders :data="$data['data']" :headers="$data['headers']" />
            </div>
            {{-- <div class="col d-block d-lg-none">
                <x-admin.orders.orders-mobile :data="$data['data']" :headers="$data['headers']" />
            </div> --}}
        </div>
    </div>
    <input type="hidden" id="auth_token" value="{{csrf_token()}}">
    @include('admin.orders.order-cancel-modal')
</x-admin.layout>
