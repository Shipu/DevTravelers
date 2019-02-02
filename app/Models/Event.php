<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Shipu\Watchable\Traits\HasAuditColumn;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Event extends BaseModel implements HasMedia
{
    use Sluggable, HasAuditColumn, HasMediaTrait;

    const IMAGE_COLLECTION_NAME = 'image';

    protected $auditColumn = true;

    protected $fillable = [
        'name',
        'slug',
        'location',
        'start',
        'end',
        'registration_start',
        'registration_end',
        'description',
        'amount',
        'approximate_amount',
        'event_type',
        'paid_event',
        'payment_options',
        'remarks',
        'status',
    ];

    protected $casts = [
        'location' => 'object'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function assets()
    {
        return $this->belongsToMany(Asset::class, 'event_assets', 'event_id', 'asset_id');
    }

    public function getLatAttribute()
    {
        return $this->location->lat;
    }

    public function getLngAttribute()
    {
        return $this->location->lng;
    }
}
