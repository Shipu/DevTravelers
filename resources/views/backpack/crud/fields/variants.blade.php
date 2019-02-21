<?php
$variants = old($field['name'], array_get($field, 'value', [])) ?? [];
$new_item_template = view('backpack.crud.fields.variant', get_defined_vars())->render();
$index = 1;

?>

<div class="form-group">
  <div class="variants-container" data-new-item-template="{{$new_item_template}}" data-variant-id="{{ $index }}"
       data-old-value-exists="@json(!empty($variants))"
  >
      @if(!empty($variants))
          @foreach($variants as $index => $item)
              @include('backpack.crud.fields.variant', [
                'item' => $item,
                'variantIndex' => $index+1
              ])
          @endforeach
      @endif
    {{--@include('crud::fields.variant')--}}
    {{--@if(!empty($old))--}}
      {{--@foreach($item->stock_items as $stockItemIndex => $stockItem)--}}
        {{--@include('crud::fields.variant')--}}
      {{--@endforeach--}}
    {{--@endif--}}
  </div>

</div>


{{-- Note: you can use  to only load some CSS/JS once, even though there are multiple instances of it --}}

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

  {{-- FIELD CSS - will be loaded in the after_styles section --}}
  @push('crud_fields_styles')
{{--    <link href="{{ asset('vendor/adminlte/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />--}}
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    <style>
      .panel-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 3px;
      }

      .badge {
        margin-left: 5px
      }
      .margin-b-10 {
        margin-bottom: 10px;
      }
    </style>
  @endpush

  {{-- FIELD JS - will be loaded in the after_scripts section --}}
  @push('crud_fields_scripts')
{{--    <script src="{{ asset('vendor/adminlte/plugins/select2/select2.min.js') }}"></script>--}}

    <script>
      var variantIndex = null;
      function getVariantAttributes(setId, vDiv, vIndex) {
        // console.log(vIndex);
        // $('#render-variant-'+variantIndex+'-attributes').append(setId);
        // Set oldData to "null" if attributes set was changed
        if(typeof(oldSetId) != 'undefined' && oldSetId != setId) {
          oldData = null;
        }

        {{--// Ajax call--}}
        $.ajax({
          url: "{{ route('getAttrBySetId') }}",
          type: 'POST',
          data: {
            setId: setId,
            old: oldData,
            name_prefix: 'variant',
            attribute_set_id: setId,
            variant_index: vIndex
          }
        })
          .done(function(response) {
            // Render ajax response
            vDiv.parent().parent().find('#render-variant-attributes').html(response).prepend("<div class='col-md-12' style='margin: 15px 0px !important;'><h3 style='margin: 0;'>"+vDiv.find('option:selected').text()+"</h3></div><hr>");

            // Reinitalize select2
            // if($('.select2_field').data('select2')) {
            //   $('.select2_field').select2("destroy");
            // }
            // $('.select2_field').select2({
            //   theme: "bootstrap"
            // });
          });
      }

      $(document).ready(function($) {

        function addNewItem($variantsContainer) {
          var $lastItemContainer = $variantsContainer.find('.variant-container').last();
          var lastItemIndex = $lastItemContainer.length ? $lastItemContainer.data('vi') : 0;
          var variantId = $variantsContainer.data('variantId');
          variantIndex = lastItemIndex + 1;
          var newItemTemplate = $variantsContainer.data('newItemTemplate').replace(/__INDEXVI__/g, variantIndex);
          var newItemTemplate = newItemTemplate.replace(/__INDEX__/g, variantId);
          var $newItem = $(newItemTemplate);
          $variantsContainer.append($newItem);
          $newItem.find('.variant-delete').addClass('hidden');
          $newItem.find('.variant-sku').focus();
        }

        $('.variants-container').each(function() {
          var $variantsContainer = $(this);
          if (!$variantsContainer.data('oldValueExists')) {
            addNewItem($variantsContainer);
          } else {
            $variantsContainer.find('.variant-add').addClass('hidden');
            var $lastItemContainer = $variantsContainer.find('.variant-container').last();
            var lastItemIndex = $lastItemContainer.length ? $lastItemContainer.data('vi') : 0;
            var variantId = $variantsContainer.data('variantId');
            variantIndex = lastItemIndex + 1;
            var newItemTemplate = $variantsContainer.data('newItemTemplate').replace(/__INDEXVI__/g, variantIndex);
            var newItemTemplate = newItemTemplate.replace(/__INDEX__/g, variantId);
            addNewItem($variantsContainer);
          }
          $variantsContainer.on('click', '.variant-add', function(e) {
            e.preventDefault();
            // if($variantsContainer.find('.variant-upc').last().val() != '') {
              $variantsContainer.find('.variant-delete').removeClass('hidden');
              $variantsContainer.find('.variant-add').addClass('hidden');
              addNewItem($variantsContainer);
              $('#variant-'+variantIndex+'-attributes-set').change(function () {
                getVariantAttributes($(this).val(), $(this), variantIndex);
              });
            // }
          });
          $variantsContainer.on('click', '.variant-delete', function(e) {
            e.preventDefault();
            $(this).parents('.variant-container').eq(0).remove();
          });
        });
        // console.log(variantIndex);
        if(variantIndex > 1) {
          for (i = 1; i < variantIndex; i++) {
            // if (typeof($('#variant-'+i+'-attributes-set').change) != "function") {
              $('#variant-'+i+'-attributes-set').change(function () {
                getVariantAttributes($(this).val(), $(this), i);
              });
              // console.log(i, $('#variant-2-attributes-set').val());
              getVariantAttributes($('#variant-'+i+'-attributes-set').val(), $('#variant-'+i+'-attributes-set'), i);
            // }
          }
        }
        $('#variant-'+variantIndex+'-attributes-set').change(function () {
          getVariantAttributes($(this).val(), $(this), variantIndex);
        });
      });
    </script>
  @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
