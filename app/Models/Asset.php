<?php

namespace App\Models;

class Asset extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [ 'name', 'price', 'attribute_set_id' ];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'asset_attributes', 'asset_id', 'attribute_id')->withPivot('value');
    }
}
