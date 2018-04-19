<?php

namespace Sitetpl\Http\Controllers\Admin;


use Sitetpl\Models\Admin;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
	const PAGESIZE = 50;

	/**
	 * Display a listing of the admins.
	 *
	 * @param \Illuminate\Http\Response $req
	 * @return mixed
	 */
	public function index(\Illuminate\Http\Response $req)
	{
		// get all the admin
		$admins = Admin::simplePaginate($this::PAGESIZE);

		// load the view and pass the admin
		return view('admin.admins.index', ['admins' => $admins]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('admin.admins.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate(
						$request,
						['name' => 'required|unique:admins', 'email' => 'required|email|unique:admins']
		);

		$admin = Admin::create(
						[
							'name' => $request->input('name'),
							'email' => $request->input('email'),
							'password' => str_random(16)
						]
		);

		return redirect()->route('admin.admins.index')
			->with('success', __('general.form.flash.created', ['name' => $admin->name]));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Sitetpl\Models\Admin $admin
	 * @return \Illuminate\Http\Response
	 */
	public function show(Admin $admin)
	{
		$params = ['admin' => $admin,];
		return view('admin.admins.show')->with($params);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Sitetpl\Models\Admin $admin
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Admin $admin)
	{
		$params = ['admin' => $admin,];
		return view('admin.admins.edit')->with($params);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Sitetpl\Models\Admin $admin
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Admin $admin)
	{

		$this->validate(
						$request,
						[
							'name' => 'required|unique:admins,name,' . $admin->id,
							'email' => 'required|email|unique:admins,email,' . $admin->id
						]
		);

		$admin->name = $request->input('name');
		$admin->email = $request->input('email');

		$admin->save();

		return redirect()->route('admin.admins.index')
			->with('success', trans('general.form.flash.updated', ['name' => $admin->name]));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Sitetpl\Models\Admin $admin
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Admin $admin)
	{
		$admin->delete();

		return redirect()->route('admin.admins.index')
			->with('success', trans('general.form.flash.deleted', ['name' => $admin->name]));
	}
}
