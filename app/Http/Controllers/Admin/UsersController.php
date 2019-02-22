<?php

namespace Sitetpl\Http\Controllers\Admin;

use Sitetpl\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    const PAGESIZE = 50;

    /**
     * Display a listing of the users.
     *
     * @param \Illuminate\Http\Response $req
     * @return mixed
     */
    public function index(\Illuminate\Http\Response $req)
    {
        // get all the user
        $users = User::simplePaginate($this::PAGESIZE);

        // load the view and pass the user
        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
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
            ['name' => 'required|unique:users', 'email' => 'required|email|unique:users']
        );

        $user = User::create(
            [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => str_random(16)
            ]
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', __('general.form.flash.created', ['name' => $user->name]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Sitetpl\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $params = ['user' => $user,];
        return view('admin.users.show')->with($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Sitetpl\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $params = ['user' => $user,];
        return view('admin.users.edit')->with($params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Sitetpl\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        $this->validate(
            $request,
            [
                'name' => 'required|unique:users,name,' . $user->id,
                'email' => 'required|email|unique:users,email,' . $user->id
            ]
        );

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', trans('general.form.flash.updated', ['name' => $user->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Sitetpl\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', trans('general.form.flash.deleted', ['name' => $user->name]));
    }
}
