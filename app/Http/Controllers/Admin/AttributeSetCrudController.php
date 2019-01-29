<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AttributeSetRequest as StoreRequest;
use App\Http\Requests\AttributeSetRequest as UpdateRequest;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeSetCrudController extends CrudController
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
                'label'     => trans('validation.attributes.attributes'),
                'type'      => 'select2_multiple',
                'name'      => 'attributes',
                'entity'    => 'attributes',
                'attribute' => 'name',
                'model'     => "App\Models\Attribute",
                'pivot'     => true,
            ]
        ]);
    }

    public function setupDataTable()
    {
        $this->crud->addColumns([
            [
                'name'  => 'name',
                'label' => trans('attribute.name'),
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
