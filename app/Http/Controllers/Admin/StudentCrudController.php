<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StudentCrudRequest as StoreRequest;
use App\Http\Requests\StudentCrudRequest as UpdateRequest;
use App\Http\Requests\StudentCrudRequest;
use App\Models\AcademicClass;
use App\Models\BackpackUser;
use App\Models\Section;
use App\Models\Subject;
use App\Traits\HandleBackpackCrudPassword;
use Spatie\MediaLibrary\Models\Media;

class StudentCrudController extends CrudController
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
            [   // CustomHTML
                'name' => 'note_info',
                'type' => 'custom_html',
                'value' => '<div class="callout">
                                <p><b>Note:</b> Create a class and section before create new student. And subject if student have elective subject.</p>
                            </div>',
                'wrapperAttributes' => [
                    'class' => '',
                ],
            ],
            [   // CustomHTML
                'name' => 'student_info',
                'type' => 'custom_html',
                'value' => '<hr><h4 class="text-light-blue">Student Info:</h4><br>',
                'wrapperAttributes' => [
                    'class' => '',
                ],
            ],
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
                    'class' => 'form-group col-md-2',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'blood_group',
                'label' => trans('validation.attributes.blood_group'),
                'type'  => 'select2_from_array',
                'options' => trans('bloodgroup'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-2',
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
                    'class' => 'form-group col-md-2',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'nationality',
                'label' => trans('validation.attributes.nationality'),
                'type'  => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
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
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'extra_curricular_activity',
                'label' => trans('validation.attributes.extra_curricular_activity'),
                'type'  => 'textarea',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ]
            ],
            [
                'name'  => 'note',
                'label' => trans('validation.attributes.note'),
                'type'  => 'textarea',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
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
                'name' => 'guardian_info_separator',
                'type' => 'custom_html',
                'value' => '<hr>',
            ],
            [   // CustomHTML
                'name' => 'guardian_info',
                'type' => 'custom_html',
                'value' => '<hr><h4 class="text-light-blue">Guardian Info:</h4><br>',
                'wrapperAttributes' => [
                    'class' => '',
                ],
            ],
            [
                'name'  => 'father_name',
                'label' => trans('validation.attributes.father').' '.trans('validation.attributes.name'),
                'type'  => 'text',
                'fake' => true,
                'store_in' => 'guardian_info',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'father_phone',
                'label' => trans('validation.attributes.father').' '.trans('validation.attributes.phone_or_mobile'),
                'type'  => 'text',
                'fake' => true,
                'store_in' => 'guardian_info',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'mother_name',
                'label' => trans('validation.attributes.mother').' '.trans('validation.attributes.name'),
                'type'  => 'text',
                'fake' => true,
                'store_in' => 'guardian_info',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'mother_phone',
                'label' => trans('validation.attributes.mother').' '.trans('validation.attributes.phone_or_mobile'),
                'type'  => 'text',
                'fake' => true,
                'store_in' => 'guardian_info',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'local_guardian',
                'label' => trans('validation.attributes.local').' '.trans('validation.attributes.guardian'),
                'type'  => 'text',
                'fake' => true,
                'store_in' => 'guardian_info',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'local_guardian_phone',
                'label' => trans('validation.attributes.guardian').' '.trans('validation.attributes.phone_or_mobile'),
                'type'  => 'text',
                'fake' => true,
                'store_in' => 'guardian_info',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'present_address',
                'label' => trans('validation.attributes.present').' '.trans('validation.attributes.address'),
                'type'  => 'textarea',
                'fake' => true,
                'store_in' => 'guardian_info',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ]
            ],
            [
                'name'  => 'permanent_address',
                'label' => trans('validation.attributes.permanent').' '.trans('validation.attributes.address'),
                'type'  => 'textarea',
                'fake' => true,
                'store_in' => 'guardian_info',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ]
            ],
            [   // CustomHTML
                'name' => 'academic_info_separator',
                'type' => 'custom_html',
                'value' => '<hr>',
            ],
            [   // CustomHTML
                'name' => 'academic_info',
                'type' => 'custom_html',
                'value' => '<h4 class="text-light-blue">Academic Info:</h4><br>',
                'wrapperAttributes' => [
                    'class' => '',
                ],
            ],
            [
                'name'  => 'registration_no',
                'label' => trans('validation.attributes.registration').' '.trans('validation.attributes.no'),
                'type'  => 'text',
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
                'entity' => 'student_class',
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
                'name' => 'section_id',
                'label' => trans('validation.attributes.section'),
                'type' => 'select2',
                'entity' => 'section',
                'attribute' => 'name',
                'model' => Section::class,
                'allows_null' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'shift',
                'label' => trans('validation.attributes.shift'),
                'type'  => 'select2_from_array',
                'options' => trans('shift'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-2',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [
                'name'  => 'national_id',
                'label' => trans('validation.attributes.id').' '.trans('validation.attributes.card').' '. trans('validation.attributes.no'),
                'type'  => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name'  => 'roll',
                'label' => trans('validation.attributes.roll'),
                'type'  => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name'  => 'board_registration_no',
                'label' => trans('validation.attributes.board').' '.trans('validation.attributes.registration').' '.trans('validation.attributes.no'),
                'type'  => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
            ],
            [
                'name' => 'elective_subject_id',
                'label' => trans('validation.attributes.elective').' '.trans('validation.attributes.subject').'/'.trans('validation.attributes.fourth').' '.trans('validation.attributes.subject'),
                'type' => 'select2',
                'entity' => 'elective_subject',
                'attribute' => 'name',
                'model' => Subject::class,
                'allows_null' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            [   // CustomHTML
                'name' => 'access_info_separator',
                'type' => 'custom_html',
                'value' => '<hr>',
            ],
            [   // CustomHTML
                'name' => 'access_info',
                'type' => 'custom_html',
                'value' => '<h4 class="text-light-blue">Access Info:</h4><br>',
                'wrapperAttributes' => [
                    'class' => '',
                ],
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
            ]
        ]);
    }

    protected function setupDataTable()
    {
        $this->crud->setColumns([
            [
                'label' => trans('validation.attributes.registration').' '.trans('validation.attributes.no'),
                'type' => "text",
                'name' => 'registration_no',
            ],
            'name',
            'gender',
            [
                'label' => trans("validation.attributes.class"), // Table column heading
                'type' => "select",
                'name' => 'class_id',
                'entity' => 'student_class',
                'attribute' => 'name',
                'model' => AcademicClass::class
            ],
            [
                'label' => trans("validation.attributes.class"), // Table column heading
                'type' => "select",
                'name' => 'section_id',
                'entity' => 'section',
                'attribute' => 'name',
                'model' => Section::class
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

    protected function saveUser(StudentCrudRequest $studentCrudRequest)
    {
        $userRequest = [
            'name' => $studentCrudRequest->name,
            'username' => $studentCrudRequest->username,
            'email' => $studentCrudRequest->email,
        ];

        if($studentCrudRequest->input('password')) {
            $userRequest['password'] = $studentCrudRequest->password;
        }

        $user = BackpackUser::updateOrCreate($userRequest);

        if ( !blank($user) ) {
            $studentCrudRequest->request->set('user_id', $user->id);

            $user->syncRoles(['student']);

            $user->phone()->updateOrCreate([
                'number' => $studentCrudRequest->phone
            ]);

            $removeFromRequests = [
                'phone', 'name', 'username', 'email', 'password'
            ];
            foreach ($removeFromRequests as $removeFromRequest) {
                $studentCrudRequest->request->remove($removeFromRequest);
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
