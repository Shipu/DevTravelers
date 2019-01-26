<form method="POST" action="{{ $url }}" id="lineButton" class="{{ $formClass ?? '' }}">
    {{ csrf_field() }}
    @if(!empty($fields) && is_array($fields))
        @foreach($fields as $field)
            @if($field['type'] === 'hidden')
                <input type="hidden" name="{{ $field['name'] }}" value="{{ $field['value'] }}">
            @endif
            @if($field['type'] === 'select' && count($field['options']))
                <select name="{{ $field['name'] }}">
                    @foreach( $field['options'] as $key => $value)
                        <option value="{{ $key }}" {{ isset($field['value']) && $field['value'] == $key ? 'selected' : ''  }}>{{ $value }}</option>
                    @endforeach
                </select>
            @endif
        @endforeach
    @endif
    <button type="submit" class="btn btn-{{$class ?? 'default btn-xs'}}" @if(!empty($confirmation) || !empty($confirm_message)) onclick='return confirm("{{ $confirm_message or "Are you Sure ?" }}");' @endif>
        <i class="fa fa-{{$icon ?? 'link'}}"></i> {{ $label }}
    </button>
</form>
