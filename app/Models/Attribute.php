<?php

namespace App\Models;

class Attribute extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'type',
        'name'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ( $model ) {
            if ( count($model->sets) == 0 ) {
                $model->values()->delete();
            } else {
                return $model;
            }
        });
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    public function sets()
    {
        return $this->belongsToMany(AttributeSet::class, 'attribute_attribute_set', 'attribute_id', 'attribute_set_id');
    }

}
