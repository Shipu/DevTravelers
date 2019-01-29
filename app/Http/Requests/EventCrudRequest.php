<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventCrudRequest extends FormRequest
{
    protected $eventId = null;

    public function __construct()
    {
        if (!empty(\Route::current()->parameters['user'])) {
            $this->eventId = intval(\Route::current()->parameters['user']);
        }

        parent::__construct();
    }

    public function authorize()
    {
        return backpack_auth()->check();
    }

    public function rules()
    {
        $rules                             = [];
        $rules[ 'name' ]                   = 'required|max:191';
        $rules[ 'password' ]               = 'required|confirmed';
        $rules[ 'username' ]               = 'required|unique:users,username';
        $rules[ 'email' ]                  = 'required|unique:users,email';

        if ($this->eventId) {
            unset($rules['password']);
            foreach (['email','username'] as $fieldName) {
                if (isset($rules[$fieldName])) {
                    $rules[$fieldName] = $rules[$fieldName] . ',' . $this->id;
                }
            }
        }

        return $rules;
    }
}
