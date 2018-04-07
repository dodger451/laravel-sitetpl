<?php

namespace Sitetpl\Http\Controllers\Admin;



use Sitetpl\Models\Admin;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all the nerds
        $nerds = Admin::all();

        // load the view and pass the nerds
        return view('admin.admins.index', ['admins' => $nerds]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:admins',
            'email' => 'required|unique:admins',
            'password' => 'required',
        ]);

        $admin = Admin::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);

        return redirect()->route('admin.admins.index')->with('success', __('general.form.flash.created',['name' => $admin->name]) );

    }

    /**
     * Display the specified resource.
     *
     * @param  \Sitetpl\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        $params = [
            'admin' => $admin,
        ];
        return view('admin.admins.show')->with($params);
        

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Sitetpl\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        $params = [
            'admin' => $admin,
        ];
        return view('admin.admins.edit')->with($params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Sitetpl\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {

        $this->validate($request, [
            'name' => 'required|unique:admins',
            'email' => 'required|email|unique:admins,email,'.$admin->id,
            'password' => 'required',
        ]);

        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->password = $request->input('password');

        $admin->save();

        return redirect()->route('admin.admins.index')->with('success', trans('general.form.flash.updated',['name' => $admin->name]));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Sitetpl\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', trans('general.form.flash.deleted',['name' => $admin->name]));

    }
}
