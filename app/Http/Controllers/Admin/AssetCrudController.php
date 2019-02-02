<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AssetRequest as StoreRequest;
use App\Http\Requests\AssetRequest as UpdateRequest;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AssetCrudController extends CrudController
{

    public function setupCrudFields()
    {
        $this->crud->addFields([
            [
                'label'     => trans('validation.attributes.name'),
                'name'      => 'name',
                'type'      => 'text',
            ],
            [
                'label'     => trans('validation.attributes.price'),
                'name'      => 'price',
                'type'      => 'text',
            ],
            [
                'name'       => 'attribute_set_id',
                'label'      => trans_choice('entity.attribute-set', 0),
                'type'       => 'select2',
                'entity'     => 'attributes',
                'attribute'  => 'name',
                'model'      => "App\Models\AttributeSet",
                'attributes' => [
                    'id'    => 'attributes-set'
                ],
            ],
            [
                'name'  => 'attribute_types',
                'label' => trans('validation.attributes.name'),
                'type'  => 'attributes',
                'view_namespace' => 'backpack.crud.fields'
            ],
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

    public function ajaxGetAttributesBySetId(Request $request, Attribute $attribute)
    {
        // Init old as an empty array
        $old = [];

        // Set old inputs as array from $request
        if (isset($request->old)) {
            $old = json_decode($request->old, true);
        }

        // Get attributes with values by set id
        $attributes = $attribute->with('values')->whereHas('sets', function ($q) use ($request) {
            $q->where('id', $request->setId);
        })->get();

        $prefix = $request->get('name_prefix');
        $attributeSetId = $request->get('attribute_set_id');
        $variantIndex = $request->get('variant_index');

        return view('renders.product_attributes', compact('attributes', 'old', 'prefix', 'attributeSetId', 'variantIndex'));
    }

	public function store(StoreRequest $request)
	{
        $redirect_location = parent::storeCrud();

        return $redirect_location;
	}

	public function update(UpdateRequest $request)
	{
        $redirect_location = parent::updateCrud();

        return $redirect_location;
	}
}
