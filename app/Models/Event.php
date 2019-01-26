<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Shipu\Watchable\Traits\HasAuditColumn;

class Event extends BaseModel
{
    use Sluggable, HasAuditColumn;

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

    public function getLatAttribute()
    {
        return $this->location->lat;
    }

    public function getLngAttribute()
    {
        return $this->location->lng;
    }
}
