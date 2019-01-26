<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmployeeCrudRequest;
use App\Models\BackpackUser;
use App\Http\Requests\EmployeeCrudRequest as StoreRequest;
use App\Http\Requests\EmployeeCrudRequest as UpdateRequest;
use App\Models\Employee;
use App\Traits\HandleBackpackCrudPassword;
use Spatie\MediaLibrary\Models\Media;

class EmployeeCrudController extends CrudController
{
    use HandleBackpackCrudPassword;

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
                'name'  => 'designation_id', // the db column for the foreign key
                'label' => trans('validation.attributes.designation'),
                'type'  => 'select2',
                'entity' => 'designation', // the method that defines the relationship in your Model
                'attribute' => 'title', // foreign key attribute that is shown to user
                'model' => "App\Models\Designation", // foreign key model
                'allows_null' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ]
            ],
            [
                'name'  => 'qualification',
                'label' => trans('validation.attributes.qualification'),
                'type'  => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ]
            ],
            [
                'name'  => 'dob',
                'label' => trans('validation.attributes.dob'),
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
                'name'  => 'gender',
                'label' => trans('validation.attributes.gender'),
                'type'  => 'select2_from_array',
                'options' => trans('gender'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'religion',
                'label' => trans('validation.attributes.religion'),
                'type'  => 'select2_from_array',
                'options' => trans('religion'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'email',
                'label' => trans('validation.attributes.email'),
                'type'  => 'email',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'phone',
                'label' => trans('validation.attributes.phone_or_mobile'),
                'type'  => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'address',
                'label' => trans('validation.attributes.address'),
                'type'  => 'textarea',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ]
            ],
            [
                'name'  => 'national_id',
                'label' => trans('validation.attributes.id_card_or_employee_id'),
                'type'  => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'joining_date',
                'label' => trans('validation.attributes.joining_date'),
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
                    'class' => 'form-group col-md-6',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'photo',
                'label' => trans('validation.attributes.photo'),
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
            [   // CustomHTML
                'name' => 'separator',
                'type' => 'custom_html',
                'value' => '<hr>'
            ],
            [
                'name'  => 'username',
                'label' => trans('validation.attributes.username'),
                'type'  => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
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
                    'class' => 'form-group col-md-4',
                ]
            ],
            [
                'name'  => 'password_confirmation',
                'label' => trans('permissionmanager.password_confirmation'),
                'type'  => 'password',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ]
            ],
            [   // CustomHTML
                'name' => 'separator',
                'type' => 'custom_html',
                'value' => '<hr>'
            ],
            [
                'label'     => trans('validation.attributes.roles'),
                'type'      => 'checklist',
                'name'      => 'roles',
                'attribute' => 'name',
                'model'     => "Backpack\PermissionManager\app\Models\Role",
            ],
        ]);

        $this->crud->addFields([
            [
                'name'              => 'password',
                'label'             => trans('permissionmanager.password'),
                'type'              => 'password',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
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
                    'class' => 'form-group col-md-4',
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
                'label' => trans('validation.attributes.name'),
                'type'  => 'model_function',
                'function_name' => 'getNameAttribute',
            ],
            [
                'name'  => 'username',
                'label' => trans('validation.attributes.username'),
                'type'  => 'model_function',
                'function_name' => 'getUserNameAttribute',
            ],
            [
                'name'  => 'email',
                'label' => trans('validation.attributes.email'),
                'type'  => 'model_function',
                'function_name' => 'getEmailAttribute',
            ],
            [
                'name'  => 'national_id',
                'label' => trans('validation.attributes.id_card_or_employee_id'),
                'type'  => 'text',
            ],
            [
                'name'  => 'roles',
                'label' => trans('validation.attributes.roles'),
                'type'  => 'model_function',
                'function_name' => 'getRoleNamesAttribute',
            ],
            [
                'name'  => 'joining_date',
                'label' => trans('validation.attributes.joining_date'),
                'type'  => 'date',
            ],
        ]);
    }

    /**
     * Store a newly created resource in the database.
     *
     * @param StoreRequest $request - type injection used for validation using Requests
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
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
     * @throws \Exception
     */
    public function update( UpdateRequest $request )
    {
        return parent::updateCrud($request);
    }

    protected function saveUser(EmployeeCrudRequest $employeeRequest)
    {
        $userRequest = [
            'name' => $employeeRequest->name,
            'username' => $employeeRequest->username,
            'email' => $employeeRequest->email,
        ];

        if($employeeRequest->input('password')) {
            $userRequest['password'] = $employeeRequest->password;
        }

        $user = BackpackUser::updateOrCreate($userRequest);

        if ( !blank($user) ) {
            $user->syncRoles(array_map('intval',$employeeRequest->roles));
            $user->phone()->updateOrCreate([
                'number' => $employeeRequest->phone
            ]);
            $user->address()->updateOrCreate([
                'address' => $employeeRequest->address
            ]);
            $employeeRequest->request->set('user_id', $user->id);

            $removeFromRequests = [
                'phone', 'address', 'name', 'username', 'email', 'password', 'roles'
            ];
            foreach ($removeFromRequests as $removeFromRequest) {
                $employeeRequest->request->remove($removeFromRequest);
            }

            return true;
        }

        return false;
    }

    public function beforeStore($request)
    {
        return $this->wrapInTransaction(function () use ($request) {
            $this->handlePasswordInput($request);
            $this->saveUser($request);
        }, $request);
    }

    public function beforeUpdate($request)
    {
        return $this->wrapInTransaction(function () use ($request) {
            $this->handlePasswordInput($request);
            $this->saveUser($request);
        }, $request);
    }

    public function afterStore($request, $entry)
    {
        $this->saveImage($entry->user, $request);
    }

    public function afterUpdate($request, $entry)
    {
        $this->saveImage($entry->user, $request, true);
    }

    public function saveImage($entry, $request, $update = false)
    {
        if ($update && !empty($request['clear_photo'])) {
            $clearImage = !is_array($request['clear_photo']) ? [$request['clear_photo']] : $request['clear_photo'];
            $entry->media()->whereIn('id', $clearImage)->get()->each->delete();
        }

        if (!empty($request['photo_existing'])) {
            $existingImage = !is_array($request['photo_existing']) ? [$request['photo_existing']] : $request['photo_existing'];
            Media::setNewOrder($existingImage);
        }

        if ($request->hasFile('photo')) {
            $entry->addMultipleMediaFromRequest(['photo'])
                ->each->withResponsiveImages()
                ->each->toMediaCollection(BackpackUser::IMAGE_COLLECTION_NAME);
        }
    }
}
