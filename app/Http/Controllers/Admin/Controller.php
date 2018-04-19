<?php
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