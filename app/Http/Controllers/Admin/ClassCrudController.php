<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ClassRequest as StoreRequest;
use App\Http\Requests\ClassRequest as UpdateRequest;
use App\Models\AcademicClass;
use App\Models\BackpackUser;

class ClassCrudController extends CrudController
{
    public $modelClass = AcademicClass::class;

    public $entityName = 'class';

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
                'name' => 'numeric_value',
                'label' => trans('validation.attributes.numeric_value'),
                'type' => 'number',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name' => 'group',
                'label' => trans('validation.attributes.group'),
                'type' => 'select2_from_array',
                'options' => trans('studentgroup'),
                'allows_null' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
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
            [
                'label' => trans("validation.attributes.numeric_value"), // Table column heading
                'type' => "text",
                'name' => 'numeric_value'
            ],
            'name',
            [
                'label' => trans("validation.attributes.teacher"), // Table column heading
                'type' => "text",
                'name' => 'teacher.user.name'
            ],
            [
                'label' => trans("validation.attributes.group"), // Table column heading
                'type' => "radio",
                'name' => 'group',
                'options' => trans('studentgroup')
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
