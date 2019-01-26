<?php

namespace App\Http\Controllers\Admin;

use App\Models\BackpackUser;
use App\Http\Requests\UserCrudRequest as StoreRequest;
use App\Http\Requests\UserCrudRequest as UpdateRequest;
use App\Traits\HandleBackpackCrudPassword;

class UserCrudController extends CrudController
{
    use HandleBackpackCrudPassword;

    public $modelClass = BackpackUser::class;

    protected function beforeCrudSetup()
    {
        $this->modelClass   = config('backpack.permissionmanager.models.user');
        $this->crudRouteUrl = backpack_url('user');
        $this->entityName   = 'user';
        $this->crud->setCreateContentClass('col-md-12');
        $this->crud->setEditContentClass('col-md-12');
    }

    protected function setupCrudFields()
    {
        $this->crud->addFields([
            [
                'name'  => 'name',
                'label' => trans('permissionmanager.name'),
                'type'  => 'text',
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'username',
                'label' => trans('permissionmanager.username'),
                'type'  => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'email',
                'label' => trans('permissionmanager.email'),
                'type'  => 'email',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'password',
                'label' => trans('permissionmanager.password'),
                'type'  => 'password',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ]
            ],
            [
                'name'  => 'password_confirmation',
                'label' => trans('permissionmanager.password_confirmation'),
                'type'  => 'password',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ]
            ],
            [
                // two interconnected entities
                'label'             => trans('permissionmanager.user_role_permission'),
                'field_unique_name' => 'user_role_permission',
                'type'              => 'checklist_dependency',
                'name'              => 'roles_and_permissions', // the methods that defines the relationship in your Model
                'subfields'         => [
                    'primary'   => [
                        'label'            => trans('permissionmanager.roles'),
                        'name'             => 'roles', // the method that defines the relationship in your Model
                        'entity'           => 'roles', // the method that defines the relationship in your Model
                        'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                        'attribute'        => 'name', // foreign key attribute that is shown to user
                        'model'            => config('permission.models.role'), // foreign key model
                        'pivot'            => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns'   => 3, //can be 1,2,3,4,6
                    ],
                    'secondary' => [
                        'label'          => ucfirst(trans('permissionmanager.permission_singular')),
                        'name'           => 'permissions', // the method that defines the relationship in your Model
                        'entity'         => 'permissions', // the method that defines the relationship in your Model
                        'entity_primary' => 'roles', // the method that defines the relationship in your Model
                        'attribute'      => 'name', // foreign key attribute that is shown to user
                        'model'          => config('permission.models.permission'), // foreign key model
                        'pivot'          => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns' => 3, //can be 1,2,3,4,6
                    ],
                ],
            ],
        ]);

        $this->crud->addFields([
            [
                'name'              => 'password',
                'label'             => trans('permissionmanager.password'),
                'type'              => 'password',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes'        => [
                    'required' => true
                ]
            ],
            [
                'name'              => 'password_confirmation',
                'label'             => trans('permissionmanager.password_confirmation'),
                'type'              => 'password',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes'        => [
                    'required' => true
                ]
            ],
        ], 'create');
    }

    protected function setupDataTable()
    {
        $this->crud->setColumns([
            [
                'name'  => 'name',
                'label' => trans('permissionmanager.name'),
                'type'  => 'text',
            ],
            [
                'name'  => 'username',
                'label' => trans('permissionmanager.username'),
                'type'  => 'text',
            ],
            [
                'name'  => 'email',
                'label' => trans('permissionmanager.email'),
                'type'  => 'email',
            ],
            [ // n-n relationship (with pivot table)
                'label'     => trans('permissionmanager.roles'), // Table column heading
                'type'      => 'select_multiple',
                'name'      => 'roles', // the method that defines the relationship in your Model
                'entity'    => 'roles', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => config('permission.models.role'), // foreign key model
            ],
            [ // n-n relationship (with pivot table)
                'label'     => trans('permissionmanager.extra_permissions'), // Table column heading
                'type'      => 'select_multiple',
                'name'      => 'permissions', // the method that defines the relationship in your Model
                'entity'    => 'permissions', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => config('permission.models.permission'), // foreign key model
            ],
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
        $this->handlePasswordInput($request);

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
        $this->handlePasswordInput($request);

        return parent::updateCrud($request);
    }
}
