<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('roles-permissions.roles.index'); // Vista donde se mostrará el componente Livewire
    }
}
