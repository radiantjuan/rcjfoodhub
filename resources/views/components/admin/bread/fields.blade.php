@switch($data_type)
    @case('select')
        <label for="{{ $name }}">{{ str_replace('_',' ',ucfirst($name)) }}</label>
        <select class="form-control" name={{ $name }} {{$attr ? __(implode(' ', $attr)) : ''}}>
            @foreach ($options as $option)
                <option value={{ $option->id }} {{ $default_value == $option->id ? 'selected' : '' }}>
                    {{ $option->name }}</option>
            @endforeach
        </select>
    @break
    @case('select-enum')
        <label for="{{ $name }}">{{ str_replace('_',' ',ucfirst($name)) }}</label>
        <select class="form-control" name={{ $name }} {{$attr ? __(implode(' ', $attr)) : ''}}>
            @foreach ($options as $option)
                <option value="{{$option}}" {{ $default_value == $option ? 'selected' : '' }}>
        {{ $option }}</option>
        @endforeach
        </select>
    @break
    @case('select-multiple')
        @php
            $json_values = ($default_value && $default_value !== "null") ? json_decode($default_value) : [];
        @endphp
        <label for="{{ $name }}">{{ str_replace('_',' ',ucfirst($name)) }}</label>
        <select class="form-control" name={{ $name.'[]' }} multiple {{$attr ? __(implode(' ', $attr)) : ''}}>
        @foreach ($options as $option)
            <option value="<?= $option ?>" {{ (in_array($option,$json_values)) ? 'selected' : '' }}>
            {{ $option }}</option>
        @endforeach
        </select>
    @break
    @case('select-multiple-json')
        @php
            $json_values = ($default_value && $default_value !== "null") ? json_decode($default_value) : [];
        @endphp
        <label for="{{ $name }}">{{ str_replace('_',' ',ucfirst($name)) }}</label>
        <select class="form-control" name={{ $name.'[]' }} multiple {{$attr ? __(implode(' ', $attr)) : ''}}>
        @foreach ($options as $option)
            <option value="<?= $option->id ?>" {{ (in_array($option->id,$json_values)) ? 'selected' : '' }}>
            {{ $option->name }}</option>
        @endforeach
    </select>
    @break
    @case('checkbox')
        <div class="form-group">
            <label for="{{ $name }}">
                <input type={{ $data_type }} step="{{ $step }}" id="{{ $name }}"
                name="{{ $name }}"
                {{($default_value) ? 'checked' : ''}} {{$attr ? __(implode(' ', $attr)) : ''}} />
                {{ str_replace('_',' ',ucfirst($name)) }}
            </label>
        </div>
    @break
    @case('date')
        <div class="form-group">
            <label for="{{ $name }}">{{ str_replace('_',' ',ucfirst($name)) }}</label>
            <input type={{ $data_type }} step="{{ $step }}" class="form-control" id="{{ $name }}"
            name="{{ $name }}"
            value="<?= date('Y-m-d', strtotime(($default_value) ? $default_value : 'now')) ?>" {{$attr ? __(implode(' ', $attr)) : ''}}/>
        </div>
    @break
    @case('textarea')
        <div class="form-group">
            <label for="{{ $name }}">{{ str_replace('_',' ',ucfirst($name)) }}</label>
            <textarea name="{{ $name }}" id="{{ $name }}" cols="30" rows="10" class="form-control"></textarea>
        </div>
    @break
    @case('ckeditor')
        <div class="form-group">
            <label for="{{ $name }}">{{ str_replace('_',' ',ucfirst($name)) }}</label>
            <textarea name="{{ $name }}" id="{{ $name }}" cols="30" rows="10" class="form-control" {{$attr ? __(implode(' ', $attr)) : ''}}>{{$default_value}}</textarea>
        </div>
    @break
    @case('hidden')
        <input type={{ $data_type }} class="form-control" id="{{ $name }}"
        name="{{ $name }}"
        value="{{$default_value}}" {{$attr ? __(implode(' ', $attr)) : ''}} />
    @break;
    @case('file')
        @if ($name == 'img_url')
            <img src="{{ \Storage::url($default_value) }}" style="width:200px;" />
        @else
            <a href="{{ \Storage::url($default_value) }}" target="_blank">{{ \Storage::url($default_value) }}</a>
        @endif
    @default
        <div class="form-group">
            <label for="{{ $name }}">{{ str_replace('_',' ',ucfirst($name)) }}</label>
            <input type={{ $data_type }} class="form-control" id="{{ $name }}"
            name="{{ $name }}"
            value="{{$default_value}}" {{$attr ? __(implode(' ', $attr)) : ''}} />
        </div>
@endswitch
