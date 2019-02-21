<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AssetRequest as StoreRequest;
use App\Http\Requests\AssetRequest as UpdateRequest;
use App\Models\Attribute;
use Illuminate\Http\Request;

class ParticipantCrudController extends CrudController
{

    public function setupCrudFields()
    {
        $this->crud->addFields([

        ]);
    }

    public function setupDataTable()
    {
        $this->crud->addColumns([

        ]);
    }

	public function store(StoreRequest $request)
	{
        $redirect_location = parent::storeCrud();

        return $redirect_location;
	}

	public function update(UpdateRequest $request)
	{
        $redirect_location = parent::updateCrud();

        return $redirect_location;
	}
}
