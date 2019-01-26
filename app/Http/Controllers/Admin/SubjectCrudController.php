<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubjectRequest as StoreRequest;
use App\Http\Requests\SubjectRequest as UpdateRequest;
use App\Models\AcademicClass;

class SubjectCrudController extends CrudController
{
    protected function setupCrudFields()
    {
        $this->crud->addFields([
            [
                'name'  => 'name',
                'label' => trans('validation.attributes.name'),
                'type'  => 'text',
                'attributes' => [
                    'required' => true
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-5',
                ],
            ],
            [
                'name'  => 'code',
                'label' => trans('validation.attributes.code'),
                'type'  => 'text',
                'attributes' => [
                    'required' => true
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ],
            [
                'name' => 'type',
                'label' => trans('validation.attributes.type'),
                'type' => 'select2_from_array',
                'options' => trans('subjecttype'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name' => 'class_id',
                'label' => trans('validation.attributes.class'),
                'type' => 'select2',
                'entity' => 'subject_class',
                'attribute' => 'name',
                'model' => AcademicClass::class,
                'allows_null' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
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
                    'class' => 'form-group col-md-6',
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
            'name',
            'code',
            [
                'label' => trans("validation.attributes.type"), // Table column heading
                'type' => "radio",
                'name' => 'type',
                'options' => trans('subjecttype')
            ],
            [
                'label' => trans("validation.attributes.class"), // Table column heading
                'type' => "select",
                'name' => 'class_id',
                'entity' => 'subject_class',
                'attribute' => 'name',
                'model' => AcademicClass::class
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
