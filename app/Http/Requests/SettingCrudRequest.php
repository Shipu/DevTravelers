<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingCrudRequest extends FormRequest
{
    public function authorize()
    {
        return backpack_auth()->check();
    }

    public function rules()
    {
        return [];
    }
}
