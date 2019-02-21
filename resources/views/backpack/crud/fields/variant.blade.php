@php
    $index = $index ?? '__INDEX__';
    $variantIndex = $variantIndex ?? '__INDEXVI__';
    $item = $item ?? [];
    $attributeSet = ['--'];
    $id = data_get($item, 'id');
    $sku = data_get($item, 'sku');
    $stock = data_get($item, 'stock');
    $price = data_get($item, 'price');
    $status = data_get($item, 'status');
    $attributeSetId = data_get($item, 'attribute_set_id');
    $media = data_get($item, 'media');

    array_push($attributeSet, \App\Models\AttributeSet::pluck('name', 'id')->toArray());
    $attributeSet = array_flatten($attributeSet);

    $variantConfigs = [
        [
            'name'  => 'variant['.$variantIndex.'][id]',
            'label' => trans('validation.attributes.sku'),
            'type'  => 'hidden',
            'value' => $id
        ],
        [
            'name'  => 'variant['.$variantIndex.'][sku]',
            'label' => trans('validation.attributes.sku'),
            'type'  => 'text',
            'value' => $sku
        ],
        [
            'name'  => 'variant['.$variantIndex.'][stock]',
            'label' => trans('validation.attributes.stock'),
            'type'  => 'number',
            'value' => $stock
        ],
        [
            'name'  => 'variant['.$variantIndex.'][price]',
            'label' => trans('validation.attributes.price'),
            'type'  => 'number',
            'attributes' => [
                'step' => 'any',
            ],
            'value' => $price
        ],
        [
            'name'    => 'variant['.$variantIndex.'][status]',
            'label'   => trans('validation.attributes.status'),
            'type'    => 'select_from_array',
            'options' => trans('statuses'),
            'value' => $status
        ],
        [
            'label' => trans("validation.attributes.image"),
            'name' => 'variant['.$variantIndex.'][variant_product_images]',
            'type' => 'upload_media',
            'multiple' => true,
            'upload' => true,
            'is_image' => true,
            'value' => null,
            'relation' => 'media',
            'entry' => $item,
            'variant' => true
        ],
        [
            'name'       => 'variant['.$variantIndex.'][attribute_set_id]',
            'label'      => trans_choice('entity.attribute-set', 0),
            'type'       => 'select2_from_array',
            'attributes' => [
                'id'    => 'variant-'.$variantIndex.'-attributes-set'
            ],
            'options' => $attributeSet,
            'value' => $attributeSetId
        ]
    ];
@endphp

<div class="form-group row variant-container" data-index="{{ $index }}" data-vi="{{ $variantIndex }}">
    <div class="col-md-12">
        {{--varient {{ $variantIndex }}--}}
        @foreach($variantConfigs as $config)
            @include('crud::inc.show_fields', ['fields' => [$config]])
        @endforeach
        <div id="render-variant-attributes" style="padding-left: 20px;">
        </div>
        {{--<input class="form-control variant-upc" placeholder="UPC" type="text" name="{{$field['name']}}[{{ $index }}][stock_items][{{ $variantIndex }}][upc]" value="{{ $upc }}" autofocus>--}}
    </div>
    <div class="col-md-12">
        <button class="btn btn-danger variant-delete" type="button"><i class="fa fa-trash" aria-hidden="true"></i>Delete</button>
        <button class="btn btn-success variant-add" type="button"><i class="fa fa-plus" aria-hidden="true"></i>Add</button>
    </div>
    <div class="col-md-12">
        <div style="background-color: #fff;border-top: 2px dashed #8c8b8b; margin-top: 10px"></div>
    </div>
</div>
