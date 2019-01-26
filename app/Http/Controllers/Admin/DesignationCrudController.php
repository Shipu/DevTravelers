<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DesignationRequest as StoreRequest;
use App\Http\Requests\DesignationRequest as UpdateRequest;

class DesignationCrudController extends CrudController
{

    protected function afterCrudSetup()
    {
        $this->crud->enableBulkActions();
        $this->crud->addBulkDeleteButton();
    }

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
                'name'    => 'status',
                'label'   => trans('validation.attributes.status'),
                'type'    => 'select_from_array',
                'options' => trans('statuses'),
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
                'type' => 'checkbox',
                'name' => 'bulk_actions',
                'label' => '<input type="checkbox" class="crud_bulk_actions_main_checkbox"/>',
            ],
            'title',
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
