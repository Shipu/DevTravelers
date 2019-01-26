<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentCrudRequest extends FormRequest
{
    protected $studentId = null;

    public function __construct()
    {
        if (!empty(\Route::current()->parameters['student'])) {
            $this->studentId = intval(\Route::current()->parameters['student']);
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
