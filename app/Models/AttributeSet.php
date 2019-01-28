<?php

namespace App\Models;

class AttributeSet extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [ 'name' ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ( $model ) {
            $model->attributes()->detach();
        });
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_attribute_set', 'attribute_set_id', 'attribute_id');
    }
}
