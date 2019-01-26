<?php

namespace App\Models;

use Shipu\Settings\Traits\GetSettings;

class Setting extends BaseModel
{
    use GetSettings;

    protected $fillable = [ 'key', 'value' ];

    protected $table = 'site_settings';

    public $timestamps = false;

}
