<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DesignationRequest as StoreRequest;
use App\Http\Requests\DesignationRequest as UpdateRequest;
use App\Models\AcademicYear;

class AcademicYearCrudController extends CrudController
{
    public $modelClass = AcademicYear::class;

    public $entityName = 'year';

    protected function setupCrudFields()
    {
        $this->crud->addFields([
            [
                'name'  => 'title',
                'label' => trans('validation.attributes.title'),
                'type'  => 'text',
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'start_date',
                'label' => trans('validation.attributes.start_date'),
                'type'  => 'date_picker',
                'date_picker_options' => [
                    'todayBtn' => true,
                    'yearRange'=> "-100:+100",
                    'format' => 'dd-mm-yyyy',
                    'changeMonth' => true,
                    'changeYear' => true,
                    'orientation' => "bottom auto"
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'end_date',
                'label' => trans('validation.attributes.end_date'),
                'type'  => 'date_picker',
                'date_picker_options' => [
                    'todayBtn' => true,
                    'yearRange'=> "-100:+100",
                    'format' => 'dd-mm-yyyy',
                    'changeMonth' => true,
                    'changeYear' => true,
                    'orientation' => "bottom auto"
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
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
        ]);
    }

    protected function setupDataTable()
    {
        $this->crud->setColumns([
            [
                'label' => '#', // Table column heading
                'type' => "text",
                'name' => 'id',
            ],
            'title',
            [
                'label' => trans("validation.attributes.start_date"), // Table column heading
                'type' => "date",
                'name' => 'start_date',
            ],
            [
                'label' => trans("validation.attributes.end_date"), // Table column heading
                'type' => "date",
                'name' => 'end_date',
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
}
