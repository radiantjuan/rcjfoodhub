<x-admin.layout>
    <div class="col-12 col-lg-6">
        <h1>{{ __($page_title) }}</h1>
        <div class="pt-4">
            <div class="card w-100">
                <div class="card-header">
                    {{ __($page_sub_title) }}
                </div>
                <div class="card-body">

                    @php
                    @endphp
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (\Session::has('status'))
                        <div class="alert alert-success">
                            {{ \Session::get('status') }}
                        </div>
                    @endif
                    <form method="POST"
                        action="{{ $route_model->get_route($route_options['name'], $route_options['options']) }}"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @if ($route_options['name'] == 'edit')
                            {{ method_field('PUT') }}
                        @endif
                        @foreach ($fields as $field)
                            {{ $field }}
                        @endforeach
                        <input type="submit" value="Save" class="btn btn-success mt-3">
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
