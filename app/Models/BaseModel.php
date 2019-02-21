<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Shipu\Watchable\Traits\HasAuditColumn;

class BaseModel extends BaseModelWithoutCrud
{
    use CrudTrait, HasAuditColumn;
}
