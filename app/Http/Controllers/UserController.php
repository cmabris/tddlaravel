<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $title = 'Listado de usuarios';

        $users = [
            'Joel',
            'Ellie',
            'Tess',
            'Tommy',
            'Bill',
            '<script>alert("Click aquí")</script>',
        ];

        return view('users', compact('users', 'title'));
    }

    public function show($id)
    {
        return 'Mostrando los detalles del usuario: ' . $id;
    }

    public function create()
    {
        return 'Creando un usuario nuevo';
    }
}
