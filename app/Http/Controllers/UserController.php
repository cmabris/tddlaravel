<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return 'Usuarios';
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
