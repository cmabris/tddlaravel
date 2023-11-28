<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Profession;
use App\Skill;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index')
            ->with('users', User::all())
            ->with('title', 'Listado de usuarios');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function create()
    {
        $professions = Profession::orderBy('title', 'ASC')->get();
        $skills = Skill::orderBy('name', 'ASC')->get();
        $roles = trans('users.roles');
        $user = new User();

        return view('users.create', compact('user', 'professions', 'skills', 'roles'));
    }

    public function store(CreateUserRequest $request)
    {
        $request->createUser();

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        $professions = Profession::orderBy('title', 'ASC')->get();
        $skills = Skill::orderBy('name', 'ASC')->get();
        $roles = trans('users.roles');

        return view('users.edit', compact('user', 'professions', 'skills', 'roles'));
    }

    public function update(User $user)
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => '',
        ]);

        if ($data['password'] != null) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.show', $user->id);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }

}
