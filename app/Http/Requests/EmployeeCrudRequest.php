<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeCrudRequest extends FormRequest
{
    protected $employeeId = null;

    public function __construct()
    {
        if (!empty(\Route::current()->parameters['employee'])) {
            $this->employeeId = intval(\Route::current()->parameters['employee']);
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
