<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HandleBackpackCrudPassword
{
    /**
     * Handle password input fields.
     *
     * @param Request $request
     */
    protected function handlePasswordInput( Request $request )
    {
        // Remove fields not present on the user.
        $request->request->remove('password_confirmation');

        // Encrypt password if specified.
        if ( $request->input('password') ) {
            $request->request->set('password', bcrypt($request->input('password')));
        } else {
            $request->request->remove('password');
        }
    }
}
