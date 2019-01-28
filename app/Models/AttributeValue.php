<?php

namespace App\Models;

class AttributeValue extends BaseModel
{

    public $timestamps = false;

    protected $fillable = [
        'attribute_id',
        'value'
    ];
}
