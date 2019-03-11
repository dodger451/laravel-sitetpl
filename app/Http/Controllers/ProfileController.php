<?php

namespace Sitetpl\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $params = ['user' => auth()->user()];
        return view('profile.show')->with($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = auth()->user();
        $params = ['user' => $user,];
        return view('profile.edit')->with($params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();

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
            ->route('home')
            ->with('success', trans('general.form.flash.updated', ['name' => $user->name]));
    }

}
