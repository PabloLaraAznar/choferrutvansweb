<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolesPermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('roles-permissions.roles-permissions.index'); // Vista donde se mostrará el componente Livewire
    }
}
