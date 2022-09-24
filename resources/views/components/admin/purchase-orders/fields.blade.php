@switch($data_type)
    @case('special')
        @php
            $json_values = $default_value && $default_value !== 'null' ? json_decode($default_value) : [];
        @endphp
        <label for="{{ $name }}">{{ str_replace('_', ' ', ucfirst($name)) }}</label>
        <div class="card mb-3">
            <div class="card-body supply-list-container-card-body">
                @if (empty($json_values))

                @else
                    @foreach ($json_values as $key => $json_value)
                        <div class="row py-2 supply-list-container align-items-center">
                            <div class="col-4">
                                <select class="form-control form-control-sm js-supply-dd-class" name={{ $name . '[]' }}
                                    {{ $attr ? __(implode(' ', $attr)) : '' }} data-field-id={{ $key }}>
                                    @foreach ($options as $option)
                                        <option value="<?= $option->id ?>" data-sku="{{ $option->sku }}"
                                            data-current-quantity="{{ $option->stock_count }}"
                                            data-stock-warning={{$option->stock_warning_count}}
                                            data-title="{{ $option->name }}"
                                            {{ $json_value->id == $option->id ? 'selected' : '' }}>
                                            {{ $option->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                SKU: <span class="js-span-sku" data-field-id={{ $key }}></span>
                            </div>
                            <div class="col">
                                Current QTY: <span class="js-span-current-quantity"
                                    data-field-id={{ $key }}></span>
                            </div>
                            <div class="col">
                                <input type="number" class="form-control form-control-sm" name="supply_quantity[]" id=""
                                    placeholder="Quantity" value="{{ $json_value->qty }}">
                            </div>
                            <div class="col">
                                <button type="button"
                                    class="btn btn-danger js-btn-delete-supply">{{ __('Delete') }}</button>
                            </div>
                        </div>
                    @endforeach
                @endif

                <button type="button" class="btn btn-success js-btn-add-supply">{{ __('Add More Supply') }}</button>
            </div>
        </div>

        <div class="row py-2 supply-list-container-copy d-none align-items-center">
            <div class="col-4">
                <select class="form-control form-control-sm js-supply-dd-class" name={{ $name . '[not_included]' }}
                    {{ $attr ? __(implode(' ', $attr)) : '' }}>
                    <option value="0">Select Supplies</option>
                    @foreach ($options as $option)
                        <option value="<?= $option->id ?>" data-sku="{{ $option->sku }}"
                            data-current-quantity="{{ $option->stock_count }}" data-stock-warning={{$option->stock_warning_count}} data-title="{{ $option->name }}">
                            {{ $option->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                SKU: <span class="js-span-sku"></span>
            </div>
            <div class="col">
                Current QTY: <span class="js-span-current-quantity"></span>
            </div>
            <div class="col">
                <input type="number" class="form-control form-control-sm" name="supply_quantity[not_included]" id=""
                    placeholder="Quantity">
            </div>
            <div class="col">
                <button type="button" class="btn btn-danger js-btn-delete-supply">{{ __('Delete') }}</button>
            </div>
        </div>
    @break;
    @case('hidden')
        <input type={{ $data_type }} step="{{ $step }}" class="form-control" id="{{ $name }}"
            name="{{ $name }}" value="{{ $default_value }}" {{ $attr ? __(implode(' ', $attr)) : '' }} />
    @break;
    @default
        <div class="form-group">
            <label for="{{ $name }}">{{ str_replace('_', ' ', ucfirst($name)) }}</label>
            <input type={{ $data_type }} step="{{ $step }}" class="form-control" id="{{ $name }}"
                name="{{ $name }}" value="{{ $default_value }}" {{ $attr ? __(implode(' ', $attr)) : '' }} />
        </div>
@endswitch
