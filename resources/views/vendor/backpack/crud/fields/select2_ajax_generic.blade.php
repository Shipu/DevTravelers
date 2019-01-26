<!-- select2 from ajax -->
<!-- use discountcrudcontroller -->
<?php
    $connected_entity = new $field['model'];
    $connected_entity_key_name = $connected_entity->getKeyName();
    $old_value = old($field['name'], array_get($field, 'value', array_get($field, 'default', null))) ?? null;
    $entity_model = isset($field['custom_model']) ? $connected_entity : $crud->model;
    $field['data_source'] = $field['data_source'] ?? route('search.entitySearch');
    $select2Config = $field;
    $select2Config["allow_clear"] = $entity_model::isColumnNullable($field['name']);
    $select2Config["include_selected"] = $field["include_selected"] ?? false;
?>

<div @include('crud::inc.field_wrapper_attributes') >
    <label id="{{ $field['label'] }}_id">{!! $field['label'] !!}</label>
    <input type="hidden" name="{{ $field['name'] }}" id="{{ $field['name'] }}_id" value="{{ $old_value }}">
    <select
            name="{{ $field['name'] }}"
            style="width: 100%"
            id="select2_ajax_{{ $field['name'] }}"
            @include('crud::inc.field_attributes', ['default_class' =>  'form-control select2_ajax_generic'])
            data-config='@json($select2Config)'
    >

        @if ($old_value)
            @php
                $item = $connected_entity->withoutGlobalScope('record_access')->find($old_value);
            @endphp
            @if ($item)
                @if ($select2Config["allow_clear"])
                    <option value="" selected>
                        {{ $field['placeholder'] }}
                    </option>
                @endif
                <option value="{{ $item->getKey() }}" selected>
                    {{ $item->{$field['attribute']} }}
                </option>
            @endif
        @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <!-- include select2 css-->
        <link href="{{ asset('vendor/adminlte/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        {{-- allow clear --}}
        @if ($select2Config["allow_clear"])
            <style type="text/css">
                .select2-selection__clear::after {
                    content: ' {{ trans('backpack::crud.clear') }}';
                }
            </style>
        @endif
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!-- include select2 js-->
        <script src="{{ asset('vendor/adminlte/plugins/select2/select2.min.js') }}"></script>

        <script>
            var reloadUrl;
            jQuery(document).ready(function($) {
                // trigger select2 for each untriggered select2 box
                $(".select2_ajax_generic").each(function (i, obj) {
                    var $obj = $(obj);
                    if (!$obj.hasClass("select2-hidden-accessible")){
                        var config = $obj.data('config');
                        var $select2 = $obj.select2({
                            theme: 'bootstrap',
                            multiple: config.multiple || false,
                            placeholder: config.placeholder,
                            minimumInputLength: config.minimum_input_length || 0,
                            allowClear: config.allow_clear,
                            ajax: {
                                url: config.data_source,
                                dataType: 'json',
                                quietMillis: 250,
                                data: function (params) {
                                    var data = {
                                        q: params.term, // search term
                                        page: params.page,
                                        widget_type: 'select2'
                                    };

                                    if (config.query_params) {
                                        $.each(config.query_params, function(key, value){
                                            var paramName = key, paramValue = value;
                                            if ($.isPlainObject(value)) {
                                                paramName = value.name || key;
                                                if (value.value) {
                                                    paramValue = value.value;
                                                }
                                                else if (value.value_source) {
                                                    var $field = $(value.value_source);
                                                    if ($field.size() === 1) {
                                                        paramValue = $field.val();
                                                        if (value.transformation_map && value.transformation_map[paramValue]) {
                                                            paramValue = value.transformation_map[paramValue];
                                                        }
                                                    }
                                                    else if ($field.size() > 1) {
                                                        paramValue = {};
                                                        $field.each(function() {
                                                            var $this = $(this);
                                                            paramValue[$this.name] = $this.val();
                                                        });
                                                    }
                                                }
                                            }
                                            data[paramName] = paramValue;
                                        });
                                    }

                                    if (config.include_selected) {
                                        data['include_id'] = $('#' + config.name + '_id').val();
                                    }

                                    return data;
                                },
                                processResults: function (data) {
                                    return {
                                        results: data
                                    };
                                },
                                cache: false
                            },
                        });


                        if (config.allow_clear) {
                            $select2.on('select2:unselecting', function(e) {
                                $(this).val('').trigger('change');
                                e.preventDefault();
                            });
                        }

                        if (config.query_params) {
                            $.each(config.query_params, function(key, value){
                                if ($.isPlainObject(value)) {
                                    var deliveryChargeInUrl = "{{ Request::has('delivery_charge')  }}";
                                    if(value.modal_open && !deliveryChargeInUrl) {
                                        $(value.modal_open).modal({
                                            backdrop: 'static',
                                            keyboard: false,
                                            show: true,
                                        });
                                    }

                                    if (value.value_source && value.reset_on_change) {
                                        var $field = $(value.value_source);
                                        $field.change(function(e){
                                            $obj.val('').trigger('change');
                                            e.preventDefault();
                                        });
                                    }

                                    if(value.reload_on_change) {
                                        function reload() {
                                            reloadUrl = "{{ \Request::url() }}?"+ key + "=" + $(this).val();
                                            var reloadData = new Object();
                                            if(value.reload_on_change.reloadData) {
                                                $.each(value.reload_on_change.reloadData, function (key, value) {
                                                    value = $(value).val();
                                                    if(value != null && value != '') {
                                                        reloadData[key] = parseInt(value);
                                                    }
                                                });
                                            }
                                            $.each(reloadData, function(key, value) {
                                                reloadUrl +=  "&" +key + "=" + value;
                                            });

                                            window.location.href = reloadUrl ;
                                        }
                                        $(value.value_source).on("select2:select", reload);
                                    }
                                }
                            });
                        }

                    }
                });
            });
        </script>
    @endpush
    {{-- --}}
@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
