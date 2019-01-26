<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ClassRequest as StoreRequest;
use App\Http\Requests\ClassRequest as UpdateRequest;
use App\Models\AcademicClass;
use App\Models\BackpackUser;

class EventCrudController extends CrudController
{
    protected function beforeCrudSetup()
    {
        $this->crud->setCreateContentClass('col-md-12');
        $this->crud->setEditContentClass('col-md-12');
    }

    protected function setupCrudFields()
    {
        $this->crud->addFields([
            [
                'name'  => 'name',
                'label' => trans('validation.attributes.name'),
                'type'  => 'text',
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'event_type',
                'label' => trans('validation.attributes.type'),
                'type'  => 'select2_from_array',
                'options' => trans('event_types'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'paid_event',
                'label' => trans('validation.attributes.paid_event'),
                'type'  => 'select2_from_array',
                'options' => trans('paid_types'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name' => 'lat',
                'label' => trans('validation.attributes.location').' '.trans('validation.attributes.lat'),
                'type' => 'text',
                'fake' => true,
                'store_in' => 'location',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name' => 'lng',
                'label' => trans('validation.attributes.location').' '.trans('validation.attributes.lng'),
                'type' => 'text',
                'fake' => true,
                'store_in' => 'location',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'start',
                'label' => trans('validation.attributes.start_date'),
                'type'  => 'datetime_picker',
                'datetime_picker_options' => [
                    'format' => 'DD/MM/YYYY HH:mm',
                    'language' => 'en'
                ],
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'end',
                'label' => trans('validation.attributes.end_date'),
                'type'  => 'datetime_picker',
                'datetime_picker_options' => [
                    'format' => 'DD/MM/YYYY HH:mm',
                    'language' => 'en'
                ],
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'registration_start',
                'label' => trans('validation.attributes.registration_start'),
                'type'  => 'datetime_picker',
                'datetime_picker_options' => [
                    'format' => 'DD/MM/YYYY HH:mm',
                    'language' => 'en'
                ],
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'registration_end',
                'label' => trans('validation.attributes.registration_end'),
                'type'  => 'datetime_picker',
                'datetime_picker_options' => [
                    'format' => 'DD/MM/YYYY HH:mm',
                    'language' => 'en'
                ],
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name' => 'description',
                'label' => trans('validation.attributes.description'),
                'type' => 'wysiwyg',
            ],
            [
                'name'    => 'status',
                'label'   => trans('validation.attributes.status'),
                'type'    => 'select_from_array',
                'options' => trans('statuses'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [   // Checkbox
                'name' => 'amount',
                'label' => trans('validation.attributes.amount'),
                'type' => 'number',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [   // Checkbox
                'name' => 'approximate_amount',
                'label' => trans('validation.attributes.approximate').' '.trans('validation.attributes.amount'),
                'type' => 'checkbox',
                'default' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group row col-md-4',
                ]
            ],
            [ // Table
                'name' => 'payment_options',
                'label' => trans('validation.attributes.payment_options'),
                'type' => 'table',
                'columns' => [
                    'payment_method' => 'Payment Method',
                    'name' => 'Name',
                    'number' => 'Number',
                    'amount' => 'Amount'
                ],
                'max' => 5, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'default' => [
                    [
                        'payment_method' => 'Bkash',
                        'name' => 'Naim',
                        'number' => '01616022669'
                    ],
                    [
                        'payment_method' => 'Roket',
                        'name' => 'Nahid',
                        'number' => '01707722669'
                    ]
                ]
            ],
            [
                'name' => 'remarks',
                'label' => trans('validation.attributes.remarks'),
                'type' => 'textarea',
            ],

        ]);
    }

    public function beforeEdit($entry)
    {

    }

    protected function setupDataTable()
    {
        $this->crud->setColumns([
            'name',
            [
                'label' => trans("validation.attributes.status"), // Table column heading
                'type' => "radio",
                'name' => 'status', // the column that contains the ID of that connected entity;
                'options' => trans('statuses'),
            ]
        ]);
    }

    /**
     * Store a newly created resource in the database.
     *
     * @param StoreRequest $request - type injection used for validation using Requests
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Backpack\CRUD\Exception\AccessDeniedException
     */
    public function store( StoreRequest $request )
    {
        return parent::storeCrud($request);
    }

    /**
     * Update the specified resource in the database.
     *
     * @param UpdateRequest $request - type injection used for validation using Requests
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Backpack\CRUD\Exception\AccessDeniedException
     */
    public function update( UpdateRequest $request )
    {
        return parent::updateCrud($request);
    }
}
