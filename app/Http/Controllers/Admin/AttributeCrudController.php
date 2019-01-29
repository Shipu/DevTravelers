<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AttributeRequest as StoreRequest;
use App\Http\Requests\AttributeRequest as UpdateRequest;
use App\Models\AttributeValue;

class AttributeCrudController extends CrudController
{

    public function setupCrudFields()
    {
        $this->crud->addFields([
            [
                'label' => trans('validation.attributes.name'),
                'name'  => 'name',
                'type'  => 'text',
            ],
            [
                'label'      => trans('validation.attributes.type'),
                'name'       => 'type',
                'type'       => 'select_from_array',
                'options'    => array_merge([
                    '0' => '--'
                ], trans('attribute.type')),
                'attributes' => [
                    'id' => 'attribute_type'
                ]
            ],
            [
                'label'        => trans('attribute.default') . " " . trans('attribute.type.media'),
                'name'         => "media",
                'type'         => 'attribute_type_image',
                'default'      => 'default.png',
                'upload'       => true,
                'aspect_ratio' => 0,
                'view_namespace' => 'backpack.crud.fields'
            ],
            [
                'label' => trans('attribute.name'),
                'name'  => 'attribute_types',
                'type'  => 'attribute_types',
                'view_namespace' => 'backpack.crud.fields'
            ]
        ]);
    }

    public function setupDataTable()
    {
        $this->crud->addColumns([
            [
                'label' => trans("validation.attributes.name"), // Table column heading
                'type' => "text",
                'name' => 'name',
            ],
            [
                'label' => trans("validation.attributes.type"), // Table column heading
                'type' => "text",
                'name' => 'type',
            ],
        ]);
    }

    public function store( StoreRequest $request )
    {
        $redirect_location = parent::storeCrud();
        $entryId           = $this->crud->entry->id;

        // Define Storage disk for media attribute type
        $disk = "media";

        // Init attributeValue array
        $attributeValue = [];

        switch ( $request->type ) {
            case 'text':
            case 'textarea':
            case 'date':
                $attributeValue = [
                    'attribute_id' => $entryId,
                    'value'        => $request->{$request->type}
                ];
                break;

            case 'multiple_select':
            case 'dropdown':
                foreach ( $request->option as $option ) {
                    $attributeValue[] = [
                        'attribute_id' => $entryId,
                        'value'        => $option
                    ];
                }
                break;

            case 'media':
                if ( starts_with($request->media, 'data:image') ) {
                    // 1. Make the image
                    $image = \Image::make($request->media);
                    // 2. Generate a filename.
                    $filename = md5($request->media . time()) . '.jpg';
                    // 3. Store the image on disk.
                    \Storage::disk($disk)->put($filename, $image->stream());
                    // 4. Save the path to attributes_value
                    $attributeValue = [ 'attribute_id' => $entryId, 'value' => $filename ];
                }
                break;
        }

        $insert_attribute_values = AttributeValue::insert($attributeValue);

        return $redirect_location;
    }

    public function update( UpdateRequest $request, AttributeValue $attributeValue )
    {
        // Define Storage disk for media attribute type
        $disk = 'media';

        switch ( $request->type ) {
            case 'text':
            case 'textarea':
            case 'date':
                $attributeValue->where('attribute_id', $request->id)->update([ 'value' => $request->{$request->type} ]);
                break;

            case 'multiple_select':
            case 'dropdown':
                if ( isset($request->current_option) ) {
                    foreach ( $request->current_option as $key => $current_option ) {
                        $attributeValue->where('id', $key)->update([ 'value' => $current_option ]);
                    }
                }

                if ( isset($request->option) ) {
                    foreach ( $request->option as $option ) {
                        $attribute_values[] = [ 'attribute_id' => $request->id, 'value' => $option ];
                    }

                    $insert_new_option = $attributeValue->insert($attribute_values);
                }
                break;

            case 'media':
                if ( starts_with($request->media, 'data:image') ) {
                    // 0. Get current image filename
                    $current_image_filename = $attributeValue->where('attribute_id', $request->id)->first()->value;
                    // 1. delete image file if exist
                    if ( \Storage::disk($disk)->has($current_image_filename) ) {
                        \Storage::disk($disk)->delete($current_image_filename);
                    }
                    // 2. Make the image
                    $image = \Image::make($request->media);
                    // 3. Generate a filename.
                    $filename = md5($request->media . time()) . '.jpg';
                    // 4. Store the image on disk.
                    \Storage::disk($disk)->put($filename, $image->stream());
                    // 5. Update image filename to attributes_value
                    $attributeValue->where('attribute_id', $request->id)->update([ 'value' => $filename ]);
                }
                break;
        }

        $redirect_location = parent::updateCrud();

        return $redirect_location;
    }
}
