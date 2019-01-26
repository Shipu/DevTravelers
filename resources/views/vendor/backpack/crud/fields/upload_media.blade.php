<?php
    $multiple = array_get($field, 'multiple', true);
    $field_name_suffix = ( $multiple ? '[]' : '' );
    $relation = data_get($field, 'relation');
    $entry = data_get($field, 'entry');

    if ( !is_array($field['value']) ) {
        $field[ 'value' ] = !blank($field[ 'value' ]) ? [ $field[ 'value' ] ] : [];
    }

    if($relation && $crud && $crud->entry) {
        $entry = $crud->entry;
    }

    if(!blank($entry)) {
        $values = $entry->{$relation}()->get();
        if(!blank($values)) {
            $field['value'] = $values;
        }
    }

?>

<div @include('crud::inc.field_wrapper_attributes') >

    <label>{!! $field['label'] !!}</label>

    @include('crud::inc.field_translatable_icon')

    {{-- Show the file name and a "Clear" button on EDIT form. --}}

    @if (!blank($field['value']))

        <div class="well well-sm media-preview-container clearfix" id="sortable">

            @foreach($field['value'] as $media)

                <div class="media-preview-wrapper">
                    <div class="media-preview">
                        <input type="hidden" name="{{ $field['name'] }}_existing{{$field_name_suffix}}"
                               value="{{$media->id}}">
                        <a id="{{ $field['name'] }}_{{ $media->id }}_clear_button" href="#"
                           class="btn btn-xs media-clear-button" title="Clear file" data-media-id="{{ $media->id }}"><i
                                    class="fa fa-trash"></i></a>
                        <a target="_blank" href="{{ $media->getUrl() }}">
                            @if ($field['is_image'])
                                <img src="{{$media->getUrl()}}" class="img-thumbnail" alt="{{$media->name}}">
                            @else
                                {{ $media->name }}
                            @endif
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <input
            type="file"
            id="{{ $field['name'] }}_file_input"
            name="{{ $field['name'] }}{{$field_name_suffix}}"
            @include('crud::inc.field_attributes')
            {{ $multiple ? 'multiple' : '' }}
    >
</div>

{{--FIELD EXTRA JS --}}

{{-- push things in the after_scripts section --}}

@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))
    @push('crud_fields_styles')
        <style>
            .media-preview-container {
                display: flex;
                flex-flow: row wrap;
            }

            .media-preview-wrapper {
                width: 25%;
            }

            .media-preview {
                position: relative;
            }

            .media-clear-button {
                position: absolute;
                top: 15px;
                right: 15px;
                color: #d9534f;
                background-color: #fff;
                border-color: #d43f3a;
            }
        </style>
    @endpush

    @push('crud_fields_scripts')
        <!-- no scripts -->
        <script>
          $(function () {
            // $( "#sortable" ).sortable().disableSelection();
          });
          $(document).on('click', ".media-clear-button", function ( e ) {
            e.preventDefault();
            var $previewRow = $(this).parent().parent();
            var $container = $previewRow.parent();
            var mediaId = $(this).data('mediaId');
            // remove the filename and button
            $previewRow.remove();
            // if the file container is empty, remove it
            if ($container.find('.media-preview').length === 0) {
              $container.remove();
            }
            $("<input type='hidden' name='clear_{{ $field['name'] }}{{$field_name_suffix}}' value='" + mediaId + "'>").insertAfter("#{{ $field['name'] }}_file_input");
          });
        </script>
    @endpush
@endif
