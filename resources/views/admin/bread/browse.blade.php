<x-admin.layout>
    <div class="col-12 col-lg-8">
        <div class="row mb-3">
            <div class="col-5">
                <h3>{{ __($page_title) }}</h3>
            </div>
            <div class="col-7 text-right">
                <div class="row">
                    <div class="col">
                        @if ($route_model->get_route('add'))
                            <a href="{{ $route_model->get_route('add') }}" class="btn btn-success"><i
                                    class="fa fa-plus"></i> New
                                {{ $page_title }}</a>
                        @endif
                        @if (!empty($special_button))
                            <a href="{{ $special_button['route'] }}"
                                class="btn {{ $special_button['classes'] }}">{!! $special_button['label'] !!}</a>
                        @endif
                    </div>
                    @if (isset($extractable) && isset($has_audit_trail))
                        <div class="col">
                            <form action="{{ route($route_model->get_prefix() . '.extract_record') }}" method="POST">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-primary"><i class="fa fa-download"></i> CSV
                                    Report</button>
                            </form>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary btn-change-logs"
                                data-url="{{ route('change.logs', ['model' => $route_model->get_prefix()]) }}"><i
                                    class="fa fa-eye"></i> Change Logs</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <x-admin.bread.browse :data="$data['data']" :headers="$data['headers']" />
            </div>
        </div>
    </div>
    <input type="hidden" id="auth_token" value="{{ csrf_token() }}">
</x-admin.layout>
