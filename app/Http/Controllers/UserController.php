<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index')
            ->with('users', User::all())
            ->with('title', 'Listado de usuarios');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        /*
         $user = User::find($id);
        
         if ($user == null) {
            return response()->view('errors.404', [], 404);
        }*/

        return view('users.show', compact('user'));
    }

    public function create()
    {
        return 'Creando un usuario nuevo';
    }
}
