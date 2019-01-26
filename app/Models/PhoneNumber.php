<?php

namespace App\Models;

class PhoneNumber extends BaseModel
{
    protected $table = 'phone_numbers';

    protected $fillable = [
        'number',
        'primary'
    ];

    public function owner()
    {
        return $this->morphTo();
    }
}
