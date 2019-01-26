<?php

namespace App\Models;

use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use Backpack\CRUD\CrudTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Permission\Traits\HasRoles;
use Tightenco\Parental\HasParentModel;

class BackpackUser extends User implements HasMedia
{
    const IMAGE_COLLECTION_NAME = 'image';

    use CrudTrait, HasParentModel, HasRoles, HasMediaTrait;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password'
    ];

    protected $hidden = [ 'password', 'remember_token' ];

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'owner');
    }

    public function phone()
    {
        return $this->morphOne(PhoneNumber::class, 'owner');
    }
}
