<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 24.03.2018
 * Time: 00:44
 */

namespace Sitetpl\Http\Controllers\Admin;


class Controller extends \Sitetpl\Http\Controllers\Controller
{
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }
}