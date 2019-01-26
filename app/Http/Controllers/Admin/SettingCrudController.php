<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SettingCrudRequest as StoreRequest;
use App\Models\AcademicYear;
use App\Models\Setting;

class SettingCrudController extends CrudController
{
    protected function beforeCrudSetup()
    {
        $this->crud->denyAccess(['list', 'update', 'delete']);
    }

    public function index()
    {
        return $this->create();
    }

    protected function afterCreate()
    {
        $this->data[ 'saveAction' ]['cancel'] = false;
        $this->data[ 'saveAction' ]['active']['label'] = 'Save';
    }

    protected function setupCrudFields()
    {
        $this->crud->addFields([
            [
                'name'  => 'site_name',
                'label' => trans('validation.attributes.site_name'),
                'type'  => 'text',
                'attributes' => [
                    'required' => true
                ],
                'default' => Setting::get('site_name')
            ],
        ]);
    }

    /**
     * Store a newly created resource in the database.
     *
     * @param StoreRequest $request - type injection used for validation using Requests
     *
     * @return void
     */
    public function store( StoreRequest $request )
    {
        $request = $request->except([ 'save_action', '_token', '_method', 'http_referrer' ]);

        Setting::set($request);

        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return redirect()->route('crud.setting.index');
    }
}
