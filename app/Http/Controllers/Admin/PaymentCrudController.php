<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AssetRequest as StoreRequest;
use App\Http\Requests\AssetRequest as UpdateRequest;
use App\Models\Attribute;
use App\Models\BackpackUser;
use App\Models\Event;
use Illuminate\Http\Request;

class PaymentCrudController extends CrudController
{

    public function setupCrudFields()
    {
        $this->crud->addFields([
            [
                'name' => 'event_id',
                'label' => trans('validation.attributes.event'),
                'type' => 'select2',
                'entity' => 'assets',
                'model' => Event::class,
                'attribute' => 'name',
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name' => 'sender_id',
                'label' => trans('validation.attributes.sender'),
                'type' => 'select2',
                'entity' => 'assets',
                'model' => BackpackUser::class,
                'attribute' => 'name',
                'attributes' => [
                    'required' => true
                ]
            ],
            [   // Checkbox
                'name' => 'amount',
                'label' => trans('validation.attributes.amount'),
                'type' => 'number',
                'attributes' => [
                    'required' => true
                ]
            ],
            [   // Checkbox
                'name' => 'transaction_id',
                'label' => trans('validation.attributes.transaction_ref'),
                'type' => 'text',
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'    => 'purpose',
                'label'   => trans('validation.attributes.purpose'),
                'type'    => 'select_from_array',
                'options' => trans('payment.purpose'),
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'    => 'channel',
                'label'   => trans('validation.attributes.channel'),
                'type'    => 'select_from_array',
                'options' => trans('payment.channel'),
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'    => 'status',
                'label'   => trans_choice('entity.payment', 1).' '.trans('validation.attributes.status'),
                'type'    => 'select_from_array',
                'options' => trans('payment.statuses'),
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'paid_at',
                'label' => trans('validation.attributes.paid_at'),
                'type'  => 'datetime_picker',
                'datetime_picker_options' => [
                    'format' => 'DD/MM/YYYY HH:mm',
                    'language' => 'en',
                    'showTodayButton' => true,
                    'showClear' => true
                ],
                'allows_null' => false,
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name' => 'remarks',
                'label' => trans('validation.attributes.remarks'),
                'type' => 'textarea',
            ],
        ]);
    }

    public function setupDataTable()
    {
        $this->crud->addColumns([
            [
                'name'  => 'amount',
                'label' => trans('validation.attributes.amount'),
                'type' => 'text',
            ]
        ]);
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
