<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function home()
    {
        $users = User::all();
        // Obtener la lista de respaldos
        /*$files = Storage::disk('local')->files('Laravel');
        $backupFiles = array_filter($files, function ($file) {
            return strpos($file, '.zip') !== false;
        });*/

        return view('admin.home', compact('users'));
    }

}

