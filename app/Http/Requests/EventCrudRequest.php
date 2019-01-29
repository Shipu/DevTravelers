<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventCrudRequest extends FormRequest
{
    protected $eventId = null;

    public function __construct()
    {
        if (!empty(\Route::current()->parameters['event'])) {
            $this->eventId = intval(\Route::current()->parameters['event']);
        }

        parent::__construct();
    }

    public function authorize()
    {
        return backpack_auth()->check();
    }

    public function rules()
    {
        return [];
    }
}
