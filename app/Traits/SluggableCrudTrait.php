<?php
/**
 * Created by PhpStorm.
 * User: shipu
 * Date: 27/1/19
 * Time: 9:15 PM
 */

namespace App\Traits;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

trait SluggableCrudTrait
{
    use Sluggable, SluggableScopeHelpers;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
            ]
        ];
    }
}
