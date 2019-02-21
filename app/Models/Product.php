<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Traits\SluggableCrudTrait;

class Product extends BaseModel implements HasMedia
{
    use HasMediaTrait, SluggableCrudTrait;

    const IMAGE_COLLECTION_NAME = 'images';

    protected $auditColumn = true;

    protected $fillable = [
        'name',
        'price',
        'attribute_set_id',
        'description',
        'sku',
        'stock',
        'status',
        'parent_id'
    ];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes', 'product_id', 'attribute_id')->withPivot('value');
    }

    public function variants()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }
}
