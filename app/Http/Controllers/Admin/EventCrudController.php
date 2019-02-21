<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EventCrudRequest as StoreRequest;
use App\Http\Requests\EventCrudRequest as UpdateRequest;
use App\Models\Product;
use App\Models\Event;
use Spatie\MediaLibrary\Models\Media;

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
                    'language' => 'en',
                    'showTodayButton' => true,
                    'showClear' => true
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
                    'language' => 'en',
                    'showTodayButton' => true,
                    'showClear' => true
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
                    'language' => 'en',
                    'showTodayButton' => true,
                    'showClear' => true
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
                    'language' => 'en',
                    'showTodayButton' => true,
                    'showClear' => true
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
                'name' => 'products',
                'label' => trans_choice('entity.product', 0),
                'type' => 'select2_multiple',
                'entity' => 'products',
                'model' => Product::class,
                'attribute' => 'name',
                'pivot' => true
            ],
            [
                'name' => 'remarks',
                'label' => trans('validation.attributes.remarks'),
                'type' => 'textarea',
            ],
            [
                'name'  => 'banner',
                'label' => trans('validation.attributes.banner'),
                'type' => 'upload_media',
                'multiple' => false,
                'upload' => true,
                'is_image' => true,
                'value' => null,
                'relation' => 'media',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12',
                ]
            ],

        ]);
    }

    protected function setupDataTable()
    {
        $this->crud->setColumns([
            'name',
            'amount',
            [
                'name' => "event_type", // The db column name
                'label' => trans("validation.attributes.type"), // Table column heading
                'type' => "radio",
                'options' => trans('event_types'),
            ],
            [
                'name' => "paid_event", // The db column name
                'label' => trans("validation.attributes.paid_event"), // Table column heading
                'type' => "radio",
                'options' => trans('paid_types'),
            ],
            [
                'name' => "start", // The db column name
                'label' => trans("validation.attributes.start_date"), // Table column heading
                'type' => "datetime",
                'format' => 'l j F Y H:i:s'
            ],
            [
                'name' => "end", // The db column name
                'label' => trans("validation.attributes.end_date"), // Table column heading
                'type' => "datetime",
                'format' => 'l j F Y H:i:s'
            ],
            [
                'name' => "registration_start", // The db column name
                'label' => trans("validation.attributes.registration_start"), // Table column heading
                'type' => "datetime",
                'format' => 'l j F Y H:i:s'
            ],
            [
                'name' => "registration_end", // The db column name
                'label' => trans("validation.attributes.registration_end"), // Table column heading
                'type' => "datetime",
                'format' => 'l j F Y H:i:s'
            ],
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

    public function afterStore($request, $entry)
    {
        $this->saveImage($entry, $request);
    }

    public function afterUpdate($request, $entry)
    {
        $this->saveImage($entry, $request, true);
    }

    public function saveImage($entry, $request, $update = false)
    {
        if ($update && !empty($request['clear_banner'])) {
            $clearImage = !is_array($request['clear_banner']) ? [$request['clear_banner']] : $request['clear_banner'];
            $entry->media()->whereIn('id', $clearImage)->get()->each->delete();
        }

        if (!empty($request['banner_existing'])) {
            $existingImage = !is_array($request['banner_existing']) ? [$request['banner_existing']] : $request['banner_existing'];
            Media::setNewOrder($existingImage);
        }

        if ($request->hasFile('banner')) {
            $entry->addMultipleMediaFromRequest(['banner'])
                ->each->withResponsiveImages()
                ->each->toMediaCollection(Event::IMAGE_COLLECTION_NAME);
        }
    }
}
