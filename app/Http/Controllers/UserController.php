<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
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
        return $this->form('users.create', new User());
    }

    public function store(CreateUserRequest $request)
    {
        $request->createUser();

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        return $this->form('users.edit', $user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $request->updateUser($user);

        return redirect()->route('users.show', $user->id);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }

    protected function form($view, User $user)
    {
        return view($view, [
            'professions' => Profession::orderBy('title', 'ASC')->get(),
            'skills' => Skill::orderBy('name', 'ASC')->get(),
            'roles' => trans('users.roles'),
            'user' => $user,
        ]);
    }
}
