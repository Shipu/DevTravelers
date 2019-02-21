<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest as StoreRequest;
use App\Http\Requests\ProductRequest as UpdateRequest;
use App\Models\Product;

class ProductCrudController extends CrudController
{
    protected function beforeCrudSetup()
    {
        $this->crud->setCreateContentClass('col-md-12');
        $this->crud->setEditContentClass('col-md-12');
    }

    public function setupCrudFields()
    {
        $this->crud->addFields([
            [
                'name'  => 'name',
                'label' => trans('validation.attributes.name'),
                'type'  => 'text',
                'tab'   => trans('validation.attributes.general_tab'),
            ],
            [
                'name'  => 'description',
                'label' => trans('validation.attributes.description'),
                // 'type'  => 'ckeditor',
                'type'  => 'wysiwyg',
                'tab'   => trans('validation.attributes.general_tab'),
            ],
            [
                'name'  => 'sku',
                'label' => trans('validation.attributes.sku'),
                'type'  => 'text',
                'tab'   => trans('validation.attributes.general_tab'),
            ],
            [
                'name'  => 'stock',
                'label' => trans('validation.attributes.stock'),
                'type'  => 'number',
                'tab'   => trans('validation.attributes.general_tab'),
            ],
            [
                'name'  => 'price',
                'label' => trans('validation.attributes.price'),
                'type'  => 'text',
                'tab'   => trans('validation.attributes.general_tab'),
            ],
            [
                'label'    => trans("validation.attributes.image"),
                'name'     => 'base_product_images',
                'type'     => 'upload_media',
                'multiple' => true,
                'upload'   => true,
                'is_image' => true,
                'value'    => null,
                'relation' => 'media',
                'tab'      => trans('validation.attributes.general_tab'),
            ],
            [
                'name'    => 'status',
                'label'   => trans('validation.attributes.status'),
                'type'    => 'select_from_array',
                'options' => trans('statuses'),
                'tab'     => trans('validation.attributes.general_tab'),
            ],
            [
                'name'       => 'attribute_set_id',
                'label'      => trans_choice('entity.attribute-set', 0),
                'type'       => 'select2',
                'entity'     => 'attributes',
                'attribute'  => 'name',
                'model'      => "App\Models\AttributeSet",
                'attributes' => [
                    'id' => 'attributes-set'
                ],
                'fake' => true,
                'tab'        => trans('validation.attributes.attributes_tab'),
            ],
            [
                'name'           => 'attribute_types',
                'label'          => trans('validation.attributes.name'),
                'type'           => 'attributes',
                'view_namespace' => 'backpack.crud.fields',
                'tab'            => trans('validation.attributes.attributes_tab'),
            ],
            [
                'name'           => 'variants',
                'label'          => trans('validation.attributes.variants_tab'),
                'type'           => 'variants',
                'attributes'     => [
                    'id' => 'variants-set'
                ],
                'view_namespace' => 'backpack.crud.fields',
                'tab'            => trans('validation.attributes.variants_tab'),
            ]
        ]);
    }

    public function setupDataTable()
    {
        $this->crud->addColumns([
            [
                'name'  => 'name',
                'label' => trans('validation.attributes.name'),
            ]
        ]);
    }

    public function beforeEdit($entity)
    {
        $this->modifyFieldSetting('attribute_set_id', [
            'value' => $entity->attribute_set_id
        ]);
    }

    public function beforeSearch()
    {
        $this->crud->addClause('whereNull', 'parent_id');
    }

    public function store( StoreRequest $request )
    {
        $redirect_location = parent::storeCrud();

        return $redirect_location;
    }

    public function update( UpdateRequest $request )
    {
        $redirect_location = parent::updateCrud();

        return $redirect_location;
    }

    public function afterStore($request, $entry)
    {
        $this->saveAttributeSet($request, $entry);
        $this->saveAttributes($request->input('attributes'), $entry);
        $this->saveVariants($request, $entry);
    }

    public function afterUpdate($request, $entry)
    {
        $this->saveAttributeSet($request, $entry);
        $this->saveAttributes($request->input('attributes'), $entry, true);
        $this->saveVariants($request, $entry, true);
    }

    public function saveAttributeSet( $request, $entry )
    {
        $entry->attribute_set_id = $request->get('attribute_set_id');
        $entry->save();
    }

    public function saveAttributes($attributes, $entry, $update = false)
    {
        if ($attributes) {

            if($update) {
                $entry->attributes()->detach();
            }

            foreach ($attributes as $key => $attributeValue) {
                if (is_array($attributeValue)) {
                    foreach ($attributeValue as $value) {
                        $entry->attributes()->attach([$key => ['value' => $value]]);
                    }
                } else {
                    $entry->attributes()->attach([$key => ['value' => $attributeValue]]);
                }
            }
        }
    }

    public function saveVariantsAttributes($variantAttributes, $entry, $update = false)
    {
        if($entry->variants) {
            foreach ($entry->variants as $variant) {
                $attributes = $variant->attributes();
                if(!blank($attributes)) {
                    $variant->attributes()->detach();
                }
            }
        }

        foreach ($variantAttributes as $key => $attributeSets) {
            foreach ($attributeSets as $setId => $attributes) {
                foreach ($attributes as $key => $attr_value) {
                    if (is_array($attr_value)) {
                        foreach ($attr_value as $value) {
                            $entry->attributes()->attach([$key => ['value' => $value]]);
                        }
                    } else {
                        $entry->attributes()->attach([$key => ['value' => $attr_value]]);
                    }
                }
            }
        }
    }

    public function saveVariants($request, $entry, $update = false)
    {
        if($request->input('variant')) {
            $i = 0;
            $updatedVariants = [];
            $existingVariants = [];
            if($update) {
                $existingVariants = $entry->variants->keyBy('id');
                $updatedVariants = [];
                $i = count($existingVariants);
            }
            foreach ($request->input('variant') as $key => $variant) {
                if(!empty($variant['attribute_set_id'])) {
                    if(!empty($variant['id']) && $update && !empty($existingVariants[$variant['id']])) {
                        $saveVariant = $existingVariants[$variant['id']];
                        $updatedVariants[] = $variant['id'];
                    } else {
                        $saveVariant = new Product();
                        $saveVariant->name = '';
                        $saveVariant->slug = $this->crud->entry->slug.'_'.$i;
                        $saveVariant->parent_id = $entry->id;
                        $saveVariant->description = $entry->description;
                    }
                    $saveVariant->price = $variant['price'] ?? $entry->price;
                    $saveVariant->sku = $variant['sku'] ?? $entry->sku.'_'.$i;
                    $saveVariant->stock = $variant['stock'] ?? 0;
                    $saveVariant->status = $variant['status'] ?? $entry->status;
                    $saveVariant->attribute_set_id = $variant['attribute_set_id'];
                    $saveVariant->save();
                }
                $i++;
            }

            $deletedVariantIds = $existingVariants ? $existingVariants->pluck('id')->diff($updatedVariants)->toArray() : null;

            if (!empty($deletedVariantIds)) {
                Product::destroy($deletedVariantIds);
            }
        }

        if ($request->input('variant_attributes')) {
            $this->saveVariantsAttributes($request->input('variant_attributes'), $entry);
        }
    }
}
