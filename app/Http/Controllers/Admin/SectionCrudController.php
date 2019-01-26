<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SectionRequest as StoreRequest;
use App\Http\Requests\SectionRequest as UpdateRequest;
use App\Models\AcademicClass;
use App\Models\BackpackUser;

class SectionCrudController extends CrudController
{
    public $entityName = 'section';

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
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name' => 'capacity',
                'label' => trans('validation.attributes.capacity'),
                'type' => 'number',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name' => 'class_id',
                'label' => trans('validation.attributes.class'),
                'type' => 'select2',
                'entity' => 'section_class',
                'attribute' => 'name',
                'model' => AcademicClass::class,
                'allows_null' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name' => 'teacher_id',
                'label' => trans('validation.attributes.teacher'),
                'type' => 'select2_from_array',
                'options' => $this->getTeachers(),
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
            [
                'name' => 'note',
                'label' => trans('validation.attributes.note'),
                'type' => 'textarea',
            ],

        ]);
    }

    public function beforeEdit($entry)
    {
        $this->modifyFieldSetting('teacher_id', [
            'value' => $entry->teacher->user_id
        ]);
    }

    protected function setupDataTable()
    {
        $this->crud->setColumns([
            'name',
            [
                'label' => trans("validation.attributes.capacity"), // Table column heading
                'type' => "text",
                'name' => 'capacity'
            ],
            [
                'label' => trans("validation.attributes.class"), // Table column heading
                'type' => "select",
                'name' => 'class_id',
                'entity' => 'section_class',
                'attribute' => 'name',
                'model' => AcademicClass::class
            ],
            [
                'label' => trans("validation.attributes.teacher"), // Table column heading
                'type' => "text",
                'name' => 'teacher.user.name'
            ],
            'note',
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
        $this->assignTeacher($entry, $request);
    }

    public function afterUpdate($request, $entry)
    {
        $this->assignTeacher($entry, $request);
    }

    public function assignTeacher($entry, $request)
    {
        $entry->teacher()->updateOrCreate([
            'user_id' => $request->get('teacher_id')
        ]);
    }

    public function getTeachers()
    {
        return BackpackUser::role('teacher')->get()->pluck('name', 'id');
    }
}
