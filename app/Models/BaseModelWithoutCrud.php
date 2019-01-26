<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Shipu\Watchable\Traits\DynamicAttributes;
use Shipu\Watchable\Traits\HasAuditColumn;
use Shipu\Watchable\Traits\HasModelAttributesEvents;
use Shipu\Watchable\Traits\HasModelEvents;

class BaseModelWithoutCrud extends Model
{
    use HasAuditColumn, HasModelEvents, HasModelAttributesEvents, DynamicAttributes;

    static $useDefaultDateSerializer = false;

    protected static function boot()
    {
        parent::boot();
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        if (static::$useDefaultDateSerializer) {
            return parent::serializeDate($date);
        }

        return $date->format(\DateTime::ISO8601);
    }
}
